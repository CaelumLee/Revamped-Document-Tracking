<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_number');
            $table->integer('creator')->unsigned();
            $table->integer('is_rush')->default(0);
            $table->string('iso_code')->nullable();
            $table->integer('confidentiality');
            $table->string('complexity', 8);
            $table->string('sender_name');
            $table->string('sender_address');
            $table->integer('type_of_docu_id')->unsigned();
            $table->integer('progress')->unsigned();
            $table->timestamp('final_action_date');
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
        Schema::dropIfExists('docus');
    }
}
