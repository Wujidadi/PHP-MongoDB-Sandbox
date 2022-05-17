<?php

namespace Database\Migrations\Triggers;

use Libraries\Logger;
use Database\Migration;

/**
 * Migration class of the trigger `auto_update_time` on table `DemoTable`.
 */
class AutoUpdateTimeOnDemoTable extends Migration
{
    /**
     * Name of the target trigger.
     *
     * @var string
     */
    protected $_triggerName = 'auto_update_time';

    /**
     * Name of the target table.
     *
     * @var string
     */
    protected $_tableName = 'DemoTable';

    /**
     * Name of the trigger function our trigger calls.
     *
     * @var string
     */
    protected $_triggerFunctionName = 'update_timestamp';

    /**
     * Unique instance of this class.
     *
     * @var self|null
     */
    protected static $_uniqueInstance = null;

    /** @return self */
    public static function getInstance(): self
    {
        if (self::$_uniqueInstance == null) self::$_uniqueInstance = new self();
        return self::$_uniqueInstance;
    }

    /**
     * Constructor.
     *
     * Override the constructor of parent `Migration` class to use different DB configurations.
     */
    protected function __construct()
    {
        parent::__construct('DEFAULT');
        $this->_className = basename(__FILE__, '.php');
    }

    /**
     * Create the trigger.
     *
     * @return boolean
     */
    public function up(): bool
    {
        $sqlArray = [

            <<<SQL
            CREATE TRIGGER "{$this->_triggerName}"
                BEFORE UPDATE
                ON public."{$this->_tableName}"
                FOR EACH ROW
                EXECUTE FUNCTION public.{$this->_triggerFunctionName}();
            SQL

        ];

        if ($runResult = $this->_run($this->_className, __FUNCTION__, $sqlArray))
        {
            Logger::getInstance()->logInfo("Trigger \"{$this->_triggerName}\" created");
        }

        return $runResult;
    }

    /**
     * Drop the trigger.
     *
     * @return boolean
     */
    public function down(): bool
    {
        $sqlArray = [

            "DROP TRIGGER IF EXISTS \"{$this->_triggerName}\" on public.\"{$this->_tableName}\""

        ];

        if ($runResult = $this->_run($this->_className, __FUNCTION__, $sqlArray))
        {
            Logger::getInstance()->logInfo("Trigger \"{$this->_triggerName}\" dropped");
        }

        return $runResult;
    }
}
