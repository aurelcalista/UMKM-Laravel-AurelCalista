<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori — hanya tambah kalau belum ada
        if (!Schema::hasColumn('kategoris', 'deleted_at')) {
            Schema::table('kategoris', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Promos — hanya tambah kalau belum ada
        if (!Schema::hasColumn('promos', 'deleted_at')) {
            Schema::table('promos', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kategoris', 'deleted_at')) {
            Schema::table('kategoris', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('promos', 'deleted_at')) {
            Schema::table('promos', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};