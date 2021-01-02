<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

use App\Models\Transactions;
use App\Models\PurchasingTypes;
use App\Models\Inventory;
use App\Models\FileUploaded;

class ImportItemFile implements ShouldQueue
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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Redis::throttle('import-file')->allow(2)->every(1)->then(function () {
            
        }, function () {
            // Could not obtain lock; this job will be re-queued
            return $this->release(2);
        });

        $purchasing_type = PurchasingTypes::find(1);
        $purchasing_type->increment('quantity');
        $purchasing_type->increment('total_items_to_received');
    }
}
