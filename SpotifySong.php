<?php

include_once ('SpotifyBaseStats.php');

class SpotifySong extends SpotifyBaseStats
{
    public int $track_id {
        get {
            return $this->track_id;
        }
    }
    public string $name;
    public string $artist;

    /**
     * Constructs the class using the passed parameters
     * @param int $track_id
     * @param string $name
     * @param string $artist
     */
    public function __construct(int $track_id, string $name, string $artist){
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