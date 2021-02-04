<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('image', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type');
            $table->bigInteger('owner_id');
            $table->string('url')->nullable();
            $table->timestamps();
        });
        $crews = \App\Models\Crew::all();
        foreach ($crews as $crew) {
            \App\Models\Image::create([
                                          'owner_type' => \App\Models\Crew::class,
                                          'owner_id'   => $crew->id,
                                          'url'        => 'http://crew-party.test' . $crew->getLogo(),
                                      ]);
        }
        $events = \App\Models\Event::all();
        foreach ($events as $event) {
            \App\Models\Image::create([
                                          'owner_type' => \App\Models\Event::class,
                                          'owner_id'   => $event->id,
                                          'url'        => 'http://crew-party.test' . $event->getCover(),
                                      ]);
        }
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            \App\Models\Image::create([
                                          'owner_type' => \App\Models\User::class,
                                          'owner_id'   => $user->id,
                                          'url'        => 'http://crew-party.test' . $user->getLogo(),
                                      ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('logo');
        });

        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn('cover');
        });

        Schema::table('crew', function (Blueprint $table) {
            $table->dropColumn('logo');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('image');

        Schema::table('users', function (Blueprint $table) {
            $table->string('logo')->default('images/default-logo.png');
        });

        Schema::table('event', function (Blueprint $table) {
            $table->string('cover')->default('images/default-logo.png');
        });

        Schema::table('crew', function (Blueprint $table) {
            $table->string('logo')->default('images/default-logo.png');
        });
    }
}
