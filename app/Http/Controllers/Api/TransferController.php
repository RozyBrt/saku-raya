<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Wajib ada buat transaksi bray!
use OpenApi\Attributes as OA;

class TransferController extends Controller
{
    #[OA\Post(
        path: '/api/transfer',
        summary: 'Kirim transfer ke pengguna lain',
        tags: ['Transactions'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['recipient_account', 'amount'],
                properties: [
                    new OA\Property(property: 'recipient_account', type: 'string', example: 'receiver@example.com'),
                    new OA\Property(property: 'amount', type: 'number', example: 50000),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Transfer berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'remaining_balance', type: 'number'),
                        new OA\Property(property: 'details', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Transfer gagal',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string'),
                        new OA\Property(property: 'message', type: 'string'),
                    ]
                )
            ),
        ]
    )]
    public function store(TransferRequest $request)
    {
        $data = $request->validated();
        
        // Simulasi: Kita anggap yang lagi login itu User ID 1
        $sender = $request->user();

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
                // Cari penerima berdasarkan email
                $recipient = User::where('email', $data['recipient_account'])->first();

                // Potong Saldo si Pengirim
                $sender->decrement('balance', $data['amount']);

                // Tambah Saldo si Penerima
                $recipient->increment('balance', $data['amount']);

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