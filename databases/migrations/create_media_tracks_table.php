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
        'CREATE TABLE media_tracks (
            `id` INT NOT NULL INCREMENT ,
            `artist_id` INT NOT NULL,
            `name` TEXT NULL , 
            `created_at` TEXT NULL , 
            `genre` INT NULL , 
            `display_date` TEXT NULL , 
            `state` INT NOT NULL , 
            `track_format` TEXT NULL , 
            `uri` TEXT NULL , 
            FOREIGN KEY (artist_id)  REFERENCES media_artists (id)';

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        'DROP TABLE media_tracks';
    }
}
