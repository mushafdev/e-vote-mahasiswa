<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalonKandidats2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calon_kandidats_2', function (Blueprint $table) {
            $table->id()->startingValue(8134234);
            $table->integer('no_urut');
            $table->string('no_anggota',30);
            $table->string('nama',70);
            $table->string('file_photo')->nullable();
            $table->longText('deskripsi')->nullable();
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
        Schema::dropIfExists('calon_kandidats');
    }
}
