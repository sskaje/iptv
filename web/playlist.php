<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IPTV 播放列表</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Clipboard.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary p-4">
            <a href="/">URL 生成器</a>

            <h6>播放列表</h6>
            <ul>
        <?php

        include __DIR__ . '/include/functions.php';

        $result = get_yaml_lists();

        foreach ($result as $entry) {
            echo '<li><a href="/playlist.php?f='.$entry['name'].'">'.$entry['playlist'].'</a></li>';
        }

        ?>
            </ul>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <?php
            $file = __DIR__ . '/../yaml/' . $_GET['f'] . '.yaml';
            $comment = '';
            if (!is_file($file)) {
                $content = "# FILE NOT FOUND\n";
            } else {
                $yaml = yaml_parse_file($file);
                $comment = $yaml['comment'] ?? '';
                $content = "#EXTM3U\n";
                foreach ($yaml['channels'] as $channel) {
                    $content .= "#EXTINF:-1," . $channel['title'] . "\n" . $channel['uri'] . "\n";
                }
            }
            ?>

            <h1 class="mb-4">IPTV 播放列表</h1>

            <small class="text-muted" id="playlist-comment"><?=$comment?></small>
            <div class="mt-4">
                <pre id="playlistBox" class="border p-3"><?=$content?></pre>
            </div>

            <small class="text-muted">添加或者维护列表，请访问 <a target="_blank" href="https://github.com/sskaje/iptv">github</a></small>

        </main>
    </div>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Your custom script -->
<script>
    // Your JavaScript code here
    $.get('/list.php').then(function(data) {
        for (var i = 0; i < data.length; i++) {
            $('#iptvList').append(
                $('<option>').attr('value', data[i]['name']).text(data[i]['playlist'])
            )
        }
    });

    // Example function for preview button
    function previewIptv() {
        $('#previewBox').text('');

        var filename = $('#iptvList').val();
        var proxyUrl = $('#proxyInput').val();
        var filterInclude = $('#filterInclude').val();
        var filterExclude = $('#filterExclude').val();

        // Replace this with your actual logic to generate API URL and fetch content
        var apiUrl = 'https://' + window.location.host + "/m3u.php?f=" + encodeURIComponent(filename);
        if (proxyUrl) apiUrl += '&p=' + encodeURIComponent(proxyUrl);
        if (filterInclude) apiUrl += '&incl=' + encodeURIComponent(filterInclude);
        if (filterExclude) apiUrl += '&excl=' + encodeURIComponent(filterExclude);

        document.getElementById("previewUrl").value = apiUrl;

        $.get(apiUrl).then(function(data) {
            $('#previewBox').text(data);
        });
    }

    // Initialize Clipboard.js
    var clipboard = new ClipboardJS('#copyButton');
</script>

</body>
</html>
