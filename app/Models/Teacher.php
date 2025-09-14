<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use Notifiable;

    protected $connection = 'teacher_db';
    protected $guard = 'teacher';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    // May define relation to messages

    public function sentMessages()
    {
        return $this->morphMany(Message::class, 'from_user');
    }

    public function receivedMessages()
    {
        return $this->morphMany(Message::class, 'to_user');
    }
}
