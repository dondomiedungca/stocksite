<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
        return view('admin.products.product_list');
    }

    public function productImport() {
        return view('admin.products.product_import');
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

    public function getAllProductTypes() {
        $product_types = ProductTypes::with('user', 'product_attributes', 'product_attributes.column_selections')
                                        ->orderBy("created_at", "ASC")
                                        ->get();
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

    public function saveFile(Request $request) {
        $filename = $request['file_name'];
        $file = $request['file'];
        $extension = $file->getClientOriginalExtension();
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        if($request->hasFile('photo')) {
            $photo = $request['photo'];
            $photo_extension = $photo->getClientOriginalExtension();
            $photo_name = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            $photo_file_name = $photo_name.".".$photo_extension;
        } else {
            $photo_file_name = NULL;
            $photo_path = NULL;
        }

        $basis = $request['basis'];
        $transaction_id = $request['basis'] == 'purchasing' ? $request['transaction_id'] : NULL;
        $purchasing_type_id = $request['basis'] == 'purchasing' ? $request['purchasing_type_id'] : NULL;

        $chunk_count = 1000;

        $product_type = $this->getProductType($request['product_type_id']);
        $check_header = FileUpload::isValidHeader($file, $extension, $product_type);
        
        if(!$check_header['isValid']) {
            $data['heading'] = "Header(s) Incorrect";
            $data['message'] = "The ff. headers are not found on your file </br></br> \"<b>".implode($check_header['difference'], ",")."</b>\"";
            $data['success'] = false;

            return $data;
        }
        $header = $check_header['header'];

        // create directory
        $path = "product/temp/$filename";
        $directory = FileUpload::createFileDirectory($path);

        if($request->hasFile('photo')) {
            if($purchasing_type_id != NULL) {
                $purchasing_type = PurchasingTypes::find($purchasing_type_id);
                if(!$purchasing_type->photo()->exists()) {
                    $photo_path = "product/Purchase_Order_Type_$purchasing_type_id";
                    PhotoHelpers::savePhoto($photo_path, $photo, $photo_file_name);
                }
            } else {
                $photo_path = "product/Product_Image_".time();
                PhotoHelpers::savePhoto($photo_path, $photo, $photo_file_name);
            }
        }

        // create chunk
        $chunks = FileUpload::chunkFile($directory, $name, $file, $chunk_count);

        // run queue on every chunk of file
        $chunk_files = glob(storage_path("app/$directory/*.csv"));
        $jobs = [];

        foreach ($chunk_files as $key => $chunk_file) {
            $jobs[] = new ImportItemFile($header, $photo_file_name, $photo_path, $key, $chunk_count, $filename, $name."-".time(), $chunk_file, $request['product_type_id'], Auth::user()->id, $transaction_id, $purchasing_type_id, $request['basis']);
        }

        $counter = Counter::find(7);
		$counter->increment('counter');
        $queue_no = $counter->prefix . str_pad($counter->counter, 6,'0',STR_PAD_LEFT);

        $batch = Bus::batch($jobs)->then(function (Batch $batch){
            // All jobs completed successfully...
        })->catch(function (Batch $batch, Throwable $e) {
            // Only First batch job failure detected...
            $batch->cancel();
        })->finally(function (Batch $batch) use ($directory) {
            // The batch has finished executing...
            // I putted here the event calling as failed because the error message will only get if the batch was cancelled,
            // the batch will continue to finish other jobs even the previous jobs are failed
            if($batch->cancelled()) {
                broadcast(new QueueProcessing("failed", BatchHelpers::getBatch($batch->id)));
            }
            // This only run at fresh batch, not when retry
            if((int) $batch->progress() == 100) {
                BatchHelpers::generateDuration($batch->id);
                BatchHelpers::importMessage($batch->id, "File content was successfully inserted to database.");
                broadcast(new QueueProcessing("complete", BatchHelpers::getBatch($batch->id)));
                FileUpload::removePath($directory);
            }
            BatchHelpers::removeFromProcessing($batch->id);
        })->name($name.' - Product File Uploading*_*'.$queue_no)->onQueue('product_imports')->dispatch();

        broadcast(new QueueProcessing("create", BatchHelpers::getBatch($batch->id)));

        $data['success'] = true;
        $data['heading'] = "Added To Queue";
        $data['message'] = "File upload process was added to system queue with no. <b>".$queue_no."</b>, we will notify you once it is done";
        $data['uuid'] = $batch->id;

        return $data;

    }

    public function getProductType($id) {
        $product_type = ProductTypes::whereId($id)
                                    ->with('product_attributes')
                                    ->first();
        $data['product_type'] = $product_type;

        return $data;
    }

    public function getProductsViaProductType($product_type_id = NULL, $searches = NULL) {
        $searches = json_decode($searches);
        $products = Inventory::query();

        $products->select(
                    "inventories.*",
                    DB::raw("case when item_cosmetic_description != NULL then item_cosmetic_description else 'No Cosmetic Description' end as item_cosmetic_description"),
                    DB::raw("case when item_description != NULL then item_description else 'No Description' end as item_description"),
                    DB::raw('DATE_FORMAT(created_at, "%M %d, %Y") as date_created')
                    )
                ->with('product_type.product_attributes.column_selections', 'status', 'cosmetic');
        $products->where('product_type_id', $product_type_id);

        $products = $products->paginate(10);

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
        $data['isSuccess'] = true;
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

        $data['isSuccess'] = true;
        $data['message'] = 'Product has been removed successfuly';
        $data['heading'] = "Product Removed";

        return response()->json($data);
    }

}
