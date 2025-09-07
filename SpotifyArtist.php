<?php

include_once "SpotifySong.php";
include_once "SpotifyAlbum.php";
include_once "SpotifyBaseStats.php";

class SpotifyArtist extends SpotifyBaseStats
{
    public string $name;
    public array $songs;
    public array $albums;

    /**
     * Constructs the class using the passed parameters
     * @param string $name
     */
    public function __construct(string $name){
        $this->name = $name;

        $this->songs = [];
        $this->albums = [];

        $this->times_played = 0;
        $this->times_played_completely = 0;
        $this->times_skipped = 0;
        $this->play_history = [];
    }

    /**
     * Add SpotifySong to $this->songs, updates the increment counts if already set
     * @param \SpotifySong $song
     * @return void
     */
    public function add_song(SpotifySong $song): void
    {
        $song_name = $song->name;
        if (!in_array($song_name, array_keys($this->songs))){
            $this->songs[$song_name] = $song;
        }
        else{
            $this->songs[$song_name]->update_state($song);
        }

    }
}