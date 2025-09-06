<?php

class SpotifyData
{
    public array $raw_json_data;

    public function __construct()
    {
        ini_set('memory_limit', '-1'); // temporary measure to prevent errors during development
        $all_files = scandir('data');
        $all_file_contents = [];
        foreach ($all_files as $file) {
            $info = pathinfo($file);
            if ($info['extension'] == 'json' && str_contains($info['filename'], 'Streaming_History') && file_exists('data/' . $file)) {
                $file_contents = json_decode(file_get_contents('data/' . $file));
                if (is_array($file_contents)) {
                    $all_file_contents = array_merge($all_file_contents, $file_contents);
                }
            }
        }
        $this->raw_json_data = $all_file_contents;
    }
}