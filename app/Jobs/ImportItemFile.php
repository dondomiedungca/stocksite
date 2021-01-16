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

use Illuminate\Support\Facades\Log;
use Exception;

class ImportItemFile implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $header;
    public $chunk_position;
    public $chunk_count;
    public $filename_time;
    public $filename;
    public $chunk_directory;
    public $product_type_id;
    public $user_id;
    public $transaction_id;
    public $purchasing_type_id;
    public $basis;

    public $timeout = 3600;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header, $chunk_position, $chunk_count, $filename, $filename_time, $chunk_directory, $product_type_id = NULL, $user_id = NULL, $transaction_id = NULL, $purchasing_type_id = NULL, $basis = NULL)
    {
        $this->header = $header;
        $this->chunk_position = $chunk_position;
        $this->chunk_count = $chunk_count;
        $this->filename_time = $filename_time;
        $this->filename = $filename;
        $this->chunk_directory = $chunk_directory;
        $this->product_type_id = $product_type_id;
        $this->user_id = $user_id;
        $this->transactio_id = $transaction_id;
        $this->purchasing_type_id = $purchasing_type_id;
        $this->basis = $basis;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }
        
        $csv_data = array_map('str_getcsv', file($this->chunk_directory));
        if($this->chunk_position == 0) {
           unset($csv_data[0]);
        }
        foreach ($csv_data as $key => $row) {
            $data = array_combine($this->header, $row);
            $checked_data = isNotEmpty($this->product_type_id, $data);

            if($checked_data['isValid']) {
                Products::importItems($this->product_type_id, $this->purchasing_type_id, $data);
            } else {
                $this->batch()->cancel();
                $line = (((int) $this->chunk_position) * (int) $this->chunk_count) + ($key + 1);
                throw new Exception($this->filename." file has a row that don't have a required fields, first appearance at row ".$line.". </br></br> fields that no data are \"<b>".implode($checked_data['no_data'], ",")."</b>\". </br></br> Try to upload again with a correct file. |");
            }
        }

        
    }

    public function failed(\Exception $e) {
        $batch = BatchHelpers::getBatch($this->batch()->id);

        if($this->batch()->cancelled() && $batch->retry == 1) {
            broadcast(new QueueProcessing("failed", $batch));
            BatchHelpers::removeFromProcessing($this->batch()->id);
        }
    }
}
