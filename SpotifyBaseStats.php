<?php

include_once('SpotifySong.php');
include_once('SpotifyArtist.php');
include_once('SpotifyAlbum.php');


class SpotifyBaseStats
{
    public int $times_played;
    public int $times_played_completely;
    public int $times_skipped;
    public array $play_history;

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
     * Updates counts of the current instance to match $obj, if it's SpotifySong then the `track_id` will be compared
     * @param SpotifyArtist|SpotifyAlbum|SpotifySong $obj
     * @return void
     */
    public function update_state(SpotifyArtist|SpotifyAlbum|SpotifySong $obj): void{
        $current_class = get_class($this);
        if ($obj instanceof $current_class) {
            if (($current_class == "SpotifySong" && $this->track_id == $obj->track_id) || ($current_class == "SpotifyAlbum" || $current_class == "SpotifyArtist")) {
                $this->times_played = $obj->times_played;
                $this->times_played_completely = $obj->times_played_completely;
                $this->times_skipped = $obj->times_skipped;
                $this->play_history = $obj->play_history;
            }
        }
    }

}