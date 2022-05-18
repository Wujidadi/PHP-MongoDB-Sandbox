<?php

namespace App;

use MongoDB\Client as MongoClient;
use App\Exceptions\MongoException;
use Libraries\Logger;

/**
 * MongoDB handling class, based on the official MongoDB PHP Library (https://github.com/mongodb/mongo-php-library)
 */
class MongoModel
{
    /**
     * Names of MongoDB Client exception types.
     *
     * @var string[]
     */
    const MONGO_CLIENT_EXCEPTIONS = [
        'InvalidArgumentException',
        'DriverInvalidArgumentException',
        'DriverRuntimeException'
    ];

    /**
     * MongoDB Client instance.
     *
     * @var MongoDB\Client|null
     */
    protected $_dbconn;

    /**
     * Type of database; should be **mongodb**.
     *
     * @var string
     */
    protected $_dbtype;

    /**
     * Host or Domain name of the database server.
     *
     * @var string
     */
    protected $_host;

    /**
     * Port or Domain name of the database server.
     *
     * @var string|integer
     */
    protected $_port;

    /**
     * Name of the database.
     *
     * @var string
     */
    protected $_dbname;

    /**
     * User name to access the database.
     *
     * @var string
     */
    protected $_username;

    /**
     * Password of the user to access the database.
     *
     * @var string
     */
    protected $_password;

    /**
     * Connection status to the database.
     *
     * @var boolean
     */
    protected $_connectionStatus = false;

    /**
     * Name of the target collection.
     *
     * @var string
     */
    protected $_collection;

    /**
     * Name of this class.
     *
     * @var string
     */
    protected $_className;

    /**
     * Unique instance of this class.
     *
     * @var self|null
     */
    protected static $_uniqueInstance = null;

    /**
     * Get the unique instance of this class.
     *
     * @param  string  $configKey  Key of the database configurations array.
     * @return self
     */
    public static function getInstance(string $configKey = 'MONGO'): self
    {
        if (self::$_uniqueInstance === null)
        {
            self::$_uniqueInstance = new self(
                DB_CONFIG[$configKey]['TYPE'],
                DB_CONFIG[$configKey]['HOST'],
                DB_CONFIG[$configKey]['PORT'],
                DB_CONFIG[$configKey]['DATABASE'],
                DB_CONFIG[$configKey]['USERNAME'],
                DB_CONFIG[$configKey]['PASSWORD']
            );
        }
        return self::$_uniqueInstance;
    }

    /**
     * Constructor.
     *
     * @param  string          $dbtype    Database type
     * @param  string          $host      Database host
     * @param  integer|string  $port      Database port
     * @param  string          $dbname    Database name
     * @param  string          $username  Database username
     * @param  string          $password  Database password
     * @return void
     */
    public function __construct(string $dbtype, string $host, mixed $port, string $dbname, string $username, string $password)
    {
        $this->_dbtype   = $dbtype;
        $this->_host     = $host;
        $this->_port     = $port;
        $this->_dbname   = $dbname;
        $this->_username = $username;
        $this->_password = $password;

        $this->_connect();
    }

    /**
     * Connect to the database.
     *
     * @return void
     */
    protected function _connect(): void
    {
        try
        {
            $dsn = "{$this->_dbtype}://{$this->_username}:{$this->_password}@{$this->_host}:{$this->_port}/?authSource={$this->_dbname}";

            $this->_dbconn = new MongoClient($dsn);

            $this->_connectionStatus = true;
        }
        catch (\Throwable $ex)
        {
            $exType = get_class($ex);
            $exCode = $ex->getCode();
            $exMessage = $ex->getMessage();
            $exTrace = $ex->getTraceAsString();

            if (in_array($exType, self::MONGO_CLIENT_EXCEPTIONS))
            {
                $logMessage = "{$exType} ({$exCode}) {$exMessage}\n{$exTrace}";
                Logger::getInstance()->logError($logMessage);
            }
            else
            {
                throw new $exType($exMessage, $exCode);
            }
        }
    }

    /**
     * Set the name of the collection to query.
     *
     * @param  string  $collection  Name of the collection to query
     * @return self
     */
    public function setCollection(string $collection): self
    {
        $this->_collection = $collection;
        return self::$_uniqueInstance;
    }

