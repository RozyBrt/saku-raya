<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class StatusController extends Controller
{
    public function check()
    {
        return response()->json([
            'status' => 'success',
            'project' => 'Saku-Raya',
            'message' => 'Controller is working, bray! 🚀',
            'db_connection' => config('database.default')
        ]);
    }
}