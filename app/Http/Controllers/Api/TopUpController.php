<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TopUpRequest; // HARUS S (Requests) dan R gede
use App\Models\User;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class TopUpController extends Controller
{
    #[OA\Post(
        path: '/api/top-up',
        summary: 'Top Up Saldo',
        tags: ['Transactions'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['amount'],
                properties: [
                    new OA\Property(property: 'amount', type: 'number', example: 100000),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Top up berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'new_balance', type: 'number'),
                    ]
                )
            )
        ]
    )]
    public function store(TopUpRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

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
