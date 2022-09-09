<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('privileges', function (Blueprint $table) {
            $table->id('privilege_id');
            $table->unsignedBigInteger('role_id')->index();
            $table->unsignedBigInteger('menu_item_id')->index();
            $table->tinyInteger('view');
            $table->tinyInteger('add');
            $table->tinyInteger('edit');
            $table->tinyInteger('delete');
            $table->tinyInteger('other');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('priviledges');
    }
};
