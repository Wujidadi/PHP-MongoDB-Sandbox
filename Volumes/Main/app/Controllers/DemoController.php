<?php

namespace App\Controllers;

use App\Controller;
use App\Handlers\Demo;

/**
 * Demo controller.
 */
class DemoController extends Controller
{
    /**
     * Unique instance of this class.
     *
     * @var self|null
     */
    protected static $_uniqueInstance = null;

    /**
     * Get the unique instance of this class.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$_uniqueInstance == null) self::$_uniqueInstance = new self();
        return self::$_uniqueInstance;
    }

    /**
     * Constructor.
     *
     * @return void
     */
    protected function __construct()
    {
        $this->_className = basename(__FILE__, '.php');
    }

    /**
     * Main demo method.
     *
     * @return void
     */
    public function main(): void
    {
        echo Demo::getInstance()->welcome('Tarascanta Welcome Page');
    }

    /**
     * Main demo API method.
     *
     * @return void
     */
    public function api(): void
    {
        header('Content-Type: text/plain');

        echo 'API';
    }

    /**
     * Return message to the demo command line script under the "`cli`" directory.
     *
     * @return string
     */
    public function cmd(): string
    {
        return 'Demo command line';
    }
}
