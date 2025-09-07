<?php

include_once 'SpotifySong.php';

class SpotifyData
{
    public array $raw_json_data;
    public array $field_names = [
        'artist' => 'master_metadata_album_artist_name',
        'album' => 'master_metadata_album_album_name',
        'song' => 'master_metadata_track_name',
        'track_id' => 'spotify_track_uri',
        'was_skipped' => 'skipped',
        'completed' => 'reason_end', // This field can contain multiple values, will need filtering
        'time_played' => 'ts'
    ];
    public array $artists;
    public array $albums;
    public array $songs;

    public function __construct()
    {
        ini_set('memory_limit', '-1'); // temporary measure to prevent errors during development

        $this->raw_json_data = SpotifyData::get_raw_json_data();
        $this->artists = [];
        $this->albums = [];
        $this->songs = [];
        $this->parse_raw_json_data();
    }

    /**
     * Reads in all the relevant JSON data from the `/data` directory
     * @return array
     */
    public static function get_raw_json_data(): array{
        $all_files = scandir('data');
        $all_file_contents = [];
        foreach ($all_files as $file) {
            $info = pathinfo($file);
            if ($info['extension'] == 'json' && str_contains($info['filename'], 'Streaming_History') && file_exists('data/' . $file)) {
                $file_contents = json_decode(file_get_contents('data/' . $file), true);
                if (is_array($file_contents)) {
                    $all_file_contents = array_merge($all_file_contents, $file_contents);
                }
            }
        }
        return $all_file_contents;
    }

    /**
     * Parse $this->raw_json_data into instances of SpotifySong/SpotifyAlbum/SpotifyArtist within class variables
     * @return void
     */
    public function parse_raw_json_data(): void{
        set_time_limit(300);

        // Interestingly the song name can differ, we can instead identify unique tracks using `track_id
        $song_names = [];

        // Loop through raw_data to get relevant information for each distinct occurrence of the field
        foreach ($this->raw_json_data as $unique_entry){
            // Determine that this isn't a podcast
            if (!is_null($unique_entry[$this->field_names['track_id']])){
                $artist_name = $unique_entry[$this->field_names['artist']];
                $album_name = $unique_entry[$this->field_names['album']];

                // Create new instance of SpotifyArtist if it doesn't exist already
                if (!in_array($artist_name, array_keys($this->artists))) {
                    $this->artists[$artist_name] = new SpotifyArtist(
                        $artist_name
                    );
                }

                // Create new instance of SpotifyAlbum if it doesn't exist already
                if (!in_array($album_name, array_keys($this->albums))) {
                    $this->albums[$album_name] = new SpotifyAlbum(
                        $album_name, $this->artists[$artist_name]
                    );
                }

                // Check if the song name exists already
                if (!in_array($unique_entry[$this->field_names['track_id']], array_keys($song_names))) {
                    $song_names[$unique_entry[$this->field_names['track_id']]] = $unique_entry[$this->field_names['song']];
                }
                // Get the $song_name using track_id from the current entry from $song_names
                $song_name = $song_names[$unique_entry[$this->field_names['track_id']]];

                // Create new instance of SpotifySong
                if (!in_array($song_name, array_keys($this->songs))) {
                    $this->songs[$song_name] = new SpotifySong(
                        intval($unique_entry[$this->field_names['track_id']]),
                        $song_name,
                        $this->artists[$artist_name]
                    );
                }

                // Increment counts and add to play_history
                $this->songs[$song_name]->inc_times_played();
                $this->artists[$artist_name]->inc_times_played();
                if ($unique_entry[$this->field_names['completed']] == "trackdone" && !$unique_entry[$this->field_names['was_skipped']]){
                    $this->songs[$song_name]->inc_times_played_completely();
                    $this->artists[$artist_name]->inc_times_played_completely();
                }
                else if ($unique_entry[$this->field_names['was_skipped']]){
                    $this->songs[$song_name]->inc_times_skipped();
                    $this->artists[$artist_name]->inc_times_skipped();
                }
                $this->songs[$song_name]->add_to_play_history($unique_entry[$this->field_names['time_played']]);
                $this->artists[$artist_name]->add_to_play_history($unique_entry[$this->field_names['time_played']]);

                // Add songs and albums to SpotifyArtists, just songs to SpotifyAlbums
                $this->artists[$artist_name]->add_song($this->songs[$song_name]);
                $this->artists[$artist_name]->add_album($this->albums[$album_name]);
                $this->albums[$album_name]->add_song($this->songs[$song_name]);

                // Update SpotifyArtists in SpotifySongs and SpotifyAlbums
                $this->songs[$song_name]->update_spotify_artist($this->artists[$artist_name]);
                $this->albums[$album_name]->update_spotify_artist($this->artists[$artist_name]);

            }
        }

        foreach ($this->albums as $album) {
            $album->update_album_stats();
        }

        /*
        echo "<h1>Artist Data</h1>";
        echo "Artist Name: " . $this->artists['Radiohead']->name . "<br>";
        echo "Songs: " . json_encode(array_keys($this->artists['Radiohead']->songs)) . "<br>";
        echo "Albums: " . json_encode(array_keys($this->artists['Radiohead']->albums)) . "<br>";
        echo "Times Played: " . $this->artists['Radiohead']->times_played . "<br>";
        echo "Times Played Completely: " . $this->artists['Radiohead']->times_played_completely . "<br>";
        echo "Times Skipped: " . $this->artists['Radiohead']->times_skipped . "<br>";
        echo "Play History: " . (count($this->artists['Radiohead']->play_history)) . "<br>";


        echo "<h1>Album Data</h1>";
        echo "Album Name: " . $this->albums['OK Computer']->album_name . "<br>";
        echo "<h2>Album Artist Data:</h2>";
        echo "Artist Name: " . $this->albums['OK Computer']->artist->name . "<br>";
        echo "Songs: " . (count($this->albums['OK Computer']->artist->songs)) . "<br>";
        echo "Albums: " . (count($this->albums['OK Computer']->artist->albums)) . "<br>";
        echo "<h2>Album Songs Data:</h2>";
        foreach ($this->albums['OK Computer']->songs as $song){
            echo "Song Name: " . $song->name . "<br>";
            echo "Artist Name: " . $song->artist->name . "<br>";
            echo "Times Played: " . $song->times_played . "<br>";
            echo "Times Played Completely: " . $song->times_played_completely . "<br>";
            echo "Times Skipped: " . $song->times_skipped . "<br>";
            echo "Play History: " .count($song->play_history) . "<br><br>";
        }


        echo "<h1>Song Data</h1>";
        echo "Song Name: " . $this->songs['Let Down']->name . "<br>";
        echo "Artist Name: " . $this->songs['Let Down']->artist->name . "<br>";
        echo "Times Played: " . $this->songs['Let Down']->times_played . "<br>";
        echo "Times Played Completely: " . $this->songs['Let Down']->times_played_completely . "<br>";
        echo "Times Skipped: " . $this->songs['Let Down']->times_skipped . "<br>";
        echo "Play History: " . count($this->songs['Let Down']->play_history) . "<br>";
        */
    }

}