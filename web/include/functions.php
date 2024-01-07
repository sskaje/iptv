<?php

function get_yaml_lists()
{
    $files = glob(__DIR__ . '/../../yaml/*.yaml');

    $result = [];
    foreach ($files as $file) {
        $name = basename($file, '.yaml');
        $yaml = yaml_parse_file($file);

        $result[] = [
            'name' => $name,
            'playlist' => $yaml['name'],
        ];
    }

    return $result;
}
