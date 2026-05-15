<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminLogController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (! $user || ! method_exists($user, 'isOwner') || ! $user->isOwner()) {
            abort(403);
        }

        $logs = ActivityLog::with('actor')->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.logs.index', compact('logs'));
    }
}
