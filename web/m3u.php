<?php

$filename = $_GET['f'] ?? '';

if (!preg_match('#^[a-z0-9_\-]+$#i', $filename)) {
    echo "# FILE NOT FOUND\n";
    exit;
}

$file = __DIR__ . '/../yaml/' . $filename . '.yaml';
if (!is_file($file)) {
    echo "# FILE NOT FOUND\n";
    exit;
}

$yaml = yaml_parse_file($file);

$proxy = trim($_GET['p'] ?? '');

if ($proxy && !preg_match('#^https?://#', $proxy)) {
    $proxy = '';
}
if ($proxy) {
    $proxy = rtrim($proxy, '/');
}

function process_uri($uri)
{
    global $proxy;
    if (empty($proxy)) {
        return $uri;
    }

    if (strpos($uri, 'rtp://') === 0) {
        return $proxy . '/rtp/' . substr($uri, strlen('rtp://'));
    } else if (strpos($uri, 'udp://') === 0) {
        return $proxy . '/udp/' . substr($uri, strlen('udp://'));
    }

    return $uri;
}

header('Content-Type: application/vnd.apple.mpegurl');

echo "#EXTM3U\n";
foreach ($yaml['channels'] as $channel) {
    if (!empty($_GET['incl'])) {
        $incl_regexp = trim($_GET['incl']);
        $included = preg_match($incl_regexp, $channel['title']) || preg_match($incl_regexp, $channel['title']);
        if (!$included) {
            continue;
        }
    }

    if (!empty($_GET['excl'])) {
        $excl_regexp = trim($_GET['excl']);
        $excluded = preg_match($excl_regexp, $channel['title']) || preg_match($excl_regexp, $channel['title']);
        if ($excluded) {
            continue;
        }
    }

    echo "#EXTINF:-1," . $channel['title'] . "\n" . process_uri($channel['uri']) . "\n";
}

