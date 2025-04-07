<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpersoParamettreSecteurTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tperso_paramettre_secteur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refCooperative')->constrained('tperso_cooperative_minerais')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('refSecteur')->constrained('tperso_secteur_minerais')->restrictOnUpdate()->restrictOnDelete();
            $table->string('active_param',250)->default('OUI');
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
        Schema::dropIfExists('tperso_paramettre_secteur');
    }
}
