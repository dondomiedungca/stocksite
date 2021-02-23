<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\ProductTypes;
use App\Models\ProductAttributes;
use App\Models\ColumnSelection;

class product_type_seeder_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $columns = [
            [
                'column_name' => 'serial_number',
                'required' => 'YES',
                'manual' => "YES",
                'data_type' => 'STRING',
                'input_type' => 'INPUT'
            ],
            [
                'column_name' => 'model_number',
                'required' => 'YES',
                'manual' => "YES",
                'data_type' => 'STRING',
                'input_type' => 'INPUT'
            ],
            [
                'column_name' => 'description',
                'required' => 'YES',
                'manual' => "YES",
                'data_type' => 'STRING',
                'input_type' => 'INPUT'
            ],
            [
                'column_name' => 'form_factor',
                'required' => 'YES',
                'manual' => "YES",
                'data_type' => 'STRING',
                'input_type' => 'SELECTION',
                'selection' => [
                    '5.25"',
                    '3.5"',
                    '2.5"',
                    '1.8"',
                    'Less than 1.8"',
                ]
            ],
            [
                'column_name' => 'vendor',
                'required' => 'YES',
                'manual' => "YES",
                'data_type' => 'STRING',
                'input_type' => 'SELECTION',
                'selection' => [
                    'Western Digital',
                    'Seagate',
                    'Toshiba',
                    'Samsung',
                    'Fujitsu'
                ]
            ]
        ];

        $product_type = new ProductTypes();
        $product_type->product_name = "Hard Drive";
        $product_type->user_id = 1;
        $product_type->save();

        foreach ($columns as $key => $value) {
            $product_attribute = new ProductAttributes();
            $product_attribute->product_column_name = $value['column_name'];
            $product_attribute->product_column_is_required = $value['required'] == "YES" ? 1 : 0;
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
    }
}
