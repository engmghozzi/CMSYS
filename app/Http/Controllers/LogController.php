<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogController extends Controller
{

    public function index(Request $request)
    {
        $query = Log::with('user');

        // Filter by action type
        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get logs with pagination
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $actionTypes = Log::getActionTypes();
        $modelTypes = Log::getModelTypes();
        $users = User::orderBy('name')->get();

        return view('pages.logs.index', compact('logs', 'actionTypes', 'modelTypes', 'users'));
    }
}
