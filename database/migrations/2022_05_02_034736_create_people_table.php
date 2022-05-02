<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
		    $table->string('firstname', 64);
		    $table->string('surname', 64);
		    $table->string('gender', 8)->nullable();
		    $table->string('profile_pic', 64)->nullable();
		    $table->string('bio', 512)->nullable();
		    $table->integer('account_id')->unsigned();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people');
    }
};
