<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->startingValue(19343);
            $table->unsignedBigInteger('unit_kerja_id')->nullable();
            $table->foreign('unit_kerja_id')->references('id')->on('kelompok_tims')->onUpdate('cascade')->onDelete('restrict');
            $table->string('nama',100);
            $table->string('jabatan',100)->nullable();
            $table->string('email',30)->nullable();
            $table->string('alamat')->nullable();
            $table->char('kontak',20)->nullable();
            $table->string('photo')->nullable();
            $table->char('level',2);
            $table->char('role',2);
            $table->string('username',100);
            $table->string('password',100);
            $table->string('remember_token',255)->nullable();
            $table->unsignedBigInteger('inserted_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('inserted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletesTz($column = 'deleted_at', $precision = 0);
            $table->enum('active',['Y','N']);
            $table->string('thema',)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
