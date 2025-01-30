<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargyakTable extends Migration
{
    public function up()
    {
        Schema::create('targyak', function (Blueprint $table) {
            $table->id();
            $table->string('targyneve');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('targyak');
    }
};

