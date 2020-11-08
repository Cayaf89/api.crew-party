<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEventChoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_event_choice', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('event_choice_id')
                  ->constrained('event_choice')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_event_choice');
    }
}
