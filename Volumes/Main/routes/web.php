<?php

use App\Controllers\DemoController;

$Route->map('GET', '/', function() {
    view('Demo.Home', [
        'title' => PROJECT_NAME,
        'mainMessage' => PROJECT_NAME
    ]);
});

// $Route->map('GET', '/demo', [DemoController::getInstance(), 'main']);

$Route->map('GET', '/demo', function() {
    view('Demo.Demo', [
        'title' => 'Demo - ' . PROJECT_NAME,
        'output' => 'This is a demo message.'
    ]);
});
