<?php

namespace Database\Migrations\Tables;

use Libraries\Logger;
use Database\Migration;

/**
 * Migration class of the table `DemoTable`.
 */
class DemoTable extends Migration
{
    /**
     * Name of the target table.
     *
     * @var string
     */
    protected $_tableName = 'DemoTable';

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
            CREATE TABLE public."{$this->_tableName}"
            (
                "ID"         bigserial                                              NOT NULL,
                "Type"       character varying(191)   COLLATE pg_catalog."C.UTF-8"  NOT NULL,
                "Content"    character varying(2048)  COLLATE pg_catalog."C.UTF-8"  NOT NULL,
                "Data"       jsonb                                                  NOT NULL,
                "Flag"       boolean,
                "Available"  unsigned_tinyint                                       NOT NULL  DEFAULT 1,
                "Editable"   unsigned_tinyint                                       NOT NULL  DEFAULT 1,
                "CreatedAt"  timestamp(6) with time zone                            NOT NULL  DEFAULT CURRENT_TIMESTAMP,
                "UpdatedAt"  timestamp(6) with time zone                            NOT NULL  DEFAULT CURRENT_TIMESTAMP,

                CONSTRAINT "{$this->_tableName}_ID" UNIQUE ("ID"),

                PRIMARY KEY ("ID")
            )
            TABLESPACE pg_default
            SQL,

            "COMMENT ON TABLE public.\"{$this->_tableName}\" IS '示範資料表'",

            "ALTER TABLE public.\"{$this->_tableName}\" OWNER to root",

            "COMMENT ON COLUMN public.\"{$this->_tableName}\".\"ID\"        IS '流水號（唯一值，主鍵）'",

            "COMMENT ON COLUMN public.\"{$this->_tableName}\".\"Type\"      IS '類型'",

            "COMMENT ON COLUMN public.\"{$this->_tableName}\".\"Content\"   IS '內容'",

            "COMMENT ON COLUMN public.\"{$this->_tableName}\".\"Data\"      IS '結構化資料'",

            "COMMENT ON COLUMN public.\"{$this->_tableName}\".\"Flag\"      IS '旗標'",

            "COMMENT ON COLUMN public.\"{$this->_tableName}\".\"Available\" IS '可用性'",

            "COMMENT ON COLUMN public.\"{$this->_tableName}\".\"Editable\"  IS '可變性'",

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
     * Drop the table.
     *
     * @return boolean
     */
    public function down(): bool
    {
        $sqlArray = [

            "DROP TABLE IF EXISTS public.\"{$this->_tableName}\""

        ];

        if ($runResult = $this->_run($this->_className, __FUNCTION__, $sqlArray))
        {
            Logger::getInstance()->logInfo("Table \"{$this->_tableName}\" dropped");
        }

        return $runResult;
    }
}
