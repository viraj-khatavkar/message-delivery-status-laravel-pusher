<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\MessageDelivered;
use Illuminate\Http\Request;

class MessageDeliveredController extends Controller
{
    public function __invoke(Conversation $conversation)
    {
    	$conversation->status = 'Delivered';
    	$conversation->save();

    	broadcast(new MessageDelivered($conversation))->toOthers();
    }
}
