<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('transfers', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        Schema::create('transfers', function(Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // Ini buat konekin ke tabel users
            $table->string('recipient_account');
            $table->decimal('amount', 15, 2); // artinya 15 digit dan 2 angka di belakang koma
            $table->string('note')->nullable();
            $table->string('status')->default('success');
            $table->timestamps(); // Ini yang otomati bikin 'created_at' & 'updated_at' 
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
