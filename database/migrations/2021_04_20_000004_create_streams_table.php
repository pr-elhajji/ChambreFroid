<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->id();
            $table->float('temperature');
            $table->float('humedite');
            $table->integer('etat_porte'); // Trois valeur -1 (defaut), 0(arrêt), 1 marche
            $table->integer('etat_moteur');
            $table->integer('etat_ventilateur');
            $table->string('carte_code');
            $table->foreign('carte_code')->references('code')->on('cartes');
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
        Schema::dropIfExists('cartes');
    }
}
