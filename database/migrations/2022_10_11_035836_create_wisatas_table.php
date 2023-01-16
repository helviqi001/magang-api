<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWisatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wisatas', function (Blueprint $table) {
            $table->id('wisata_id');
            $table->string('gambar_wisata');
            $table->string('name_wisata');
            $table->string('deskripsi');
            $table->integer('harga_dewasa');
            $table->integer('harga_anak');
            $table->string('fasilitas');
            $table->string('operasional');
            $table->string('lokasi');
            $table->double('latitude');
            $table->double('longitude');
            $table->softDeletes();
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
        Schema::dropIfExists('wisatas');
    }
}
