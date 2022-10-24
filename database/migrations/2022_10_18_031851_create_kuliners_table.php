<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKulinersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kuliners', function (Blueprint $table) {
            $table->id('kuliner_id');
            $table->string('gambar_kuliner')->nullable();
            $table->string('name_kuliner');
            $table->string('deskripsi')->nullable();
            $table->integer('harga_reguler');
            $table->integer('harga_jumbo');
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
        Schema::dropIfExists('kuliners');
    }
}
