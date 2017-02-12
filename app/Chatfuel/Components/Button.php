<?php

namespace App\Chatfuel\Components;

use App\Chatfuel\Enums\ButtonType;
use App\Chatfuel\Exceptions\CouldNotCreateButton;

class Button implements \JsonSerializable
{
    /** @var string Button Title */
    protected $title;

    /** @var string Button Type */
    protected $type;

    /** @var string|array Button URL, Postback Data or Phone Number */
    protected $data;

    /** @var array Payload */
    protected $payload = [];

    /** @var array User attributes to set up */
    protected $user_attributes = [];

    /**
     * Create a button.
     *
     * @param string       $title
     * @param string|array $data
     * @param string       $type
     *
     * @return static
     */
    public static function create($title = '', $data = null, $type = ButtonType::WEB_URL)
    {
        return new static($title, $data, $type);
    }

    /**
     * Button Constructor.
     *
     * @param string       $title
     * @param string|array $data
     * @param string       $type
     */
    public function __construct($title = '', $data = null, $type = ButtonType::WEB_URL)
    {
        $this->title = $title;
        $this->data = $data;
        $this->payload['type'] = $type;
    }

    /**
     * Set Button Title.
     *
     * @param $title
     *
     * @return $this
     * @throws CouldNotCreateButton
     */
    public function title($title)
    {

        if( $this->type == ButtonType::ELEMENT_SHARE )
        {
            return $this;
        }

        if ($this->isNotSetOrEmpty($title)) {
            throw CouldNotCreateButton::titleNotProvided();
        } 
        else if (mb_strlen($title) > 20) {
            throw CouldNotCreateButton::titleLimitExceeded($title);
        }

        $this->title = $title;
        $this->payload['title'] = $title;

        return $this;
    }

    /**
     * Set a URL for the button.
     *
     * @param $url
     *
     * @return $this
     * @throws CouldNotCreateButton
     */
    public function url($url)
    {
        if ($this->isNotSetOrEmpty($url)) {
            throw CouldNotCreateButton::urlNotProvided();
        } 
        else if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw CouldNotCreateButton::invalidUrlProvided($url);
        }

        $this->data = $url;
        $this->payload['url'] = $url;
        $this->isTypeWebUrl();

        return $this;
    }

    /**
     * @param $phone
     *
     * @return $this
     * @throws CouldNotCreateButton
     */
    public function phone($phone)
    {
        if ($this->isNotSetOrEmpty($phone)) {
            throw CouldNotCreateButton::phoneNumberNotProvided();
        } 
        else if (is_string($phone) && ! starts_with($phone, '+')) {
            throw CouldNotCreateButton::invalidPhoneNumberProvided($phone);
        }

        $this->data = $phone;
        $this->payload['phone_number'] = $phone;
        $this->isTypePhoneNumber();

        return $this;
    }

    /**
     * @param $postback
     *
     * @return $this
     * @throws CouldNotCreateButton
     */
    public function postback($postback)
    {
        if ($this->isNotSetOrEmpty($postback)) {
            throw CouldNotCreateButton::postbackNotProvided();
        } 
        else if (! filter_var($postback, FILTER_VALIDATE_URL)) {
            throw CouldNotCreateButton::invalidUrlProvided($postback);
        }

        $this->data = $postback;
        $this->payload['url'] = $postback;
        $this->isTypePostback();

        return $this;
    }

    /**
     * @param $blocks
     *
     * @return $this
     * @throws CouldNotCreateButton
     */
    public function blockLink($block_links)
    {
        if ($this->isNotSetOrEmpty($block_links)) {
            throw CouldNotCreateButton::blockLinkNotProvided();
        }
        else if (is_array($block_links)) {
            if( count($block_links) == 0 ) {
                throw CouldNotCreateButton::invalidBlockLinkProvided($block_links);
            }

            foreach ($block_links as $name) {
                if( strpos($name, ' ') > 0 ) {
                    throw CouldNotCreateButton::invalidBlockLinkProvided($block_links);
                }
            }
        }
        else if( strpos($block_links, ' ') > 0 ) {
            throw CouldNotCreateButton::invalidBlockLinkProvided($block_links);
        }

        $this->data = $block_links;
        $property = is_array($block_links) ? 'block_names' : 'block_name';
        $this->payload[$property] = $block_links;
        
        $this->isTypeBlockLink();

        return $this;
    }

    /**
     * Set Button Type.
     *
     * @param $type Possible Values: "web_url", "postback" or "phone_number". Default: "web_url"
     *
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;
        $this->payload['type'] = $type;

        return $this;
    }

    /**
     * Set button type as web_url.
     *
     * @return $this
     */
    public function isTypeWebUrl()
    {
        $this->payload['type'] = ButtonType::WEB_URL;
        $this->type = $this->payload['type'];

        return $this;
    }

    /**
     * Set button type as postback.
     *
     * @return $this
     */
    public function isTypePostback()
    {
        $this->payload['type'] = ButtonType::POSTBACK;
        $this->type = $this->payload['type'];

        return $this;
    }

    /**
     * Set button type as phone_number.
     *
     * @return $this
     */
    public function isTypePhoneNumber()
    {
        $this->payload['type'] = ButtonType::PHONE_NUMBER;
        $this->type = $this->payload['type'];

        return $this;
    }

    /**
     * Set button type as show_block.
     *
     * @return $this
     */
    public function isTypeBlockLink()
    {
        $this->payload['type'] = ButtonType::SHOW_BLOCK;
        $this->type = $this->payload['type'];

        return $this;
    }

    /**
     * Set button type as element_share.
     *
     * @return $this
     */
    public function isTypeElementShare()
    {
        $this->payload['type'] = ButtonType::ELEMENT_SHARE;
        $this->type = $this->payload['type'];

        return $this;
    }

    /**
     * Determine Button Type.
     *
     * @param $type
     *
     * @return bool
     */
    protected function isType($type)
    {
        return isset($this->payload['type']) && $type === $this->payload['type'];
    }

    /**
     * Set Button user attributes.
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
     * Make payload by data and type.
     *
     * @param mixed $data
     *
     * @return $this
     * @throws CouldNotCreateButton
     */
    protected function makePayload($data)
    {
        if ($this->isNotSetOrEmpty($data)) {
            return $this;
        }

        switch ($this->payload['type']) {
            case ButtonType::WEB_URL:
                $this->url($data);
                break;
            case ButtonType::PHONE_NUMBER:
                $this->phone($data);
                break;
            case ButtonType::POSTBACK:
                $this->postback($data);
                break;
            case ButtonType::SHOW_BLOCK:
                $this->blockLink($data);
                break;
        }

        if (isset($this->payload['payload']) && mb_strlen($this->payload['payload']) > 1000) {
            throw CouldNotCreateButton::payloadLimitExceeded($this->payload['payload']);
        }

        return $this;
    }

    /**
     * Builds payload and returns an array.
     *
     * @return array
     * @throws CouldNotCreateButton
     */
    public function toArray()
    {
        $this->title($this->title);
        $this->makePayload($this->data);

        if( $this->user_attributes != null && 
            count($this->user_attributes) > 0 ) {
            $this->payload['set_attributes'] = $this->user_attributes;
        }

        return $this->payload;
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
     * Determine if it's not set or is empty.
     *
     * @param $var
     *
     * @return bool
     */
    protected function isNotSetOrEmpty($var)
    {
        return ! isset($var) || empty($var);
    }
}
