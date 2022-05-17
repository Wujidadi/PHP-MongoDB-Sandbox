<?php

namespace App\Models;

use App\Model;

/**
 * Demo model.
 */
class DemoModel extends Model
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
     * Can be commented if the used DB configurations are the same as parent `Model` class (`DEFAULT` by default).
     */
    protected function __construct()
    {
        parent::__construct('DEFAULT');
        $this->_className = basename(__FILE__, '.php');
    }

    /**
     * A demo method.
     *
     * @return string
     */
    public function demo(): string
    {
        return 'Connected to DB';
    }
}
