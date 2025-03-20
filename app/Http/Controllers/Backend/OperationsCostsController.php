<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OperationCost;
use Illuminate\Http\Request;

class OperationsCostsController extends Controller
{
    public function index()
    {
        $operations_costs = OperationCost::paginate(10);

        return view('backend.pages.operations.operation-costs', compact('operations_costs'));
    }

    function operations_pagination(Request $request)
    {
        if ($request->ajax()) {
            $operations_costs = OperationCost::paginate(10);
            return view('backend.pages.operations.search-result', compact('operations_costs'));
        }
    }

    public function search_operations(Request $request)
    {
        $operations_costs = OperationCost::where(function ($q) use ($request) {
            $q->Where('cost', 'LIKE', "%" . strip_tags($request->cost) . "%");
        })->paginate(10);
        return $operations_costs->total() >= 1 ? view('backend.pages.operations.search-result', compact('operations_costs'))->render() : response()->json(['status' => __('nothing')]);
    }

    public function edit_info(Request $request)
    {
        $request->validate([
            'edit_cost' => 'required|numeric|min:0',
        ]);

        OperationCost::findOrFail($request->edit_operation_id)
            ->update([
                'cost' => $request->edit_cost,
            ]);

        toastr_success(__('Operation Cost Successfully Updated'));
        return back();
    }
}
