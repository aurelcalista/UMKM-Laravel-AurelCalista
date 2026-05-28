<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Matikan foreign key check sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        Schema::table('detail_transaksis', function (Blueprint $table) {
            // Hapus foreign key lama (gunakan try-catch untuk menghindari error)
            try {
                $table->dropForeign(['produk_id']);
            } catch (\Exception $e) {
                // Foreign key mungkin sudah tidak ada atau berbeda nama
                // Coba cek dan hapus manual
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_NAME = 'detail_transaksis' 
                    AND COLUMN_NAME = 'produk_id'
                    AND CONSTRAINT_SCHEMA = DATABASE()
                ");
                
                if (!empty($foreignKeys)) {
                    $constraintName = $foreignKeys[0]->CONSTRAINT_NAME;
                    DB::statement("ALTER TABLE detail_transaksis DROP FOREIGN KEY {$constraintName}");
                }
            }
        });

        // Tambah foreign key baru
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->foreign('produk_id')
                ->references('id')
                ->on('produks')
                ->onDelete('restrict');
        });
        
        // Aktifkan kembali foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        Schema::table('detail_transaksis', function (Blueprint $table) {
            try {
                $table->dropForeign(['produk_id']);
            } catch (\Exception $e) {
                // Ignore jika tidak ada
            }
            
            $table->foreign('produk_id')
                ->references('id')
                ->on('produks')
                ->onDelete('cascade');
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};