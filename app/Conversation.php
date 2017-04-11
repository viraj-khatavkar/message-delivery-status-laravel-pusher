<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['message', 'user_id', 'status'];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
