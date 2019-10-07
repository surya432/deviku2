<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMirrorkeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('mirrorkeys');
        Schema::create('mirrorkeys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('keys')->nullable();
            $table->integer('master_mirror_id')->unsigned();
            $table->integer('cmp_id')->unsigned();
            $table->timestamps();
            // $table->foreign('master_mirror_id')->references('id')->on('master_mirrors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mirrorkeys');
    }
}
