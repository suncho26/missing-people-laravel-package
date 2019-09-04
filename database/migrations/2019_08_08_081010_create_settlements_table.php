<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlementsTable extends Migration
{
    public function up()
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('place_id')->unsigned();
            $table->integer('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade')->onUpdate('cascade');
            $table->string('code', 50);
            $table->integer('ekatte')->unsigned();
            $table->string('name', 255);
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->integer('people')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('settlements');
    }
}
