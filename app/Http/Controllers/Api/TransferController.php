<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Wajib ada buat transaksi bray!

class TransferController extends Controller
{
    public function store(TransferRequest $request)
    {
        $data = $request->validated();
        
        // Simulasi: Kita anggap yang lagi login itu User ID 1
        $sender = User::find(1);

        // 1. Cek Saldo dulu, bray. Gak ada saldo = Gak ada transfer.
        if ($sender->balance < $data['amount']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Duit lo gak cukup bray! Saldo lo cuma ' . $sender->balance
            ], 400);
        }

        // 2. Jalankan Transaksi Database
        try {
            return DB::transaction(function () use ($sender, $data) {
                // Potong Saldo si Pengirim
                $sender->decrement('balance', $data['amount']);

                // Catat di Tabel Transfers
                $transfer = Transfer::create([
                    'sender_id' => $sender->id,
                    'recipient_account' => $data['recipient_account'],
                    'amount' => $data['amount'],
                    'note' => $data['note'] ?? null,
                    'status' => 'success'
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Transfer Saku-Raya Berhasil!',
                    'remaining_balance' => $sender->fresh()->balance,
                    'details' => $transfer
                ], 201);
            });
        } catch (\Exception $e) {
            // Kalau ada error apa pun di tengah jalan, DB bakal otomatis Rollback
            return response()->json([
                'status' => 'error',
                'message' => 'Aduh bray, sistem lagi oleng: ' . $e->getMessage()
            ], 500);
        }
    }
}