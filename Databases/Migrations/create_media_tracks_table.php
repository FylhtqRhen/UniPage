<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('media_artist_id');
            $table->foreign('media_artist_id')->references('id')->on('media_artists');
            $table->string('name');
            $table->string('description');
            $table->string('album');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_tracks');
    }
}
