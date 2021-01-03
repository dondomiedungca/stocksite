<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
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

class ImportItemFile implements ShouldQueue, ShouldBeUnique, ShouldBeUniqueUntilProcessing
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filename;
    public $directory;
    public $user_id;
    public $transaction_id;
    public $purchasing_type_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename = NULL, $directory = NULL, $user_id = NULL, $transaction_id = NULL, $purchasing_type_id = NULL)
    {
        $this->filename = $filename;
        $this->directory = $directory;
        $this->user_id = $user_id;
        $this->transactio_id = $transaction_id;
        $this->purchasing_type_id = $purchasing_type_id;
    }

    public function uniqueId()
    {
        return $this->filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
    }
}
