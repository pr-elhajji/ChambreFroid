<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->float('capacite-max')->nullable();
            $table->float('Quantite');
            $table->dateTime('date-entree')->nullable();
            $table->dateTime('date-sortie')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreignId('chambre_id');
            $table->foreign('chambre_id')->references('id')->on('machines');
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
        Schema::dropIfExists('clients');
    }
}
