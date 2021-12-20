<?php

namespace App\Http\helpers;

use App\Models\Transactions;
use App\Models\PurchasingTypes;
use App\Models\Photable;

use App\Http\helpers\FileUpload;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoHelpers {
    
    protected $photo_temp_directory = "";
    protected $photo_directory = "";
    public $photo_eloquent = NULL;
    public $photo_file = NULL;
    public $photo_filename = "";

    public static function savePhoto($path, $file, $photo_name) {
        FileUpload::createFileDirectory($path);

        Storage::putFileAs($path, $file, $photo_name);

        return true;
    }

    public static function saveThroughFileName($photo_path, $photo_name, $purchasing_type_id, $inventory) {
        if($purchasing_type_id != NULL) {
            $purchasing_type = PurchasingTypes::find($purchasing_type_id);
            
            $photo = new Photable();
            $photo->path = $photo_path;
            $photo->photo_name = $photo_name;

            $purchasing_type->photo()->save($photo);
        } else {
            $photo = new Photable();
            $photo->path = $photo_path;
            $photo->photo_name = $photo_name;

            $inventory->photo()->save($photo);
        }
    }


    public function initialize() {
        if(request()->hasFile('photo')) {
            $file = request()->file("photo");
            $extension = $file->getClientOriginalExtension();
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $this->photo_filename = $name.".".$extension;
            $this->photo_temp_directory = "temp/photo_temp_directory/image_".Str::uuid();
            $this->photo_directory = "product/images/product_".Str::uuid();

            Storage::putFileAs($this->photo_temp_directory, request()->file('photo'), $this->photo_filename);

            $photo = new Photable();
            $photo->path = $this->photo_directory;
            $photo->photo_name = $this->photo_filename;

            $this->photo_eloquent = $photo;

        }
    }
}

?>