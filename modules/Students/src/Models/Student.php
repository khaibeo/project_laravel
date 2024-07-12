<?php

namespace Modules\Students\src\Models;

use App\Notifications\EmailVerifyQueued;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'address',
        'phone'
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerifyQueued);
    }

}