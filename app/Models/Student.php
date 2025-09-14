<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $connection = 'student_db';
    protected $guard = 'student';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function sentMessages()
    {
        return $this->morphMany(Message::class, 'from_user');
    }

    public function receivedMessages()
    {
        return $this->morphMany(Message::class, 'to_user');
    }
}
