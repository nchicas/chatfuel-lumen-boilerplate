<?php

namespace App\Chatfuel\Exceptions;

class CouldNotCreateResponse extends \Exception
{
    /**
     * Thrown when number of messages in response exceeds.
     *
     * @return static
     */
    public static function messagesLimitExceeded()
    {
        return new static('You cannot add more than 10 messages in 1 response.');
    }
}
