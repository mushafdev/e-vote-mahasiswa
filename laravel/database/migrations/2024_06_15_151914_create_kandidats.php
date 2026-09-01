<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKandidats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kandidats', function (Blueprint $table) {
            $table->id()->startingValue(6762512);
            $table->integer('no_urut');
            $table->string('no_anggota',30);
            $table->string('nama',70);
            $table->string('file_photo')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->enum('calon_kandidat_status',['N','Y']);
            $table->enum('kandidat_status',['N','Y']);
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
        Schema::dropIfExists('kandidats');
    }
}
