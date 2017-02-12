## About

This is a boilerplate project using Lumen/Laravel for allowing dynamic conversations using Chatfuel JSON plugin. This project has all the necessary classes to manage all the cases listed in: https://help.chatfuel.com/facebook-messenger/plugins/json-plugin/. The Lumen version is intended to be 5.2 to provide HHVM support and blazing fast response times.

## Examples

```php
 public function sendTextMessages() 
    {
        $response = Response::create([
            Message::create('Welcome to our store!'),
            Message::create('How can I help you?')
        ]);

        return $response;
    }

    public function sendImage() 
    {
        $message = new Message();
        $message->attach( 
            AttachmentType::IMAGE, 
            'https://petersapparel.parseapp.com/img/item101-thumb.png' 
        );

        $response = Response::create([
            $message
        ]);

        return $response;
    }

    public function sendVideo() 
    {
        $message = new Message();
        $message->attach( 
            AttachmentType::VIDEO, 
            'https://petersapparel.parseapp.com/img/item101-video.mp4' 
        );

        $response = Response::create([
            $message
        ]);

        return $response;
    }

    public function sendAudio() 
    {
        $message = new Message();
        $message->attach( 
            AttachmentType::AUDIO, 
            'https://archive.org/download/Dictaphones_Lament/TychoDictaphones_Lament.mp3' 
        );

        $response = Response::create([
            $message
        ]);

        return $response;
    }

    public function sendCardWithButtons() 
    {
        $message = new Message();
        $message->buttons([
            Button::create()
                ->type(ButtonType::SHOW_BLOCK)
                ->blockLink('SomeBlockName')
                ->title('Show the block!'),
            Button::create()
                ->type(ButtonType::WEB_URL)
                ->url('https://petersapparel.parseapp.com/buy_item?item_id=100')
                ->title('Buy item')
        ]);

        $response = Response::create([
            $message
        ]);

        return $response;
    }

    public function sendGallery() 
    {
        $message = new Message();
        $message->cards([
            Card::create()
                ->title('Classic White T-Shirt')
                ->image('http://petersapparel.parseapp.com/img/item100-thumb.png')
                ->subtitle('Soft white cotton t-shirt is back in style')
                ->buttons([
                    Button::create()
                        ->type(ButtonType::WEB_URL)
                        ->url('https://petersapparel.parseapp.com/view_item?item_id=100')
                        ->title('View Item'),
                    Button::create()
                        ->type(ButtonType::WEB_URL)
                        ->url('https://petersapparel.parseapp.com/buy_item?item_id=100')
                        ->title('Buy Item'),
                ]),
            Card::create()
                ->title('Classic Gray T-Shirt')
                ->image('http://petersapparel.parseapp.com/img/item101-thumb.png')
                ->subtitle('Soft gray cotton t-shirt is back in style')
                ->buttons([
                    Button::create()
                        ->type(ButtonType::WEB_URL)
                        ->url('https://petersapparel.parseapp.com/view_item?item_id=101')
                        ->title('View Item'),
                    Button::create()
                        ->type(ButtonType::WEB_URL)
                        ->url('https://petersapparel.parseapp.com/buy_item?item_id=101')
                        ->title('Buy Item'),
                ])
        ]);

        $response = Response::create([
            $message
        ]);

        return $response;
    }

    public function linkBlock() 
    {
        $message = new Message();
        $message->buttons([
            Button::create()
                ->type(ButtonType::SHOW_BLOCK)
                ->blockLink('Finish')
                ->title('Finish'),
            Button::create()
                ->type(ButtonType::SHOW_BLOCK)
                ->blockLink(['Block1','Block2'])
                ->title('Finish Shopping')
        ]);

        $response = Response::create([
            $message
        ]);

        return $response;
    }

    public function quickReply() 
    {
        $message = Message::create('testRedirectInQuickReply')
            ->replies([
                Button::create()
                    ->title('go')
                    ->blockLink(['Block1', 'Block2'])
            ]);

        $response = Response::create([
            $message
        ]);

        return $response;
    }

    public function postBack()
    {
        $message = Message::create('test JSON with postback');
        $message->buttons([
            Button::create()
                ->type(ButtonType::POSTBACK)
                ->postback('http://pastebin.com/raw/bYwUN7un')
                ->title('go')
        ]);

        $response = Response::create([
            $message
        ]);

        return $response;
    }

    public function setAttributes() 
    {
        $attributes = [
            'some attribute' => 'some value',
            'another attribute' => 'another value'
        ];

        $message = Message::create('Set user attributes');
        $message->buttons([
            Button::create()
                ->type(ButtonType::SHOW_BLOCK)
                ->blockLink(['BlockWithUserAttributes'])
                ->title('go')
                ->userAttributes($attributes)
        ]);

        $response = Response::create([
            $message
        ]);

        return $response;
    }

    public function setAttributesSilently() 
    {
        $attributes = [
            'some attribute' => 'some value',
            'another attribute' => 'another value'
        ];

        $response = Response::create([
            Message::create('Hello!')
        ])->userAttributes($attributes);

        return $response;
    }

    public function sendShareCallButtons() 
    {
        $message = new Message();
        $message->cards([
            Card::create()
                ->title('Classic White T-Shirt')
                ->image('http://petersapparel.parseapp.com/img/item100-thumb.png')
                ->subtitle('Soft white cotton t-shirt is back in style')
                ->buttons([
                    Button::create()
                        ->type(ButtonType::PHONE_NUMBER)
                        ->phone('+79268881413')
                        ->title('Call'),
                    Button::create()
                        ->type(ButtonType::ELEMENT_SHARE)
                ])
        ]);

        $response = Response::create([
            $message
        ]);

        return $response;
    }
```

### License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
