<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('menu_groups', function (Blueprint $table) {
            $table->id('menu_group_id');
            $table->string('name');
            $table->tinyInteger('sequence');
            $table->string('icon')->nullable();
            $table->string('platform')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_groups');
    }
};
