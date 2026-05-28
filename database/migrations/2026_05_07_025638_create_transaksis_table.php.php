<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('transaksis');
        
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->bigInteger('total_harga');
            $table->date('tanggal')->nullable();
            $table->string('status')->default('pending');
            $table->enum('approval_status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->string('metode_bayar');
            $table->string('metode_kirim');
            $table->text('alamat')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};