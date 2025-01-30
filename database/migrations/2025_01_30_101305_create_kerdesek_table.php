<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKerdesekTable extends Migration
{
    public function up()
    {
        Schema::create('kerdesek', function (Blueprint $table) {
            $table->id();
            $table->text('kerdes');
            $table->foreignId('targy')->constrained('targyak')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('kerdesek');
    }
};
