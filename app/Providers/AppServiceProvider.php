<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Log;

use App\Events\QueueProcessing;
use Illuminate\Support\Facades\Bus;

use App\Http\helpers\BatchHelpers;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            $payload = $event->job->payload();
            $batch = unserialize($payload['data']['command']);

            $result = BatchHelpers::willBroadcastProcess($batch->batchId);
        });

        Queue::after(function (JobProcessed $event) {
            $payload = $event->job->payload();
            $batch = unserialize($payload['data']['command']);
            $batch_details = Bus::findBatch($batch->batchId);

            if((int) $batch_details->failedJobs > 0) {
                if((int) $batch_details->progress() == 100) {
                    BatchHelpers::generateDuration($batch->batchId);
                    BatchHelpers::importMessage($batch->batchId, "File content was successfully inserted to database.");
                    broadcast(new QueueProcessing("complete", BatchHelpers::getBatch($batch->batchId)));
                    $remove = BatchHelpers::removeFromProcessing($batch->batchId);
                }
            }
        });
    }
}
