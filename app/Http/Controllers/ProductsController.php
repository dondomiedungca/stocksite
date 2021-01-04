<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

use App\Http\helpers\TransactionHelpers;
use App\Http\helpers\FileUpload;

use App\Jobs\ImportItemFile;

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

class ProductsController extends Controller
{
    public function index() {
        return view('admin.products.index');
    }

    public function productTypes() {
        return view('admin.products.product_types');
    }

    public function addProductTypes(Request $request) {
        $product_type = new ProductTypes();
        $product_type->product_name = $request->product_name;
        $product_type->user_id = Auth::user()->id;
        $product_type->save();

        foreach ($request['columns'] as $key => $value) {
            $product_attribute = new ProductAttributes();
            $product_attribute->product_column_name = $value['column_name'];
            $product_attribute->product_column_is_required = $value['required'] == "YES" ? 1 : 0;
            $product_attribute->product_column_manual_fillable = $value['manual'] == "YES" ? 1 : 0;
            $product_attribute->product_column_data_type = $value['data_type'];
            $product_attribute->product_column_input_type = $value['input_type'];
            $product_attribute->product_type()->associate($product_type);
            $product_attribute->save();

            if($value['data_type'] != "DATE" && $value['input_type'] == "SELECTION") {
                foreach ($value['selection'] as $key => $val) {
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
                                        ->orderBy("created_at", "DESC")
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

                TransactionHelpers::manageStatus($request['transaction_id']);
            }
        } else {
            $counter->increment('counter');
            $stock_number = $counter->prefix . str_pad($counter->counter, 6,'0',STR_PAD_LEFT);
            $inventory = $this->createInventory($request, $stock_number, $request['product_type_id']);
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
        $inventory->inventory_status_id = $request['inventory']['item_status_id'];
        $inventory->inventory_cosmetic_Id = $request['inventory']['item_cosmetic_id'];
        $inventory->item_cosmetic_description = $request['inventory']['item_cosmetic_description'];
        $inventory->item_description = $request['inventory']['item_description'];
        $inventory->origin_price = $request['inventory']['origin_price'];
        $inventory->selling_price = $request['inventory']['selling_price'];
        $inventory->discount_percentage = $request['inventory']['discount_percentage'];
        $inventory->details = $request['inventory']['details'];

        return $inventory;
    }

    public function saveFile(Request $request) {
        $filename = $request['file_name'];
        $file = $request['file'];
        $basis = $request['basis'];
        $transaction_id = $request['basis'] == 'purchasing' ? $request['transaction_id'] : NULL;
        $purchasing_type_id = $request['basis'] == 'purchasing' ? $request['purchasing_type_id'] : NULL;
        $extension = $file->getClientOriginalExtension();
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $product_type = $this->getProductType($request['product_type_id']);
        $check_header = FileUpload::isValidHeader($file, $extension, $product_type);
        $header = $check_header['header'];
        
        if(!$check_header['isValid']) {
            $data['heading'] = "Header(s) Incorrect";
            $data['message'] = "The ff. headers are not found on your file </br></br> <b>".implode($check_header['difference'], ",")."</b>";
            $data['success'] = false;

            return $data;
        }

        // create directory
        $directory = FileUpload::createFileDirectory($name);

        // create chunk
        $chunks = FileUpload::chunkFile($directory, $name, $file, 1000);

        // run queue on every chunk of file
        $chunk_files = glob(storage_path("app/$directory/*.csv"));
        $jobs = [];

        foreach ($chunk_files as $key => $chunk_file) {
            $jobs[] = new ImportItemFile($header, $name."-".time(), $chunk_file, $request['product_type_id'], Auth::user()->id, $transaction_id, $purchasing_type_id, $request['basis']);
        }

        $batch = Bus::batch($jobs)->then(function (Batch $batch) {
            // All jobs completed successfully...
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
        })->name($name.' - Product File Uploading')->onQueue('product_imports')->dispatch();

        $data['success'] = true;
        $data['heading'] = "Added To Queue";
        $data['message'] = "File upload process was added to system queue, we will notify you once it is done";
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
}
