<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Http\helpers\TransactionHelpers;
use App\Http\helpers\FileUpload;
use App\Http\helpers\BatchHelpers;
use App\Http\helpers\PhotoHelpers;

use App\Jobs\ImportItemFile;
use App\Events\QueueProcessing;

use App\Models\ColumnSelection;
use App\Models\ProductAttributes;
use App\Models\ProductTypes;
use App\Models\Counter;
use App\Models\InventoryCosmetic;
use App\Models\InventoryStatus;
use App\Models\Transactions;
use App\Models\PurchaseOrders;
use App\Models\PurchasingTypes;
use App\Models\Inventory;
use App\Models\Photable;

class ProductsController extends Controller
{
    public function index() {
        return view('admin.products.index');
    }

    public function productTypes() {
        return view('admin.products.product_types');
    }

    public function productList() {
        $product_types = ProductTypes::with('user', 'product_attributes', 'product_attributes.column_selections')
                                     ->orderBy("created_at", "ASC")
                                     ->get();

        $currency = getCurrency();
        $cosmetics = InventoryCosmetic::all();
        $statuses = InventoryStatus::all();

        $data = [
            "product_types" => $product_types,
            "currency" => $currency,
            "cosmetics" => $cosmetics,
            "statuses" => $statuses,
        ];

        return view('admin.products.product_list')->with($data);
    }

    public function productImport() {
        $stock_number = $this->getStockNumber()["stock_number"];
        $product_types = $this->getProductTypesData();
        $cosmetics = $this->getCosmetics()["cosmetics"];
        $statuses = $this->getItemStatuses()["statuses"];

        $data = [
            "stock_number" => $stock_number,
            "product_types" => $product_types,
            "cosmetics" => $cosmetics,
            "statuses" => $statuses
        ];

        Log::info($data);

        return view('admin.products.product_import')->with($data);
    }

    public function addProductTypes(Request $request) {
        $product_type = new ProductTypes();
        $product_type->product_name = $request->product_name;
        $product_type->user_id = Auth::user()->id;
        $product_type->save();

        foreach ($request['columns'] as $key => $value) {
            $product_attribute = new ProductAttributes();
            $product_attribute->product_column_name = $value['column_name'];
            $product_attribute->product_column_is_required = $value['is_required'];
            $product_attribute->product_column_data_type = $value['data_type'];
            $product_attribute->product_column_input_type = $value['value_type'] == 1 ? "INPUT" : "SELECTION";
            $product_attribute->product_type()->associate($product_type);
            $product_attribute->save();

            if($value['data_type'] != "DATE" && $value['value_type'] == 0) {
                foreach ($value['selections'] as $key => $val) {
                    $column_selection = new ColumnSelection();
                    $column_selection->selection_name = $val;
                    $column_selection->column_name()->associate($product_attribute);
                    $column_selection->save();
                }
            }
        }

        $data['success'] = true;
        $data['heading'] = "New Product Type";
        $data['message'] = "New Product type has been added successfuly";   
        
        return response()->json($data);
    }

    public function getProductTypes() {
        $product_types = ProductTypes::with('user', 'product_attributes', 'product_attributes.column_selections')
                                        ->orderBy("created_at", "DESC")
                                        ->paginate(10);

        return response()->json($product_types);
    }

    public function getProductTypesData() {
        return ProductTypes::with('user', 'product_attributes', 'product_attributes.column_selections')
                                        ->orderBy("created_at", "ASC")
                                        ->get();
    }

    public function getAllProductTypes() {
        $product_types = $this->getProductTypesData();
        $data['product_types'] = $product_types;
        
        return response()->json($data);
    }

    public function removeProductTypes($id = NULL) {
        ProductTypes::find($id)->delete();

        $data['success'] = true;
        $data['heading'] = "Product Type Removed";
        $data['message'] = "Product type has been removed successfuly"; 

        return response()->json($data);
    }

    public function getStockNumber() {
        $counter = Counter::find(3);
        $stocks_number = $counter->prefix . str_pad($counter->counter + 1, 6,'0',STR_PAD_LEFT);

        return [
            'stock_number' => $stocks_number
        ];
    }

    public function getCosmetics() {
        $cosmetics = InventoryCosmetic::all();

        return [
            'cosmetics' => $cosmetics
        ];
    }

    public function getItemStatuses() {
        $statuses = InventoryStatus::all();

        return [
            'statuses' => $statuses
        ];
    }

