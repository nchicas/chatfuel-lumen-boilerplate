<?php

namespace App\Chatfuel;

use App\Chatfuel\Exceptions\CouldNotCreateMessage;
use App\Chatfuel\Enums\AttachmentType;
use App\Chatfuel\Enums\NotificationType;
use App\Chatfuel\Traits\HasButtons;

/**
 * Class Message.
 */
class Message implements \JsonSerializable
{
    use HasButtons;

    /** @var string Notification Text. */
    protected $text;

    /** @var array Generic Template Cards (items) */
    protected $cards = [];

    /** @var array Quick replies */
    protected $replies = [];

    /** @var string Attachment Type. Defaults to File */
    protected $attachmentType = AttachmentType::FILE;

    /** @var string Attachment URL */
    protected $attachmentUrl;

    /** @var bool */
    protected $hasAttachment = false;

    /** @var bool */
    protected $hasText = false;

    /**
     * @param string $text
     *
     * @return static
     */
    public static function create($text = '')
    {
        return new static($text);
    }

    /**
     * @param string $text
     */
    public function __construct($text = '')
    {
        if ($text != '') {
            $this->text($text);
        }
    }

    /**
     * Notification text.
     *
     * @param $text
     *
     * @throws CouldNotCreateMessage
     *
     * @return $this
     */
    public function text($text)
    {
        if (mb_strlen($text) > 320) {
            throw CouldNotCreateMessage::textTooLong();
        }

        $this->text = $text;
        $this->hasText = true;

        return $this;
    }

    /**
     * Add Attachment.
     *
     * @param $attachmentType
     * @param $url
     *
     * @throws CouldNotCreateMessage
     *
     * @return $this
     */
    public function attach($attachmentType, $url)
    {
        $attachmentTypes = [
            AttachmentType::FILE,
            AttachmentType::IMAGE,
            AttachmentType::VIDEO,
            AttachmentType::AUDIO,
        ];

        if (! in_array($attachmentType, $attachmentTypes)) {
            throw CouldNotCreateMessage::invalidAttachmentType();
        }

        if (! isset($url)) {
            throw CouldNotCreateMessage::urlNotProvided();
        }

        $this->attachmentType = $attachmentType;
        $this->attachmentUrl = $url;
        $this->hasAttachment = true;

        return $this;
    }

    /**
     * Add up to 10 cards to be displayed in a carousel.
     *
     * @param array $cards
     *
     * @return $this
     * @throws CouldNotCreateMessage
     */
    public function cards(array $cards = [])
    {
        if (count($cards) > 10) {
            throw CouldNotCreateMessage::messageCardsLimitExceeded();
        }

        $this->cards = $cards;

        return $this;
    }

    /**
     * Add up to 3 replies to be displayed in a carousel.
     *
     * @param array $replies
     *
     * @return $this
     * @throws CouldNotCreateMessage
     */
    public function replies(array $replies = [])
    {
        if (count($replies) > 3) {
            throw CouldNotCreateMessage::messageRepliesLimitExceeded();
        }

        $this->replies = $replies;

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
     * Returns message payload for JSON conversion.
     *
     * @throws CouldNotCreateMessage
     * @return array
     */
    public function toArray()
    {
        if ($this->hasAttachment) {
            return $this->attachmentMessageToArray();
        }

        if (count($this->buttons) > 0) {
            return $this->buttonMessageToArray();
        }
        else if (count($this->cards) > 0) {
            return $this->genericMessageToArray();
        }
        else if (count($this->replies) > 0) {
            return $this->repliesMessageToArray();
        }
        else if ($this->hasText) {
            return $this->textMessageToArray();
        }

        throw CouldNotCreateMessage::dataNotProvided();
    }

    /**
     * Returns message for simple text message.
     *
     * @return array
     */
    protected function textMessageToArray()
    {
        $message = [];
        $message['text'] = $this->text;

        return $message;
    }

    /**
     * Returns message for attachment message.
     *
     * @return array
     */
    protected function attachmentMessageToArray()
    {
        $message = [];

        if ($this->hasText) {
            $message['text'] = $this->text;
        }

        $message['attachment']['type'] = $this->attachmentType;
        $message['attachment']['payload']['url'] = $this->attachmentUrl;

        return $message;
    }

    /**
     * Returns message for Generic Template message.
     *
     * @return array
     */
    protected function genericMessageToArray()
    {
        $message = [];

        if ($this->hasText) {
            $message['text'] = $this->text;
        }

        $message['attachment']['type'] = 'template';
        $message['attachment']['payload']['template_type'] = 'generic';
        $message['attachment']['payload']['elements'] = $this->cards;

        return $message;
    }

    /**
     * Returns message for Button Template message.
     *
     * @return array
     */
    protected function buttonMessageToArray()
    {
        $message = [];

        if ($this->hasText) {
            $message['text'] = $this->text;
        }

        $message['attachment']['type'] = 'template';
        $message['attachment']['payload']['template_type'] = 'button';
        $message['attachment']['payload']['text'] = $this->text;
        $message['attachment']['payload']['buttons'] = $this->buttons;

        return $message;
    }

    /**
     * Returns message for Quick replies message.
     *
     * @return array
     */
    protected function repliesMessageToArray()
    {
        $message = [];
        
        if ($this->hasText) {
            $message['text'] = $this->text;
        }

        $message['quick_replies'] = $this->replies;

        return $message;
    }
}
