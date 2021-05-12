
CREATE TABLE `media_artists` (
                                 `avatar_url` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `first_name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `followers_count` int(11) NOT NULL,
                                 `full_name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `id` int(11) NOT NULL,
                                 `kind` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `last_modified` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `last_name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `permalink` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `permalink_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `uri` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `urn` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `username` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `verified` int(11) DEFAULT NULL,
                                 `city` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `country_code` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                 `station_permalink` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `media_tracks` (
                                `id` int(11) NOT NULL,
                                `artist_id` int(11) NOT NULL,
                                `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                `created_at` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `genre` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `display_date` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `track_format` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `uri` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `media_artists`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `media_tracks`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `media_tracks`
    MODIFY `id` int(11);

ALTER TABLE media_tracks
    ADD CONSTRAINT track_id_artist_id
        FOREIGN KEY (`artist_id`)
            REFERENCES media_artists (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE;

COMMIT;
