<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTventeLocalisationProduitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tvente_localisation_produit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refProduit')->constrained('tvente_produit')->restrictOnUpdate()->restrictOnDelete();
            $table->double('latitude');
            $table->double('longitude');
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
        Schema::dropIfExists('tvente_localisation_produit');
    }
}
