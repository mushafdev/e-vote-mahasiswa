<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tims', function (Blueprint $table) {
            $table->id();
            $table->string('nik',20)->nullable();
            $table->string('nama',70);
            $table->char('jenis_kelamin',1);
            $table->string('tempat_lahir',100)->nullable();
            $table->string('tgl_lahir',12)->nullable();
            $table->string('alamat',100);
            $table->char('rw',2)->nullable();
            $table->char('rt',2)->nullable();
            $table->char('tps',4)->nullable();
            $table->char('level',3);
            $table->char('role',3)->nullable();
            $table->string('username',100)->nullable();
            $table->string('password',100)->nullable();
            $table->string('remember_token',255)->nullable();
            $table->unsignedBigInteger('kelompok_tim_id')->nullable();
            $table->unsignedBigInteger('kab_id')->nullable();
            $table->unsignedBigInteger('kec_id')->nullable();
            $table->unsignedBigInteger('kel_id')->nullable();
            $table->foreign('kelompok_tim_id')->references('id')->on('kelompok_tims')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kab_id')->references('id')->on('kabupatens')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kec_id')->references('id')->on('kecamatans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kel_id')->references('id')->on('kelurahans')->onUpdate('cascade')->onDelete('cascade');
            $table->string('email',30)->nullable();
            $table->string('telp',20)->nullable();
            $table->unsignedBigInteger('inserted_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('inserted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletesTz($column = 'deleted_at', $precision = 0);
            $table->enum('active',['Y','N']);
            $table->string('photo',20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemilih');
    }
}
