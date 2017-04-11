<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\NewMessage;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
	public function index()
	{
		return Conversation::with('user')->get();
	}

    public function store()
    {
    	$conversation = Conversation::create([
    		'message' => request('message'),
    		'status' => 'Sent',
			'user_id' => auth()->user()->id
		]);

		broadcast(new NewMessage($conversation))->toOthers();

		return $conversation->load('user');
    }
}
