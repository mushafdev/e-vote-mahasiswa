<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoteEncToPemilihsTable extends Migration
{
    /**
     * Kolom vote_enc menyimpan pilihan suara final (kandidat_id) dalam bentuk
     * ciphertext AES-256 (Laravel Crypt). Kolom kandidat_id tidak lagi diisi
     * pilihan asli — kehadiran vote_enc menjadi penanda "sudah memilih".
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pemilihs', function (Blueprint $table) {
            $table->text('vote_enc')->nullable()->after('kandidat_id');
        });
    }

    public function down()
    {
        Schema::table('pemilihs', function (Blueprint $table) {
            $table->dropColumn('vote_enc');
        });
    }
}
