<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function all()
    {
        $reports = Report::where('client_id',Auth::guard('web')->user()->id)
            ->where('reporter','client')->paginate(20);
        return view('frontend.user.client.report.all', compact('reports'));
    }
}
