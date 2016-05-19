<?php

namespace App\Http\Controllers\Api\Transformers;

use App\Message;
use League\Fractal\TransformerAbstract;

class MessageTransformer extends TransformerAbstract
{
    public function transform(Message $msg ) {
        $from = $msg->from();
        return [
            'id' => $msg->id,
            'title' => $msg->title,
            'content' => $msg->content,
            'avatar' => $from->avatar,
            'from_id' => $from->id,
            'from_name' => $from->name,
            'created_at' => $msg->created_at->diffForHumans(),
            'isRead' => $msg->isRead
        ];
    }
}