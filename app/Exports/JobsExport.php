<?php

namespace App\Exports;

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JobsExport implements FromView
{
    protected $jobs;

    public function __construct($jobs)
    {
        $this->jobs = $jobs;
    }

    public function view(): View
    {
        return view('backend.pages.job.export-jobs', [
            'jobs' => $this->jobs
        ]);
    }
}

