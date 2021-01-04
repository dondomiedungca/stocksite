<?php

namespace App\Http\helpers;

use App\Models\Transactions;
use Illuminate\Support\Facades\Storage;

class FileUpload {

    public static function isValidHeader($file, $extension, $product_type) {
        if($extension == 'csv') {
            $csv_as_array = array_map('str_getcsv', file($file));
            $candidate_header = $csv_as_array[0];

            $product_header = $product_type['product_type']->show_columns();
            $header_diff = array_diff($product_header, $candidate_header);

            if(count($header_diff)) {
                return [
                    'isValid' => false,
                    'difference' => $header_diff,
                ];
            } else {
                return [
                    'isValid' => true,
                    'difference' => $header_diff,
                    'header' => $product_header
                ];
            }
        }
    }

    public static function createFileDirectory($filename) {
        $path = "product/temp/$filename";

        if(!Storage::exists($path)) {
            Storage::makeDirectory($path);
        } else {
            Storage::deleteDirectory($path);
            Storage::makeDirectory($path);
        }

        return $path;
    }

    public static function chunkFile($directory, $name, $file, $chunk_count) {
        $file = file($file);
        $chunks = array_chunk($file, $chunk_count);

        foreach ($chunks as $key => $chunk_data) {
            file_put_contents(storage_path("app/".$directory."/chunk-".$key.".csv"), $chunk_data);
        }

        return true;
    }

}

?>