<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWagersTable extends Migration
{
    public $withinTransaction = false;

    public function up()
    {
        Schema::create('wagers', function (Blueprint $table) {
            $table->id();
            $table->integer('pot')->default(0);
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wagers');
    }
}
