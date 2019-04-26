<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DateTimeApi extends Migration
{
    /**
     * Run the migrations.s
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_time_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ACNo'); //Added "5"
            $table->string('name'); //Legendary MG
            $table->string('email')->unique; //Added legendarymg@mkg-wazzap-man.com
            $table->string('apiKey'); //Added "12312fsfwer324"
            $table->timestamp('datetime')->nullable(); //Added 2019-04-05 08:28:00
            $table->string('state'); //Added - "C/In or C/Out"
            $table->string('deviceID'); //Added - "123456789"
            $table->string('status'); //Added - "saved"
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_time_records');
    }
}
