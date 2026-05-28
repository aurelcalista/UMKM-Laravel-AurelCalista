<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {

            $table->id();

            // nama promo
            $table->string('nama_promo');

            // persen diskon
            $table->integer('diskon');

            // banner promo
            $table->string('banner')->nullable();

            // tanggal aktif
            $table->date('tanggal_mulai');

            // tanggal selesai
            $table->date('tanggal_selesai');

            // aktif / nonaktif
            $table->boolean('status')->default(true);

            // deskripsi promo
            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};