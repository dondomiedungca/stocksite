<?php

namespace App\Http\helpers;

use App\Models\Transactions;
use App\Models\PurchasingTypes;
use App\Models\Photable;

use App\Http\helpers\FileUpload;

use Illuminate\Support\Facades\Storage;

class PhotoHelpers {
    public static function createPhotable($path = NULL, $request = NULL, $connect_to) {
        if($request->hasFile('photo')) {
            $file = $request['photo'];
            $extension = $file->getClientOriginalExtension();
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $exact_name = $name.".".$extension;

            Storage::putFileAs($path, $request->file('photo'), $exact_name);

            $photo = new Photable();
            $photo->path = $path;
            $photo->photo_name = $exact_name;

            $connect_to->photo()->save($photo);

            return true;
        } else {
            return false;
        }
    }

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
}

?>