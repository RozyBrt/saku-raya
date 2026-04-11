<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transfer;

class TransactionController extends Controller
{
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