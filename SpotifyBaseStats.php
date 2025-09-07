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
     * Updates $this->artist with the passed SpotifyArtist instance if $this is a SpotifySong or SpotifyAlbum
     * @param SpotifyArtist $artist
     * @return void
     */
    public function update_spotify_artist(SpotifyArtist $artist): void{
        if ($this instanceof SpotifySong || $this instanceof SpotifyAlbum) {
            $this->artist = $artist;
        }
    }

    /**
     * Updates $this->albums with the passed SpotifyAlbum instance if $this is a SpotifyArtist
     * @param SpotifyAlbum $album
     * @return void
     */
    public function update_spotify_album(SpotifyAlbum $album): void{
        if ($this instanceof SpotifyArtist) {
            $this->albums[$album->album_name] = $album;
        }
    }

    /**
     * Updates $this->songs with the passed SpotifySong instance if $this is a SpotifyArtist or SpotifyAlbum
     * @param SpotifySong $song
     * @return void
     */
    public function update_spotify_song(SpotifySong $song): void{
        if ($this instanceof SpotifyArtist || $this instanceof SpotifyAlbum) {
            $this->songs[$song->name] = $song;
        }
    }


}