<?php

namespace App\Http\helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\FileUploaded;
use App\Models\Transactions;

class FileUpload {
    public $temp_directory = "";
    public $directories = [];

    public $file_eloquent = NULL;
    public $file_filename = "";
    public $headers = [];

    public $contents = [];
    public $valid = 0;
    public $invalid = 0;
    public $new = 0;
    public $exist = 0;
    public $total = 0;

    const EXCEPTIONS = ['""""""'];

    public function headerValidation($columns) {
        
        $act_as_candidate_header = $this->headers;
        $header_diff = array_diff($columns, $act_as_candidate_header);

        return [
            'isValid' => count($header_diff) ? false : true,
            'difference' => $header_diff
        ];
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

    public function chunkFile($chunk_count) {
        self::createFileDirectory($this->temp_directory);
        $chunks = array_chunk($this->contents, $chunk_count);

        foreach ($chunks as $key => $chunk_data) {
            file_put_contents(storage_path("app".DIRECTORY_SEPARATOR.$this->temp_directory.DIRECTORY_SEPARATOR."chunk-".$key.".csv"), $chunk_data);
        }

        $this->directories = glob(storage_path("app".DIRECTORY_SEPARATOR.$this->temp_directory.DIRECTORY_SEPARATOR."*.csv"));

        return $this->directories;
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

    public static function excelForDevelopers($fileToParse) {
        $file = $fileToParse;
        $fileName = $file->getClientOriginalName();
    	$folderName = self::generateTempFolderName('default_excel_destination');
        $temp = storage_path().DIRECTORY_SEPARATOR .'app'.DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR.$folderName;
    	$zip = new \ZipArchive();
    	$zip->open($file);
    	$zip->extractTo($temp);
    	$data = simplexml_load_file($temp.DIRECTORY_SEPARATOR."xl".DIRECTORY_SEPARATOR."worksheets".DIRECTORY_SEPARATOR."sheet1.xml");
    	$strings = simplexml_load_file($temp.DIRECTORY_SEPARATOR."xl".DIRECTORY_SEPARATOR."sharedStrings.xml");
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
    
            if (!self::deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) {
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
                $file_upload_invalids[] = $item;
                $collection[$k] = false;
            }
        };

        $collection = array_filter($collection);
        $data["invalids"] = $file_upload_invalids;
        $data["collection"] = $collection;

        return $data;
    }

    public function initialize() {
        if(request()->has("file")) {
            $file = request()->file("file");
            $extension = $file->getClientOriginalExtension();
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $this->file_filename = $name.".".$extension;
            
            if($extension == 'xlsx') {
                $xlsx = self::excelForDevelopers($file);
                $this->headers = $xlsx["headers"];
                $this->total = $xlsx["total_content"] - 1;
                $this->contents = self::excelFileContentFormatToCSV($xlsx["data"]);
            } else {
                $csv = array_map('str_getcsv', file($file));
                $this->headers = $csv[0];
                $this->total = count(array_slice($csv, 1));
                $this->contents = self::excelFileContentFormatToCSV(array_slice($csv, 1));
            }

            $this->temp_directory = "temp".DIRECTORY_SEPARATOR."chunks".DIRECTORY_SEPARATOR."chunk_".Str::uuid();

            $this->file_eloquent = new FileUploaded;
            $this->file_eloquent->file_name = $this->file_filename;
            $this->file_eloquent->total_content = $this->total;
            $this->file_eloquent->valid = $this->valid;
            $this->file_eloquent->invalid = $this->invalid;
            $this->file_eloquent->new = $this->new;
            $this->file_eloquent->exist = $this->exist;
            $this->file_eloquent->user_id = Auth::user()->id;
            $this->file_eloquent->save();
        }
    }

    public function updateFileEloquent($id, $valid, $invalid, $new, $exist) {
        $file_eloquent = $this->getFileEloquent($id);
        $file_eloquent->valid = $file_eloquent->valid + $valid;
        $file_eloquent->invalid = $file_eloquent->invalid + $invalid;
        $file_eloquent->new = $file_eloquent->new + $new;
        $file_eloquent->exist = $file_eloquent->exist + $exist;
        $file_eloquent->save();
    }

    public function tryToRemove($directory) {
        self::deleteDirectory($directory);
        if(self::isDirEmpty($this->temp_directory)) {
            self::deleteDirectory(storage_path("app".DIRECTORY_SEPARATOR.$this->temp_directory));
            return true;
        }

        return false;
    }

    public static function isDirEmpty($dir) {
        $handle = opendir(storage_path("app".DIRECTORY_SEPARATOR.$dir));
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
            closedir($handle);
            return false;
            }
        }
        closedir($handle);
        return true;
    }

    public function getFileEloquent($id) {
        return FileUploaded::find($id);
    }
}

?>