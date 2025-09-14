<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $connection = 'message_db';

    protected $fillable = [
        'from_user_id', 'from_user_type', 'to_user_id', 'to_user_type', 'message'
    ];

    public function from_user()
    {
        return $this->morphTo(null, 'from_user_type', 'from_user_id');
    }

    public function to_user()
    {
        return $this->morphTo(null, 'to_user_type', 'to_user_id');
    }
}
