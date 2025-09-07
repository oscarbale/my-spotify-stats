<?php

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

}