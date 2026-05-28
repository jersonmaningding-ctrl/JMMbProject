<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    protected $table = 'user_accounts';

    protected $fillable = [
        'email',
        'username',
        'password',
        'must_change_password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];
}
