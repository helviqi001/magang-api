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
            $table->string('gambar_wisata')->nullable();
            $table->string('name_wisata');
            $table->string('deskripsi')->nullable();
            $table->integer('harga_dewasa');
            $table->integer('harga_anak');
            $table->string('fasilitas')->nullable();
            $table->string('operasional')->nullable();
            $table->string('lokasi');
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
