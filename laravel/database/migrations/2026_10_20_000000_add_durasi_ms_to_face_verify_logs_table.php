<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurasiMsToFaceVerifyLogsTable extends Migration
{
    /**
     * Lama proses verifikasi (Laravel → Face API → respons), dalam milidetik.
     * Sumber data "waktu respons verifikasi rata-rata" di artikel.
     * NULL = Face API tidak merespons (timeout/koneksi gagal) atau log lama.
     */
    public function up()
    {
        Schema::table('face_verify_logs', function (Blueprint $table) {
            $table->unsignedInteger('durasi_ms')->nullable()->after('antispoof_label');
        });
    }

    public function down()
    {
        Schema::table('face_verify_logs', function (Blueprint $table) {
            $table->dropColumn('durasi_ms');
        });
    }
}