    /**
     * Insert documents.
     *
     * See https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-insertOne/  
     * and https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-insertMany/
     *
     * @param  array|object  $document  The documents to insert into the collection.
     * @param  array         $options   An array specifying the desired options.
     * @return integer
     */
    public function insert(mixed $document, array $options = []): int
    {
        if (!is_null($this->_collection) && is_array($document) && count($document) > 0)
        {
            $collection = $this->_dbconn->{$this->_dbname}->{$this->_collection};

            if (!is_array($document[array_key_first($document)]))
            {
                $result = $collection->insertOne($document, $options);
            }
            else
            {
                $result = $collection->insertMany($document, $options);
            }

            return $result->getInsertedCount();
        }
        else if (!is_array($document))
        {
            throw new MongoException('Invalid insertion data', MongoException::INVALID_INSERTION_DATA);
        }
        else if (count($document) <= 0)
        {
            throw new MongoException('Empty insertion data', MongoException::EMPTY_INSERTION_DATA);
        }
        else
        {
            throw new MongoException('Unassigned collection', MongoException::UNASSIGNED_COLLECTION);
        }
    }

    /**
     * Count the number of documents that match the filter criteria.
     *
     * See https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-count/
     *
     * @param  array  $filter|object  The filter criteria that specifies the documents to count
     * @param  array  $options        An array specifying the desired options
     * @return integer
     */
    public function count(mixed $filter = [], array $options = []): int
    {
        if (!is_null($this->_collection))
        {
            $collection = $this->_dbconn->{$this->_dbname}->{$this->_collection};
            return $collection->count($filter, $options);
        }
        else
        {
            throw new MongoException('Unassigned collection', MongoException::UNASSIGNED_COLLECTION);
        }
    }

    /**
     * Finds documents matching the query.
     *
     * See https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-find/
     *
     * @param  array  $filter|object  The filter criteria that specifies the documents to count
     * @param  array  $options        An array specifying the desired options
     * @return array
     */
    public function select(mixed $filter = [], array $options = []): array
    {
        if (!is_null($this->_collection))
        {
            $collection = $this->_dbconn->{$this->_dbname}->{$this->_collection};
            return $collection->find($filter, $options)->toArray();
        }
        else
        {
            throw new MongoException('Unassigned collection', MongoException::UNASSIGNED_COLLECTION);
        }
    }

    /**
     * Update documents that match the filter criteria.
     *
     * See https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-updateOne/  
     * and https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-updateMany/
     *
     * @param  array|object  $filter   The filter criteria that specifies the documents to update
     * @param  array|object  $update   Specifies the field and value combinations to update and any relevant update operators
     * @param  array         $options  An array specifying the desired options
     * @return integer
     */
    public function update(mixed $filter, mixed $update, array $options = []): int
    {
        if (!is_null($this->_collection) && is_array($filter) &&
            is_array($update) && count($update) > 0)
        {
            $collection = $this->_dbconn->{$this->_dbname}->{$this->_collection};
            $result = $collection->updateMany($filter, $update, $options);
            return $result->getModifiedCount();
        }
        else if (!is_array($filter))
        {
            throw new MongoException('Invalid updating filter', MongoException::INVALID_UPDATING_FILTER);
        }
        else if (!is_array($update))
        {
            throw new MongoException('Invalid updating parameter', MongoException::INVALID_UPDATING_PARAMETER);
        }
        else if (count($update) <= 0)
        {
            throw new MongoException('Empty updating parameter', MongoException::EMPTY_UPDATING_PARAMETER);
        }
        else
        {
            throw new MongoException('Unassigned collection', MongoException::UNASSIGNED_COLLECTION);
        }
    }

    /**
     * Deletes documents that match the filter criteria.
     *
     * See https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-deleteOne/  
     * and https://www.mongodb.com/docs/php-library/current/reference/method/MongoDBCollection-deleteMany/
     *
     * @param  array|object  $filter   The filter criteria that specifies the documents to delete
     * @param  array         $options  An array specifying the desired options
     * @return integer
     */
    public function delete(mixed $filter, array $options = []): int
    {
        if (!is_null($this->_collection) && is_array($filter))
        {
            $collection = $this->_dbconn->{$this->_dbname}->{$this->_collection};
            $result = $collection->deleteMany($filter, $options);
            return $result->getDeletedCount();
        }
        else if (!is_array($filter))
        {
            throw new MongoException('Invalid deleting filter', MongoException::INVALID_DELETING_FILTER);
        }
        else
        {
            throw new MongoException('Unassigned collection', MongoException::UNASSIGNED_COLLECTION);
        }
    }
}
