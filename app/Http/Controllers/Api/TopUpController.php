<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TopUpRequest; // HARUS S (Requests) dan R gede
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TopUpController extends Controller
{
    public function store(TopUpRequest $request)
    {
        $data = $request->validated();
        $user = User::find(1); // tetap menggunakan simulasi user ID 1

        return DB::transaction(function () use ($user, $data) {
            $user->increment('balance', $data['amount']);

            return response() -> json([
                'status' => 'success',
                'message' => 'Top Up berhasil, sekarang lo kaya lagi!',
                'new_balance' => $user->fresh()->balance,
            ]);
        });
    }
}

// class TopUpController extends Controller
// {
//     public function store(TopUpRequest $request)
//     {
//         $data = $request->validated();
//         $user = User::find(1); // tetap menggunakan simulasi user ID 1

//         return DB::transaction(function () use) ($user, $data) {
//             // tambahkan increment utuk penambahan saldo
//             $user->increment('balance', $data['amount']);

//             return response()->json([
//                 'status' => 'success',
//                 'message' => 'Top Up berhasil, sekarang lo kaya lagi!',
//                 'new_balance => $user->fresh()->balance,'
//             ])
//         }
//     }
// }
