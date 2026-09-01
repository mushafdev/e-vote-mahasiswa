<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaceVerifyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('face_verify_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemilih_id');
            $table->foreign('pemilih_id')->references('id')->on('pemilihs')->onDelete('cascade');
            $table->enum('aksi', ['register', 'verify_login', 'verify_vote']);
            $table->boolean('match')->default(false);
            $table->float('confidence', 5, 2)->default(0);
            $table->float('threshold', 5, 2)->default(0);
            $table->string('keterangan', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->index(['pemilih_id', 'aksi', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('face_verify_logs');
    }
}
