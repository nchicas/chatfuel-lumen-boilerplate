<?php

namespace App\Chatfuel;

use App\Chatfuel\Exceptions\CouldNotCreateResponse;

/**
 * Class Message.
 */
class Response implements \JsonSerializable
{
    /** @var array Collection of messages */
    protected $messages = [];

    /** @var array User attributes to set up */
    protected $user_attributes = [];

    /**
     * @param array $messages
     *
     * @return static
     */
    public static function create($messages = null)
    {
        return new static($messages);
    }

    /**
     * @param array $messages
     */
    public function __construct($messages = null)
    {
        if ($messages != null) {
            $this->messages($messages);
        }
    }

    /**
     * Add up to 10 messages to the response.
     *
     * @param array $messages
     *
     * @return $this
     * @throws CouldNotCreateResponse
     */
    public function messages(array $messages = [])
    {
        if (count($messages) > 10) {
            throw CouldNotCreateResponse::messagesLimitExceeded();
        }

        $this->messages = $messages;

        return $this;
    }

    /**
     * Set Response user attributes.
     *
     * @param $user_attribute User attributes to set up
     *
     * @return $this
     */
    public function userAttributes($user_attributes)
    {
        $this->user_attributes = $user_attributes;

        return $this;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Returns response payload for JSON conversion.
     *
     * @throws CouldNotCreateMessage
     * @return array
     */
    public function toArray()
    {
        $this->messages($this->messages);
        $this->userAttributes($this->user_attributes);

        $response = [
            'messages' => $this->messages
        ];

        if( $this->user_attributes != null && 
            count($this->user_attributes) > 0 ) {
            $response['set_attributes'] = $this->user_attributes;
        }

        return $response;
    }
}
