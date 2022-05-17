<?php

namespace App\Handlers;

use App\Handler;

/**
 * Demo handler.
 */
class Demo extends Handler
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
     * Output welcome message.
     *
     * @param  string  $message  String to form the welcom message
     * @return void
     */
    public function welcome(string $message): void
    {
        echo 'Welcome to ' . $message;
    }
}
