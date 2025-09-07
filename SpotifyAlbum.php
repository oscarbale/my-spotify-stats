<?php

include_once ('SpotifyBaseStats.php');
include_once ('SpotifyArtist.php');
include_once ('SpotifySong.php');

class SpotifyAlbum extends SpotifyBaseStats
{
    public string $album_name;
    public SpotifyArtist $artist;
    public array $songs;

    public function __construct(string $album_name, SpotifyArtist $artist){
        $this-> album_name = $album_name;
        $this-> artist = $artist;
        $this-> songs = array();

        // Set all the incrementable variables to 0
        $this->times_played = 0;
        $this->times_played_completely = 0;
        $this->times_skipped = 0;

        // Set $this->play_history to an empty array
        $this->play_history = [];
    }

    /**
     * Add SpotifySong to $this->songs array
     * @param SpotifySong $song
     * @return void
     */
    public function add_song(SpotifySong $song): void{
        if (!in_array($song->name, array_keys($this->songs))){
            $this->songs[$song->name] = $song;
        }
        else{
            $this->update_spotify_song($song);
        }
    }

}