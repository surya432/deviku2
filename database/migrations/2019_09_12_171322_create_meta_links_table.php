<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('meta_links');
        Schema::create('meta_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kualitas')->nullable();
            $table->string('link')->nullable();
            $table->string('status')->nullable();
            $table->string('content_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_links');
    }
}
