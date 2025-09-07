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

    /**
     * Updates the stats of the album based on the songs it contains, has to be done after all songs have been added
     * and updated
     * @return void
     */
    public function update_album_stats(): void{
        // The album can only be played as many times as the least played song
        $song_times_played = [];
        foreach ($this->songs as $song){
            $song_times_played[$song->name] = $song->times_played;
        }
        $this->times_played = min($song_times_played);
    }

}