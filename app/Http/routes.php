<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->post('/text-messages', 'TestController@sendTextMessages');
$app->post('/image-attachment', 'TestController@sendImage');
$app->post('/video-attachment', 'TestController@sendVideo');
$app->post('/audio-attachment', 'TestController@sendAudio');
$app->post('/card-with-buttons', 'TestController@sendCardWithButtons');
$app->post('/gallery', 'TestController@sendGallery');
$app->post('/link-block', 'TestController@linkBlock');
$app->post('/quick-reply', 'TestController@quickReply');
$app->post('/postback', 'TestController@postback');
$app->post('/set-attributes', 'TestController@setAttributes');
$app->post('/set-attributes-silently', 'TestController@setAttributesSilently');
$app->post('/share-call-buttons', 'TestController@sendShareCallButtons');

