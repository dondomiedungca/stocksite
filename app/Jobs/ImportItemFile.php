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

use Illuminate\Support\Facades\Log;
use Exception;

class ImportItemFile implements ShouldQueue, ShouldBeUnique, ShouldBeUniqueUntilProcessing
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $header;
    public $filename_time;
    public $chunk_directory;
    public $product_type_id;
    public $user_id;
    public $transaction_id;
    public $purchasing_type_id;
    public $basis;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header, $filename_time, $chunk_directory, $product_type_id = NULL, $user_id = NULL, $transaction_id = NULL, $purchasing_type_id = NULL, $basis = NULL)
    {
        $this->header = $header;
        $this->filename_time = $filename_time;
        $this->chunk_directory = $chunk_directory;
        $this->product_type_id = $product_type_id;
        $this->user_id = $user_id;
        $this->transactio_id = $transaction_id;
        $this->purchasing_type_id = $purchasing_type_id;
        $this->basis = $basis;
    }

    public function uniqueId()
    {
        return $this->filename_time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $csv_data = array_map('str_getcsv', file($this->chunk_directory));

        foreach ($csv_data as $key => $row) {
            if(count($this->header) == count($row)) {
                $data = array_combine($this->header, $row);
            } else {
                throw new Exception("Your file doesn't match the number of headers like your product header");
            }
        }

    }
}
