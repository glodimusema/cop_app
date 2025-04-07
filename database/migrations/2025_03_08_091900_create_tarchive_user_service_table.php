<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarchiveUserServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarchive_user_service', function (Blueprint $table) {
            $table->id();
            $table->Integer('refUser');
            $table->Integer('refService');
            // $table->foreignId('refUser')->constrained('users')->restrictOnUpdate()->restrictOnDelete();
            // $table->foreignId('refService')->constrained('tperso_service_archivage')->restrictOnUpdate()->restrictOnDelete();
            $table->string('active')->default('OUI');
            $table->string('author',100);
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
        Schema::dropIfExists('tarchive_user_service');
    }
}
