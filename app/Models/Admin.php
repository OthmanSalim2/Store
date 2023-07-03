<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Admin extends User
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'super_admin', 'status', 'phone_number', 'password'
    ];
}
