<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpersoCooperativeMineraisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tperso_cooperative_minerais', function (Blueprint $table) {
            $table->id();
            $table->string('nom_coop',100);
            $table->string('responsable_coop',100);
            $table->string('contact_respo_coop',250);
            $table->string('description_coop',250);
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
        Schema::dropIfExists('tperso_cooperative_minerais');
    }
}
