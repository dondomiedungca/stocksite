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

    public $header;
    public $chunk_position;
    public $photo_path;
    public $photo_name;
    public $chunk_count;
    public $filename_time;
    public $filename;
    public $chunk_directory;
    public $product_type_id;
    public $user_id;
    public $transaction_id;
    public $purchasing_type_id;
    public $basis;
    public $ext;

    public $timeout = 3600;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header, $photo_name, $photo_path, $chunk_position, $chunk_count, $filename, $filename_time, $chunk_directory, $product_type_id = NULL, $user_id = NULL, $transaction_id = NULL, $purchasing_type_id = NULL, $basis = NULL, $ext)
    {
        $this->header = $header;
        $this->photo_name = $photo_name;
        $this->photo_path = $photo_path;
        $this->chunk_position = $chunk_position;
        $this->chunk_count = $chunk_count;
        $this->filename_time = $filename_time;
        $this->filename = $filename;
        $this->chunk_directory = $chunk_directory;
        $this->product_type_id = $product_type_id;
        $this->user_id = $user_id;
        $this->transaction_id = $transaction_id;
        $this->purchasing_type_id = $purchasing_type_id;
        $this->basis = $basis;
        $this->ext = $ext;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            sleep(5);
            if ($this->batch()->cancelled()) {
                return;
            }
            
            $csv_data = file($this->chunk_directory);
            if($this->chunk_position == 0 && $this->ext == 'csv') {
                unset($csv_data[0]);
            }
            // if this kind of importing is related on purchasing type
            if($this->purchasing_type_id != NULL) {
                $purchasing_type = PurchasingTypes::find($this->purchasing_type_id);
                if(!$purchasing_type->photo()->exists()) {
                    $photable = PhotoHelpers::saveThroughFileName($this->photo_path, $this->photo_name, $this->purchasing_type_id, NULL);
                }
            }
            $csv_data = FileUpload::parsedCSVToArrayWithColumns($csv_data, $this->header);
            foreach ($csv_data['collection'] as $key => $data) {
                $checked_data = isNotEmpty($this->product_type_id, $data);

                if($checked_data['isValid']) {
                    Products::importItems($this->photo_path, $this->photo_name, $this->product_type_id, $this->purchasing_type_id, $data);
                    if($this->transaction_id != "" && $this->transaction_id != NULL) {
                        try {
                            TransactionHelpers::manageStatus($this->transaction_id);
                        } catch (Exception $e) {
                            $this->batch()->cancel();
                            throw new Exception($e->getMessage()." |");
                        }
                    }
                } else {
                    $this->batch()->cancel();
                    $line = (((int) $this->chunk_position) * (int) $this->chunk_count) + ($key + 1);
                    // this is important to indicate the reason of failing jobs when showing queuing reports
                    throw new Exception($this->filename." file has a row that don't have a required fields, first appearance at row ".$line.". </br></br> fields that no data are \"<b>".implode($checked_data['no_data'], ",")."</b>\". </br></br> Try to upload again with a correct file. |");
                }
            }

    }

    public function failed(\Exception $e) {
        $batch = BatchHelpers::getBatch($this->batch()->id);
        // this will only run in RETRY mode and if the job is failed, the error message will not get
        // unless the jobs is cancelled
        if($this->batch()->cancelled() && $batch->retry == 1) {
            broadcast(new QueueProcessing("failed", $batch));
            BatchHelpers::removeFromProcessing($this->batch()->id);
        }
    }

    public function removeExcessColumn($reports, $columns) {
        $until = count($columns);
        $offset = 0;

        return array_map(function($item) use ($offset, $until) {
            $item = str_replace("\n", "", $item);
            $item = explode(",", $item);
            $item = array_slice($item, $offset, $until);
            $item = implode(",", $item);

            return $item;
        }, $reports);
    }
}
