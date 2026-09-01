<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_otps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemilih_id');
            $table->foreign('pemilih_id')->references('id')->on('pemilihs')->onUpdate('cascade')->onDelete('cascade');
            $table->string('jenis',10)->nullable();
            $table->string('role',10);
            $table->string('otp',10);
            $table->timestamp('expire_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_otps');
    }
}
