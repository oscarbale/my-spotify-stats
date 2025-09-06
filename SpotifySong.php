<?php

class SpotifySong
{
    private int $track_id {
        get {
            return $this->track_id;
        }
    }
    public string $name;
    public string $artist;
    public int $times_played;
    public int $times_played_completely;
    public int $times_skipped;
    public array $play_history;

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
     * Increments $times_played by 1
     * @return void
     */
    public function inc_times_played() : void{
        $this->times_played++;
    }

    /**
     * Increments $times_played_completely by 1
     * @return void
     */
    public function inc_times_played_completely() : void{
        $this->times_played_completely++;
    }

    /**
     * Increments $times_skipped by 1
     * @return void
     */
    public function inc_times_skipped() : void{
        $this->times_skipped++;
    }

    /**
     * Updates the $play_history with information passed from $ts_string
     * @param string $ts_string
     * @return void
     */
    public function add_to_play_history(string $ts_string): void{
        // Correctly formats $ts_string, [0] will be the date [1] will be the time
        $ts_string = explode("T", $ts_string);
        $this->play_history[] = [
            'date' => $ts_string[0],
            'time' => $ts_string[1],
        ];
    }

    /**
     * Updates counts of the current instance of SpotifySong to match $song, intended use is if the same song is added
     * to SpotifyAlbum or SpotifyArtist the state can be updated in a single function call rather than with individual
     * calls to inc functions
     * @param SpotifySong $song
     * @return void
     */
    public function update_state(SpotifySong $song): void{
        $this->times_played = $song->times_played;
        $this->times_played_completely = $song->times_played_completely;
        $this->times_skipped = $song->times_skipped;
        $this->play_history = $song->play_history;
    }

}