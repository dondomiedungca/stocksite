<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\BatchJobs;

class ReportsController extends Controller
{
    public function index() {
        return view('admin.reports.index');
    }

    public function showQueues() {
        return view('admin.reports.queue');
    }

    public function getBatches() {
        $forProcess = DB::table('job_batches')
                        ->where('cancelled_at', NULL)
                        ->where('pending_jobs', '!=', 0)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(10);
        $forProcess->getCollection()->transform(function ($value) {
            $value->created_date = $value->created_at->format('M d, Y - h:i A');
            return $value;
        });

        $data['forProcess'] = $forProcess;

        return response()->json($data);
    }

    public function getBatchesFailed() {
        $failed = BatchJobs::where('cancelled_at', '!=', NULL)
                        ->where('failed_jobs', '!=', 0)
                        ->where('failed_job_ids', '!=', "[]")
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
        $completed = DB::table('job_batches')
                        ->where('cancelled_at', NULL)
                        ->where('finished_at', '!=', NULL)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(10);

        $completed->getCollection()->transform(function ($value) {
            $value->created_date = date('M d, Y - h:i A', $value->created_at);
            return $value;
        });

        $data['completed'] = $completed;

        return response()->json($data);
    }
}
