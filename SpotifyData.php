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
}