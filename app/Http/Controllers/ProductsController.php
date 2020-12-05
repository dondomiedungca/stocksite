<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\ColumnSelection;
use App\Models\ProductAttributes;
use App\Models\ProductTypes;

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

    public function removeProductTypes($id = NULL) {
        ProductTypes::find($id)->delete();

        $data['success'] = true;
        $data['heading'] = "Product Type Removed";
        $data['message'] = "Product type has been removed successfuly"; 

        return response()->json($data);
    }
}
