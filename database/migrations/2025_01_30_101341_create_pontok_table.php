<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePontokTable extends Migration
{
    public function up()
    {
        Schema::create('pontok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('targy')->constrained('targyak')->onDelete('cascade');
            $table->integer('osszpont');
            $table->string('email');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('pontok');
    }
};
