<?php

use App\Controllers\DemoController;
use App\Controllers\MongoDemoController;

$Route->map('GET', '/', function() {
    view('Demo.Home', [
        'title' => PROJECT_NAME,
        'mainMessage' => PROJECT_NAME
    ]);
});

$Route->map('GET', '/demo', [DemoController::getInstance(), 'main']);

$Route->map('GET', '/mongo/demo/count', function() {
    MongoDemoController::getInstance()->count();
});

$Route->map('GET', '/mongo/demo/insert', function() {
    MongoDemoController::getInstance()->insert();
});

$Route->map('GET', '/mongo/demo/select', function() {
    MongoDemoController::getInstance()->select();
});

$Route->map('GET', '/mongo/demo/update', function() {
    MongoDemoController::getInstance()->update();
});

$Route->map('GET', '/mongo/demo/delete', function() {
    MongoDemoController::getInstance()->delete();
});
