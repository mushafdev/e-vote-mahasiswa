<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoteVerificationColumnsToPemilihsTable extends Migration
{
    /**
     * Kolom-kolom verifikasi suara oleh panlih:
     * - verifikasi_vote : penanda keabsahan suara ('Y' sah, 'N' tidak sah,
     *                     NULL = belum diverifikasi). Alur voting men-set 'Y'
     *                     otomatis; rekap suara sah menghitung yang 'Y'.
     * - verify_vote_by  : id user panlih yang memverifikasi suara.
     * - verify_vote_time: waktu verifikasi suara.
     * - catatan_panlih  : catatan panlih saat memverifikasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pemilihs', function (Blueprint $table) {
            $table->enum('verifikasi_vote', ['N', 'Y'])->nullable()->after('vote_enc');
            $table->unsignedBigInteger('verify_vote_by')->nullable()->after('verifikasi_vote');
            $table->dateTime('verify_vote_time', 0)->nullable()->after('verify_vote_by');
            $table->string('catatan_panlih', 255)->nullable()->after('verify_vote_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pemilihs', function (Blueprint $table) {
            $table->dropColumn(['verifikasi_vote', 'verify_vote_by', 'verify_vote_time', 'catatan_panlih']);
        });
    }
}
