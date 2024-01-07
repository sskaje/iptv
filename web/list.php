<?php

include __DIR__ . '/include/functions.php';

$result = get_yaml_lists();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($result);