    public function saveManualItem(Request $request) {
        $counter = Counter::find(3);

        if($request['basis'] == 'purchasing') {
            if(!isValidToReceive($request['purchasing_type_id'])) {
                $data['success'] = false;
                $data['heading'] = "Reached The Quantity";
                $data['message'] = "This Inventory is already reached the quantity";

                return $data;
            } else {
                $counter->increment('counter');
                $stock_number = $counter->prefix . str_pad($counter->counter, 6,'0',STR_PAD_LEFT);

                $purchase_order_type = PurchasingTypes::find($request['purchasing_type_id']);
                $purchase_order_type->increment('total_received');
                $purchase_order_type->decrement('total_items_to_receive');

                $inventory = $this->createInventory($request, $stock_number, $request['product_type_id']);

                $purchase_order_type->inventories()->save($inventory);

                if(!$purchase_order_type->photo()->exists()) {
                    $path = "product/Purchase_Order_Type_$purchase_order_type->id";
                    $directory = FileUpload::createFileDirectory($path);

                    $photable = PhotoHelpers::createPhotable($path, $request, $purchase_order_type);
                }

                TransactionHelpers::manageStatus($request['transaction_id']);
            }
        } else {
            $counter->increment('counter');
            $stock_number = $counter->prefix . str_pad($counter->counter, 6,'0',STR_PAD_LEFT);
            $inventory = $this->createInventory($request, $stock_number, $request['product_type_id']);
            
            $path = "product/Product_Image_$inventory->id";

            if(!FileUpload::checkIfDirectoryExist($path)) {
                $directory = FileUpload::createFileDirectory($path);
                $photable = PhotoHelpers::createPhotable($path, $request, $inventory);
            }
        }

        $data['success'] = true;
        $data['heading'] = "New Item Saved";
        $data['message'] = "<b>".$stock_number."</b> has been added to inventory";

        return $data;
    }

    public function createInventory($request, $stock_number, $product_type_id) {
        $inventory = new Inventory();
        $inventory->stock_number = $stock_number;
        $inventory->product_type_id = $product_type_id;
        $inventory->inventory_status_id = json_decode($request['inventory'])->item_status_id;
        $inventory->inventory_cosmetic_Id = json_decode($request['inventory'])->item_cosmetic_id;
        $inventory->item_cosmetic_description = json_decode($request['inventory'])->item_cosmetic_description;
        $inventory->item_description = json_decode($request['inventory'])->item_description;
        $inventory->origin_price = json_decode($request['inventory'])->origin_price;
        $inventory->selling_price = json_decode($request['inventory'])->selling_price;
        $inventory->discount_percentage = (json_decode($request['inventory'])->discount_percentage == NULL || json_decode($request['inventory'])->discount_percentage == '') ? 0.00 : json_decode($request['inventory'])->discount_percentage;
        $inventory->details = json_decode($request['inventory'])->details;
        $inventory->save();

        return $inventory;
    }

    public function saveFile(Request $request, PhotoHelpers $photo, FileUpload $docs) {
        try {

            $validator = Validator::make($request->all(), [
                'photo' => 'required',
                'file' => 'required',
                'product_type_id' => 'required',
                'basis' => 'required',
                'purchasing_type_id' => [
                    'nullable',
                    'integer',
                    Rule::requiredIf($request->basis == 'purchasing')
                ],
                'transaction_id' => [
                    'nullable',
                    'integer',
                    Rule::requiredIf($request->basis == 'purchasing')
                ],
            ])->validate();

            $photo->initialize();

            $basis = $request['basis'];
            $transaction_id = $request['basis'] == 'purchasing' ? $request['transaction_id'] : NULL;
            $purchasing_type_id = $request['basis'] == 'purchasing' ? $request['purchasing_type_id'] : NULL;
            
            $docs->initialize();

            $columns = $product_type = $this->getProductType($request['product_type_id'])["product_type"]->show_columns();

            $headerValidation = $docs->headerValidation($columns);

            if(!$headerValidation["isValid"]) {
                throw new \Exception("The ff. headers are not found on your file </br></br> \"<b>".implode($headerValidation['difference'], ",")."</b>\"");
            }

            if($docs->total == 0) {
                throw new \Exception("No data found in your file");
            }

            $chunks = $docs->chunkFile(200);

            foreach ($chunks as $key => $chunk_file) {
                $jobs[] = new ImportItemFile($photo, $docs, $chunk_file, $request['product_type_id'], $transaction_id, $purchasing_type_id);
            }

            $queue_no = getCounterNumber(7);

            $batch = BatchHelpers::processJobs($jobs, $docs->file_filename, $queue_no, "Product File Uploading");

            return response()->json([
                "message" => "File upload process was added to system queue with no. <b>".$queue_no."</b>, we will notify you once it is done",
                "success" => true
            ]);

        } catch (\Exception $e) {
            Log::info($e);
            return response()->json([
                "message" => $e->getMessage(),
                "success" => false
            ]);
        }

    }

