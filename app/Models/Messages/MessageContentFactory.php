<?php

namespace App\Models\Messages;

class MessageContentFactory
{
    /**
     * @param array $content
     * @return TextMessage
     */
    public static function create(array $content)
    {
        switch ($content['type']) {
            case 'text_messages':
                return TextMessage::create([ 'content' => $content['content'] ]);
            default:
                abort(400, 'Unknown content type.');
        }
    }
}
