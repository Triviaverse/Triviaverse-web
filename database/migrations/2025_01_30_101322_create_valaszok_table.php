<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValaszokTable extends Migration
{
    public function up()
    {
        Schema::create('valaszok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerdes_id')->constrained('kerdesek')->onDelete('cascade');
            $table->text('valasz');
            $table->boolean('helyes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('valaszok');
    }
};

