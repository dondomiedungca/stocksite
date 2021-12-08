<?php

namespace App\Http\helpers;

use App\Models\Transactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileUpload {
    const EXCEPTIONS = ['""""""'];

    public static function isValidHeader($file, $extension, $product_type) {
        $product_header = $product_type['product_type']->show_columns();
        if($extension == 'csv') {
            $csv_as_array = array_map('str_getcsv', file($file));
            $candidate_header = $csv_as_array[0];

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
        } else {
            $act_as_candidate_header = $file;
            $header_diff = array_diff($product_header, $act_as_candidate_header);

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

    public static function createFileDirectory($path) {

        if(!Storage::exists($path)) {
            Storage::makeDirectory($path);
        } else {
            Storage::deleteDirectory($path);
            Storage::makeDirectory($path);
        }

        return $path;
    }

    public static function chunkFile($directory, $name, $file, $chunk_count, $ext) {
        if($ext == 'csv') {
            $file = file($file);
        }
        $chunks = array_chunk($file, $chunk_count);

        foreach ($chunks as $key => $chunk_data) {
            file_put_contents(storage_path("app/".$directory."/chunk-".$key.".csv"), $chunk_data);
        }

        return true;
    }
    
    public static function checkIfDirectoryExist($path) {
        if(!Storage::exists($path)) {
            return false;
        } else {
            return true;
        }
    }

    public static function removePath($path) {
        Storage::deleteDirectory($path);

        return true;
    }

    public static function excelForDevelopers($fileToParse, $sheet) {
        $file = $fileToParse;
        $fileName = $file->getClientOriginalName();
    	$folderName = self::generateTempFolderName('default_excel_destination');
        $temp = storage_path().'/app/temp/'.$folderName;
    	$zip = new \ZipArchive();
    	$zip->open($file);
    	$zip->extractTo($temp);
    	$data = simplexml_load_file($temp."/xl/worksheets/sheet1.xml");
    	$strings = simplexml_load_file($temp."/xl/sharedStrings.xml");
    	$rows = $data->sheetData->row;

        $headers = self::getExcelHeaderComplete($rows[0], $strings);
        $data = [];
        for($i = 1; $i < count($rows); $i++){
            $dataCells = [];
            $content = [];
            foreach($rows[$i] as $c){
                array_push($dataCells, self::removeDigits((string) $c['r']));
                $rawData = self::getExcelCellValue($c, $strings);
                array_push($content, $rawData);
            }
            array_push($data, $content);
        }

        self::deleteDirectory($temp);
        
        return [
            "file_name" => $fileName,
            "headers" => $headers["header"],
            "data" => $data,
            "total_content" => count($rows)
        ];
    }

    public static function generateTempFolderName($prefix){
        return uniqid($prefix);
    }

    public static function getExcelHeaderComplete($rows, $strings){
        $header = [];
        $headerCells = [];
        $type = (string)$rows->c['t'];
        foreach($rows as $head){
            if($type == "inlineStr"){
                $value = (string) $head->is->t;
                if($value != "" && !is_null($value)){
                    array_push($header, trim(preg_replace('/\([^)]+\)/', '', $value)));
                    array_push($headerCells, self::removeDigits((string) $head['r']));
                }
            }
            else if($type == 's'){
                if($head->v != ""){
                    array_push($header, trim((string) $strings->si[(int) $head->v]->t));
                    array_push($headerCells, self::removeDigits((string) $head['r']));
                }
            }
            
        }
        $header = array_map('strtolower', $header);
        return [
            'header' => $header,
            'headerCells' => $headerCells
        ];
    }

    public static function removeDigits($string){
    	return preg_replace('/[^a-zA-Z]/', '', $string);
    }

    public static function getExcelCellValue($data, $strings){
        $type = (string) $data['t'];
        if($type == 's'){
            $value = (string) $strings->si[(int) $data->v]->t;
        }
        else if($type == 'inlineStr'){
            $value = (string) $data->is->t;
        }
        else if($type == 'n'){
            $value = (string) $data->v;
        }
        else{
            $value = (string) $data->v;
        }
        return $value;
    }

    public static function excelFileContentFormatToCSV($file) {
        return array_map(function($item){
            // check if there is a comma(,) characters, this is important to match like csv then wrap it in quotes
            foreach ($item as $key => $val) {
                preg_match('/,/', $val, $result);
                if(count($result) > 0) {
                    // Remove all special characters (�)
                    $val = preg_replace('/[\x00-\x1F\x80-\xFF]/', "", $val);
                    // We have to wrap this column to make it as csv when the value has comma
                    $val = "\"$val\"";
                    // Remove "end of line" sign because this will break the quote like this
                    // "2021-04-01T19:36:09-07:00,.....,...
                    $val = preg_replace('/[\x00]/', "", $val);
                    // lastly, we only need a line break for each row
                    $item[$key] = ($key + 1) == count($item) ? "$val\n" : $val;
                } else {
                    $item[$key] = ($key + 1) == count($item) ? "$val\n" : $item[$key];
                }
            }
            //
            $item = implode(",", $item);
            return $item;
        }, $file);
    }

    public static function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }
    
        if (!is_dir($dir)) {
            return unlink($dir);
        }
    
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
    
            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
    
        }
    
        return rmdir($dir);
    }

    public static function parsedCSVToArrayWithColumns($collection, $columns)
    {
        $file_upload_invalids = [];

        foreach($collection as $k => $item){
            $FILE_DELIMITER = ',';
            $initial = true;
            $item = str_replace("\n", "", $item);
            $item = explode($FILE_DELIMITER, $item);
            $toRemove = [];
            foreach ($item as $key => $val) {
                preg_match('/"[^,\n\x00]*/', $val, $output);
                // if the start quote was found, start maanging the column for this index
                if(count($output) > 0 && (!in_array(@$output[0], self::EXCEPTIONS))) {
                    // if the match doesn't have a '"' in the end like '""""""' of fee preview, proceed to concatenation process
                    // or else ignore this current value of exploded item and make it as whole value for column 
                    if($output[0] != '"' && @$output[0][strlen(@$output[0]) -1] != '"') {
                        $starting = $key + 1;
                        $toConcatenate = $item[$key]; 
                        for ($i=$starting; $i < count($item); $i++) { 
                            preg_match('/[^,\n\x00]*"/', $item[$i], $output2);
                            // if the end quote was found, meaning the whole paragraph is ready for closing and set this for column in this index of this array
                            if(count($output2) > 0 && @$output2[0] != '"') {
                                $toConcatenate.= ",".$item[$i];
                                $toRemove[] = $i;
                                break;
                            } else {
                                $toConcatenate.= ",".$item[$i];
                                $toRemove[] = $i;
                            }
                        }
                        $item[$key] = $toConcatenate;
                    }
                }
                $item[$key] = preg_replace('/[\x00-\x1F\x80-\xFF]/', "", $item[$key]);
            }
            foreach ($toRemove as $key => $value) {
                $index = $value;
                if($initial !== true) {
                    $index -= $key;
                }
                $initial = false;
                array_splice($item, $index, 1);
            }
            if(count($columns) === count($item)) {
                $item = array_combine($columns, $item);
                $collection[$k] = $item;
            } else {
                $collection[$k] = false;
            }
        };

        $collection = array_filter($collection);
        $data["invalids"] = $file_upload_invalids;
        $data["collection"] = $collection;

        return $data;
    }
}

?>