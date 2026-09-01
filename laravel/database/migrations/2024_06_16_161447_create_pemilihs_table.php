<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemilihsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemilihs', function (Blueprint $table) {
            $table->id('id')->startingValue(51661);
            $table->string('nik',20)->nullable();
            $table->string('nama',70);
            $table->string('no_anggota',30);
            $table->string('email',50)->unique();
            $table->string('telp',30)->unique();
            $table->string('photo',20)->nullable();
            $table->char('jenis_kelamin',1)->nullable();
            $table->string('tempat_lahir',100)->nullable();
            $table->string('tgl_lahir',12)->nullable();
            $table->text('ktp')->nullable();
            $table->longText('file_photo')->nullable();
            $table->string('alamat',100)->nullable();
            $table->char('rw',3)->nullable();
            $table->char('rt',3)->nullable();
            $table->char('role',3)->default('dpt');
            $table->char('level',3)->default('dpt');
            $table->unsignedBigInteger('kab_id')->nullable();
            $table->unsignedBigInteger('kec_id')->nullable();
            $table->unsignedBigInteger('kel_id')->nullable();
            $table->foreign('kab_id')->references('id')->on('kabupatens')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kec_id')->references('id')->on('kecamatans')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kel_id')->references('id')->on('kelurahans')->onUpdate('cascade')->onDelete('restrict');
            
            $table->unsignedBigInteger('inserted_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('verify_by')->nullable();
            $table->foreign('inserted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('deleted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('verify_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->dateTime($column = 'verify_time', $precision = 0)->nullable();
            $table->timestamps();
            $table->softDeletesTz($column = 'deleted_at', $precision = 0);
            $table->dateTime($column = 'email_verified_at', $precision = 0)->nullable();
            $table->enum('verifikasi',['N','Y']);
            $table->string('catatan_verifikasi',150)->nullable();
            $table->enum('email_verifikasi',['N','Y']);
            $table->enum('aktif',['N','Y']);
            $table->string('username',100);
            $table->string('password',100);
            $table->string('remember_token',255)->nullable();
            $table->unsignedBigInteger('calon_kandidat_id')->nullable()->index();
            $table->foreign('calon_kandidat_id')->references('id')->on('calon_kandidats')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('calon_kandidat_2_id')->nullable()->index();
            $table->foreign('calon_kandidat_2_id')->references('id')->on('calon_kandidats_2')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('kandidat_id')->nullable()->index();
            $table->foreign('kandidat_id')->references('id')->on('kandidats')->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime($column = 'time_vote_2', $precision = 0)->nullable();
            $table->dateTime($column = 'time_vote_final', $precision = 0)->nullable();
            $table->index(['no_anggota', 'nama', 'email', 'telp']);
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
