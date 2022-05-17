<?php

namespace Database\Migrations\Tables;

use Libraries\Logger;
use Database\Migration;

/**
 * Migration class of the table `ExampleTable`.
 */
class ExampleTable extends Migration
{
    /**
     * Name of the target table.
     *
     * @var string
     */
    protected $_tableName = 'ExampleTable';

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
     * Create the table.
     *
     * @return boolean
     */
    public function up(): bool
    {
        $sqlArray = [

            <<<SQL
            CREATE TABLE IF EXISTS public."{$this->_tableName}"
            (
                "ID"         bigserial                                             NOT NULL,
                "Content"    character varying(800)  COLLATE pg_catalog."C.UTF-8"  NOT NULL,
                "CreatedAt"  timestamp(6) with time zone                           NOT NULL  DEFAULT CURRENT_TIMESTAMP,
                "UpdatedAt"  timestamp(6) with time zone                           NOT NULL  DEFAULT CURRENT_TIMESTAMP,

                CONSTRAINT "{$this->_tableName}_ID" UNIQUE ("ID"),

                PRIMARY KEY ("ID")
            )
            TABLESPACE pg_default
            SQL,

            "COMMENT ON TABLE public.\"{$this->_tableName}\" IS '範例資料表'",

            "ALTER TABLE public.\"{$this->_tableName}\" OWNER to root",

            "COMMENT ON COLUMN public.\"{$this->_tableName}\".\"ID\"        IS '流水號（唯一值，主鍵）'",

            "COMMENT ON COLUMN public.\"{$this->_tableName}\".\"Content\"   IS '內容'",

            "COMMENT ON COLUMN public.\"{$this->_tableName}\".\"CreatedAt\" IS '建立時間'",

            "COMMENT ON COLUMN public.\"{$this->_tableName}\".\"UpdatedAt\" IS '更新時間'"

        ];

        if ($runResult = $this->_run($this->_className, __FUNCTION__, $sqlArray))
        {
            Logger::getInstance()->logInfo("Table \"{$this->_tableName}\" created");
        }

        return $runResult;
    }

    /**
     * Change name of the column of created time.
     *
     * @return boolean
     */
    public function changeNameOfCreatedTime(): bool
    {
        $sqlArray = [

            "ALTER TABLE public.\"{$this->_tableName}\" RENAME \"CreatedAt\" TO \"CreatedTime\""

        ];

        if ($runResult = $this->_run($this->_className, __FUNCTION__, $sqlArray))
        {
            Logger::getInstance()->logInfo("Column \"{$this->_tableName}\".\"CreatedAt\" renamed to \"{$this->_tableName}\".\"CreatedTime\"");
        }

        return $runResult;
    }

    /**
     * Change name of the column of updated time.
     *
     * @return boolean
     */
    public function changeNameOfUpdatedTime(): bool
    {
        $sqlArray = [

            "ALTER TABLE public.\"{$this->_tableName}\" RENAME \"UpdatedAt\" TO \"UpdatedTime\""

        ];

        if ($runResult = $this->_run($this->_className, __FUNCTION__, $sqlArray))
        {
            Logger::getInstance()->logInfo("Column \"{$this->_tableName}\".\"UpdatedAt\" renamed to \"{$this->_tableName}\".\"UpdatedTime\"");
        }

        return $runResult;
    }

    /**
     * Drop the table.
     *
     * @return boolean
     */
    public function down(): bool
    {
        $sqlArray = [

            "DROP TABLE public.\"{$this->_tableName}\""

        ];

        if ($runResult = $this->_run($this->_className, __FUNCTION__, $sqlArray))
        {
            Logger::getInstance()->logInfo("Table \"{$this->_tableName}\" dropped");
        }

        return $runResult;
    }
}
