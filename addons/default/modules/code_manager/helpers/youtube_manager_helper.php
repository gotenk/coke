<?php defined('BASEPATH') OR exit('No direct script access allowed');

function get_youtube_video_id ($url) {
    $vidid = false;
    $valid_schemes = array ('http', 'https');
    $valid_hosts = array ('www.youtube.com', 'youtube.com');
    $valid_paths = array ('/watch');

    $bits = parse_url ($url);
    if (! is_array ($bits)) {
        return false;
    }
    if (! (array_key_exists ('scheme', $bits)
            and array_key_exists ('host', $bits)
            and array_key_exists ('path', $bits)
            and array_key_exists ('query', $bits))) {
        return false;
    }
    if (! in_array ($bits['scheme'], $valid_schemes)) {
        return false;
    }
    if (! in_array ($bits['host'], $valid_hosts)) {
        return false;
    }
    if (! in_array ($bits['path'], $valid_paths)) {
        return false;
    }
    $querypairs = explode ('&', $bits['query']);
    if (count ($querypairs) < 1) {
        return false;
    }
    foreach ($querypairs as $querypair) {
        list ($key, $value) = explode ('=', $querypair);
        if ($key == 'v') {
            if (preg_match ('/^[a-zA-Z0-9\-_]+$/', $value)) {
                # Set the return value
                $vidid = $value;
            }
        }
    }

    return $vidid;
}