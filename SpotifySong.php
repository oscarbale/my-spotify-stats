<?php

include_once ('SpotifyBaseStats.php');
include_once ('SpotifyArtist.php');

class SpotifySong extends SpotifyBaseStats
{
    public int $track_id;
    public string $name;
    public SpotifyArtist $artist;

    /**
     * Constructs the class using the passed parameters
     * @param int $track_id
     * @param string $name
     * @param SpotifyArtist $artist
     */
    public function __construct(int $track_id, string $name, SpotifyArtist $artist){
        $this->track_id = str_replace("spotify:track:", "", $track_id);
        $this->name = $name;
        $this->artist = $artist;

        // Set all the incrementable variables to 0
        $this->times_played = 0;
        $this->times_played_completely = 0;
        $this->times_skipped = 0;

        // Set $this->play_history to an empty array
        $this->play_history = [];
    }




}