<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('alamat')->nullable();
            $table->string('phone')->nullable();
            $table->string('facebook')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('instagram')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('whatsapp_link')->nullable();
            $table->string('map')->nullable();
            $table->string('email')->nullable();
            $table->string('domain')->nullable();
            $table->tinyInteger('pajak')->unsigned();
            $table->enum('persediaan_kp',['N','Y']);
            $table->date('tgl_ro_start')->nullable();
            $table->date('tgl_ro_end')->nullable();
            $table->unsignedBigInteger('inserted_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('inserted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('settings');
    }
}
