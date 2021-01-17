<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\helpers\BatchHelpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

use App\Models\BatchJobs;
use App\Events\QueueProcessing;

class ReportsController extends Controller
{
    public function index() {
        return view('admin.reports.index');
    }

    public function showQueues() {
        return view('admin.reports.queue');
    }

    public function getCurrent() {
        $processing = BatchJobs::whereIn('id', function($query)
                        {
                            $query->select('uuid')
                                ->from('current_job_running');
                        })
                        ->get();

        $processing->transform(function ($value) {
            $value->created_date = $value->created_at->format('M d, Y - h:i A');
            return $value;
        });

        $data['current'] = $processing;

        return response()->json($data);
    }

    public function getBatches() {
        $forProcess = BatchJobs::where('cancelled_at', NULL)
                        ->where('pending_jobs', '>', 0)
                        ->whereNotIn('id', function($query)
                        {
                            $query->select('uuid')
                                ->from('current_job_running');
                        })
                        ->orderBy('created_at', 'ASC')
                        ->get();
        $forProcess->transform(function ($value) {
            $value->created_date = $value->created_at->format('M d, Y - h:i A');
            return $value;
        });

        $data['forProcess'] = $forProcess;

        return response()->json($data);
    }

    public function getBatchesFailed() {
        $failed = BatchJobs::where('failed_job_ids', '!=', "[]")
                        ->orderBy('created_at', 'DESC')
                        ->paginate(10);

        $failed->getCollection()->transform(function ($value) {
            $value->failed_jobs = $value->failed_jobs();
            $value->created_date = $value->created_at->format('M d, Y - h:i A');
            return $value;
        });

        $data['failed'] = $failed;

        return response()->json($data);
    }

    public function getBatchesCompleted() {
        $completed = BatchJobs::with('message')->where('finished_at', '!=', NULL)
                        ->where('pending_jobs', '<=', 0)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(10);

        $completed->getCollection()->transform(function ($value) {
            $value->created_date = $value->created_at->format('M d, Y - h:i A');
            return $value;
        });

        $data['completed'] = $completed;

        return response()->json($data);
    }

    public function queueRetry($id) {
        BatchHelpers::removeCancelledAt($id);
        broadcast(new QueueProcessing("create", BatchHelpers::getBatch($id)));
        $result = Artisan::call('queue:retry-batch', ['id' => $id]);

        $data['response'] = $result;
        return response()->json($data);
    }
}
