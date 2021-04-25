<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailslots', function (Blueprint $table) {
            $table->float('quantite')->nullable();
            $table->foreignId('lot_id');
            $table->foreign('lot_id')->references('id')->on('lots');

            $table->foreignId('variete_id');
            $table->foreign('variete_id')->references('id')->on('varietes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machines');
    }
}
