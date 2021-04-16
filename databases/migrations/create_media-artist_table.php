<?php

class CreateMediaArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        'CREATE TABLE `media_artists` (
            `avatar_url` TEXT NULL , 
            `first_name` TEXT NULL , 
            `followers_count` INT NULL , 
            `full_name` TEXT NULL , 
            `id` INT NOT NULL , 
            `kind` TEXT NULL , 
            `last_modified` TEXT NULL , 
            `last_name` TEXT NULL , 
            `permalink` TEXT NOT NULL , 
            `permalink_url` TEXT NULL , 
            `uri` TEXT NULL , 
            `urn` TEXT NULL , 
            `username` TEXT NOT NULL , 
            `verified` BOOLEAN NULL , 
            `city` TEXT NULL , 
            `country_code` INT NULL , 
            `badges` TEXT NULL ,
            PRIMARY KEY (`id`(11)))';
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        'DROP TABLE media_artists';
    }
}
