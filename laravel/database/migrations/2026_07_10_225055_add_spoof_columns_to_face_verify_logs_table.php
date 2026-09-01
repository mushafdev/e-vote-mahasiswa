<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpoofColumnsToFaceVerifyLogsTable extends Migration
{
    public function up()
    {
        Schema::table('face_verify_logs', function (Blueprint $table) {
            $table->boolean('is_real')->default(true)->after('match');
            $table->float('spoof_score', 5, 4)->default(1.0)->after('is_real');
            $table->string('antispoof_label', 20)->default('real')->after('spoof_score');
        });
    }

    public function down()
    {
        Schema::table('face_verify_logs', function (Blueprint $table) {
            $table->dropColumn(['is_real', 'spoof_score', 'antispoof_label']);
        });
    }
}
