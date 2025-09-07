<?php

include_once ('SpotifyBaseStats.php');

class SpotifySong extends SpotifyBaseStats
{
    private int $track_id {
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


    /**
     * Updates counts of the current instance of SpotifySong to match $song, intended use is if the same song is added to SpotifyAlbum or SpotifyArtist the state can be updated in a single function call rather than with individual calls to inc functions (function won't do anything if $song->track_id doesn't match $this
     * @param SpotifySong $song
     * @return void
     */
    public function update_state(SpotifySong $song): void{
        if ($this->track_id == $song->track_id){
            $this->times_played = $song->times_played;
            $this->times_played_completely = $song->times_played_completely;
            $this->times_skipped = $song->times_skipped;
            $this->play_history = $song->play_history;
        }
    }

}