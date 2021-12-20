<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Transactions;
use App\Models\PurchasingTypes;
use App\Models\Inventory;
use App\Models\FileUploaded;

use App\Events\QueueProcessing;
use App\Http\helpers\BatchHelpers;
use App\Http\helpers\Products;
use App\Http\helpers\PhotoHelpers;
use App\Http\helpers\TransactionHelpers;
use App\Http\helpers\FileUpload;

use Illuminate\Support\Facades\Log;
use Exception;

class ImportItemFile implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $photo;
    public $docs;
    public $chunk_directory;
    public $product_type_id;
    public $transaction_id;
    public $purchasing_type_id;

    public $timeout = 3600;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($photo, $docs, $chunk_directory, $product_type_id = NULL, $transaction_id = NULL, $purchasing_type_id = NULL)
    {
        $this->photo = $photo;
        $this->docs = $docs;
        $this->chunk_directory = $chunk_directory;
        $this->product_type_id = $product_type_id;
        $this->transaction_id = $transaction_id;
        $this->purchasing_type_id = $purchasing_type_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            
            $csv_data = file($this->chunk_directory);

            // // if this kind of importing is related on purchasing type
            // if($this->purchasing_type_id != NULL) {
                // $purchasing_type = PurchasingTypes::find($this->purchasing_type_id);
                // if(!$purchasing_type->photo()->exists()) {
                //     $photable = PhotoHelpers::saveThroughFileName($this->photo_path, $this->photo_name, $this->purchasing_type_id, NULL);
                // }
            // }
            $csv_data = FileUpload::parsedCSVToArrayWithColumns($csv_data, $this->docs->headers);

            $this->docs->updateFileEloquent(
                $this->docs->file_eloquent->id,
                count($csv_data["collection"]),
                count($csv_data["invalids"]),
                0,
                0
            );

            $compiled_data = Products::createAndUpdateIfExist($csv_data["collection"], $this->product_type_id, $this->docs);

            $this->docs->updateFileEloquent(
                $this->docs->file_eloquent->id,
                0,
                0,
                count($compiled_data["new"]),
                count($compiled_data["exist"])
            );

            if($this->docs->tryToRemove($this->chunk_directory)) {
                $this->photo->movedFiles($this->photo->photo_directory, $this->photo->photo_temp_directory);
                if($this->purchasing_type_id != NULL) {
                    $purchasing_type = PurchasingTypes::find($this->purchasing_type_id);
                    if(!$purchasing_type->photo()->exists()) {
                        $this->photo->savePhotable($purchasing_type);
                    }
                } else {
                    $this->photo->savePhotable($this->docs->getFileEloquent($this->docs->file_eloquent->id));
                }
            }

    }

}
