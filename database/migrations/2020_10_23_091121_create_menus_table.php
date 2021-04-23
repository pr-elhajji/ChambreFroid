<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->id('id');
                $table->string('icon')->nullable();
                $table->string('title');
                $table->string('link')->nullable();
                $table->unsignedBigInteger('menu_category_id');
                $table->unsignedBigInteger('parent_menu_id');
                $table->foreign('menu_category_id')->references('id')->on('menu_categories');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
