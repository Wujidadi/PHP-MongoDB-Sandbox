<?php

namespace App\Exceptions;

/**
 * MongoDB model exception class.
 */
class MongoException extends \Exception
{
    const UNASSIGNED_COLLECTION      = 1;
    const INVALID_INSERTION_DATA     = 2;
    const EMPTY_INSERTION_DATA       = 3;
    const INVALID_UPDATING_FILTER    = 4;
    const INVALID_UPDATING_PARAMETER = 5;
    const EMPTY_UPDATING_PARAMETER   = 6;
    const INVALID_DELETING_FILTER    = 7;

    protected $message;
    protected $code;

    public function __construct($message = null, $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
