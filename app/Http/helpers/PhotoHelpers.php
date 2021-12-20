<?php

namespace App\Http\helpers;

use App\Models\Transactions;
use App\Models\PurchasingTypes;
use App\Models\Photable;

use App\Http\helpers\FileUpload;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoHelpers {
    
    public $photo_temp_directory = "";
    public $photo_directory = "";
    public $photo_eloquent = NULL;
    public $photo_file = NULL;
    public $photo_filename = "";

    public function initialize() {
        if(request()->hasFile('photo')) {
            $file = request()->file("photo");
            $extension = $file->getClientOriginalExtension();
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $this->photo_filename = $name.".".$extension;
            $this->photo_temp_directory = "temp".DIRECTORY_SEPARATOR."photo_temp_directory".DIRECTORY_SEPARATOR."image_".Str::uuid();
            $this->photo_directory = "product".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."product_".Str::uuid();

            Storage::putFileAs($this->photo_temp_directory, request()->file('photo'), $this->photo_filename);

            $photo = new Photable();
            $photo->path = $this->photo_directory;
            $photo->photo_name = $this->photo_filename;

            $this->photo_eloquent = $photo;

        }
    }

    public function savePhotable($asPhotable) {
        $asPhotable->photo()->save($this->photo_eloquent);
    }

    public function movedFiles($to, $from) {
        Storage::move($from."/".$this->photo_filename, $to."/".$this->photo_filename);
    }
}

?>