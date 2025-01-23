<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModuleListController extends Controller
{
    public function module_list()
    {
        $filePath = base_path('modules_statuses.json');
        $module_status = json_decode(file_get_contents($filePath));
        return response()->json([
            'module_status' => $module_status,
        ]);
    }
}
