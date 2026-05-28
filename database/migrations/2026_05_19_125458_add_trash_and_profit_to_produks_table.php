<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {

            $table->integer('modal')->default(0);

            $table->integer('diskon')->default(0);

            $table->boolean('promo')->default(false);

            $table->softDeletes();
        });

        Schema::table('kategoris', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {

            $table->dropColumn([
                'modal',
                'diskon',
                'promo'
            ]);

            $table->dropSoftDeletes();
        });

        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};