    public function getProductType($id) {
        $product_type = ProductTypes::whereId($id)
                                    ->with('product_attributes')
                                    ->first();
        $data['product_type'] = $product_type;

        return $data;
    }

    public function getProductsViaProductType(Request $request) {
        $products = Inventory::query();
        $product_type_id = $request->selected_product_type_id;
        $search = $request['search'];

        $products->select(
                    "inventories.*",
                    DB::raw("case when (item_cosmetic_description != NULL OR item_cosmetic_description != '') then item_cosmetic_description else 'No Cosmetic Description' end as item_cosmetic_description"),
                    DB::raw("case when (item_description != NULL OR item_description != '') then item_description else 'No Description' end as item_description"),
                    DB::raw('DATE_FORMAT(created_at, "%M %d, %Y") as date_created')
                    )
                ->with('product_type.product_attributes.column_selections', 'status', 'cosmetic');
        $products->where('product_type_id', $product_type_id);

        if(!empty($search)) {
            $products->when($search['stock_number'] != "", function ($q) use ($search) {
                return $q->where('stock_number', 'like', '%'.$search['stock_number'].'%');
            });
            $products->when($search['inventory_status_id'] != 0, function ($q) use ($search) {
                return $q->where('inventory_status_id', $search['inventory_status_id']);
            });
            $products->when($search['inventory_cosmetic_id'] != 0, function ($q) use ($search) {
                return $q->where('inventory_cosmetic_id', $search['inventory_cosmetic_id']);
            });
            $products->whereBetween('origin_price', [$search['origin_price'][0], $search['origin_price'][1]]);
            $products->whereBetween('selling_price', [$search['selling_price'][0], $search['selling_price'][1]]);

            foreach ($search['details'] as $key => $value) {
                $products->when($search['details'][$key] != "", function ($q) use ($search, $key){
                    return $q->where("details->$key", "like", "%".$search['details'][$key]."%");
                });
            }
        }

        $products = $products->paginate($request->page_limit);

        return response()->json($products);
    }

    public function updateProduct(Request $request) {
        $inventory = $request['inventory'];

        $inv = Inventory::find($inventory['id']);
        $inv->inventory_status_id = $inventory['inventory_status_id'];
        $inv->inventory_cosmetic_id = $inventory['inventory_cosmetic_id'];
        $inv->item_cosmetic_description = $inventory['item_cosmetic_description'];
        $inv->item_description = $inventory['item_description'];
        $inv->origin_price = $inventory['origin_price'];
        $inv->selling_price = $inventory['selling_price'];
        $inv->discount_percentage = $inventory['discount_percentage'];
        $inv->details = $inventory['details'];
        $inv->save();

        $data['heading'] = 'Product Updated';
        $data['success'] = true;
        $data['message'] = $inventory['stock_number']." was successfully updated";

        return response()->json($data);

    }

    public function removeProduct(Request $request) {
        $inventory = $request['inventory'];

        $inv = Inventory::find($inventory['id']);

        // If this inventory was imported via local
        if($inv->photo()->exists()) {
            $relation_model_photo = $inv->photo()->pluck('path');
            $remaining_count_of_other_related = Photable::where([
                'path' => $relation_model_photo[0],
                'photable_type' => Inventory::class
            ])->get();

            if($inv->photo()->exists()) {
                if($remaining_count_of_other_related->count() > 1) {
                    $inv->photo()->delete();
                } else {
                    $inv->photo()->delete();
                    FileUpload::removePath($remaining_count_of_other_related[0]->path);
                }
            }
        }

        // if it is via purchasing
        if($inv->purchasing_type()->exists()) {
            $relation_model_photo_path_name = $inv->purchasing_type()->first()->photo->path;

            $inventory_remaining = $inv->purchasing_type()->first()->inventories->count();

            if($inventory_remaining == 1) {
                FileUpload::removePath($relation_model_photo_path_name);
                $inv->purchasing_type()->first()->photo()->delete();
            }
        }

        $inv->delete();

        $data['success'] = true;
        $data['message'] = 'Product has been removed successfuly';
        $data['heading'] = "Product Removed";

        return response()->json($data);
    }

}
