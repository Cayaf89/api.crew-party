<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->longText('description');
            $table->string('cover')->default('/storage/images/default-cover-event.png');
            $table->foreignId('crew_id')
                  ->constrained('crew')
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
        Schema::dropIfExists('event');
    }
}
