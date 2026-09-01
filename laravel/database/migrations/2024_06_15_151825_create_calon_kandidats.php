<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalonKandidats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calon_kandidats', function (Blueprint $table) {
            $table->id()->startingValue(313423);
            $table->string('no_anggota',30);
            $table->string('status_kode')->nullable();
            $table->year('thn_iuran')->nullable();
            $table->string('nama',70);
            $table->string('status_aktif')->nullable();
            $table->string('file_photo')->nullable();
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
