<?php

chdir(__DIR__);
require_once '../bootstrap/cli.php';

/*
|--------------------------------------------------------------------------
| Demo Command
|--------------------------------------------------------------------------
|
| A demo command line script.
|
*/

use App\Controllers\DemoController;

try
{
    $strResult = DemoController::getInstance()->cmd();
    echo "\033[33;1m{$strResult}\033[0m\n";
}
catch (Throwable $th)
{
    $strErrorMessage = "{$th->getMessage()} ({$th->getCode()})";
    echo "\033[31;1m{$strErrorMessage}\033[0m\n";
    exit(1);
}

exit(0);
