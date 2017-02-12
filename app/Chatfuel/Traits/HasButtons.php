<?php

namespace App\Chatfuel\Traits;

use App\Chatfuel\Exceptions\CouldNotCreateMessage;

trait HasButtons
{
    /** @var array Call to Action Buttons */
    protected $buttons = [];

    /**
     * Add up to 3 call to action buttons.
     *
     * @param array $buttons
     *
     * @return $this
     * @throws CouldNotCreateMessage
     */
    public function buttons(array $buttons = [])
    {
        if (count($buttons) > 3) {
            throw CouldNotCreateMessage::messageButtonsLimitExceeded();
        }

        $this->buttons = $buttons;

        return $this;
    }
}
