<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transfer;
use OpenApi\Attributes as OA;

class TransactionController extends Controller
{
    #[OA\Get(
        path: '/api/transactions',
        summary: 'Get Transaction History',
        tags: ['Transactions'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Transaction history retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'type', type: 'string', enum: ['IN', 'OUT']),
                                    new OA\Property(property: 'amount', type: 'number'),
                                    new OA\Property(property: 'note', type: 'string'),
                                    new OA\Property(property: 'date', type: 'string'),
                                    new OA\Property(property: 'counterparty', type: 'integer'),
                                ],
                                type: 'object'
                            )
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request)
    {
        $user = $request->user();

        // Ambil semua transaksi dimana user adalah pengirim ATAU penerima
        $transactions = Transfer::where('sender_id', $user->id)
            ->orWhere('recipient_account', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $transactions->map(function($trx) use ($user) {
                return [
                    'id' => $trx->id,
                    'type' => $trx->sender_id == $user->id ? 'OUT' : 'IN',
                    'amount' => $trx->amount,
                    'note' => $trx->note,
                    'date' => $trx->created_at->format('d M Y H:i'),
                    'counterparty' => $trx->sender_id == $user->id ? $trx->recipient_account : $trx->sender_id
                ];
            })
        ]);
    }
}