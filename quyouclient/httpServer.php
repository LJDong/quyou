<?php
$http = new swoole_http_server("0.0.0.0", 9501);
$http->on('request', function ($request, $response) {
    print_r($request);
});
$http->start();
