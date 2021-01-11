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
            // $event->connectionName
            // $event->job
            // $event->job->payload()
            $payload = $event->job->payload();
            $batch = unserialize($payload['data']['command']);

            $result = BatchHelpers::willBroadcastProcess($batch->batchId);
        });

        Queue::after(function (JobProcessed $event) {
            // $event->connectionName
            // $event->job
            // $event->job->payload()
            $payload = $event->job->payload();
            $batch = unserialize($payload['data']['command']);
            $batch_details = Bus::findBatch($batch->batchId);

            if((int) $batch_details->progress() == 100) {
                broadcast(new QueueProcessing("complete", BatchHelpers::getBatch($batch->batchId)));
                $remove = BatchHelpers::removeFromProcessing($batch->batchId);
                // $remove = BatchHelpers::removeCancelledAt($batch->batchId);
            }
        });
    }
}
