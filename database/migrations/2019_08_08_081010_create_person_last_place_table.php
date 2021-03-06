<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonLastPlaceTable extends Migration
{
    public function up()
    {
        Schema::create('person_last_place', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('person_id')->unsigned();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->string('address', 255);
            $table->tinyInteger('exact_address');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('person_last_place');
    }
}
