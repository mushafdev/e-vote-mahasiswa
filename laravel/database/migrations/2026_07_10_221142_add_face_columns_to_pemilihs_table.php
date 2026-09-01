<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFaceColumnsToPemilihsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pemilihs', function (Blueprint $table) {
            $table->boolean('face_registered')->default(false)->after('file_photo');
            $table->timestamp('face_registered_at')->nullable()->after('face_registered');
        });
    }

    public function down()
    {
        Schema::table('pemilihs', function (Blueprint $table) {
            $table->dropColumn(['face_registered', 'face_registered_at']);
        });
    }
}
