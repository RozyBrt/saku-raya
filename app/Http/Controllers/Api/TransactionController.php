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
        summary: 'Get Transaction History with Filters & Pagination',
        tags: ['Transactions'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'type',
                in: 'query',
                description: 'Filter by transaction type (IN/OUT)',
                required: false,
                schema: new OA\Schema(type: 'string', enum: ['IN', 'OUT'])
            ),
            new OA\Parameter(
                name: 'search',
                in: 'query',
                description: 'Search by counterparty name or email',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Page number for pagination',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
        ],
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
                        new OA\Property(property: 'current_page', type: 'integer'),
                        new OA\Property(property: 'last_page', type: 'integer'),
                        new OA\Property(property: 'per_page', type: 'integer'),
                        new OA\Property(property: 'total', type: 'integer'),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request)
    {
        $user = $request->user();

        // Build query dengan filter dan search
        $query = Transfer::where(function($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->orWhere('recipient_account', $user->id);
        });

        // Filter berdasarkan type (IN/OUT)
        if ($request->has('type') && in_array($request->type, ['IN', 'OUT'])) {
            if ($request->type === 'OUT') {
                $query->where('sender_id', $user->id);
            } else {
                $query->where('recipient_account', $user->id);
            }
        }

        // Search berdasarkan counterparty (nama/email)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($user, $search) {
                // Untuk transaksi OUT: cari di recipient_account
                $q->where(function($subQ) use ($user, $search) {
                    $subQ->where('sender_id', $user->id)
                         ->where('recipient_account', 'LIKE', "%{$search}%");
                })
                // Untuk transaksi IN: cari di sender_id (yang merupakan user ID)
                ->orWhere(function($subQ) use ($user, $search) {
                    $subQ->where('recipient_account', $user->id)
                         ->whereHas('sender', function($senderQ) use ($search) {
                             $senderQ->where('name', 'LIKE', "%{$search}%")
                                    ->orWhere('email', 'LIKE', "%{$search}%");
                         });
                });
            });
        }

        // Pagination dengan 15 item per page
        $transactions = $query->orderBy('created_at', 'desc')
                              ->paginate(15);

        // Transform data
        $transformedData = $transactions->getCollection()->map(function($trx) use ($user) {
            return [
                'id' => $trx->id,
                'type' => $trx->sender_id == $user->id ? 'OUT' : 'IN',
                'amount' => $trx->amount,
                'note' => $trx->note,
                'date' => $trx->created_at->format('d M Y H:i'),
                'counterparty' => $trx->sender_id == $user->id ? $trx->recipient_account : $trx->sender_id
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $transformedData,
            'current_page' => $transactions->currentPage(),
            'last_page' => $transactions->lastPage(),
            'per_page' => $transactions->perPage(),
            'total' => $transactions->total(),
        ]);
    }
}