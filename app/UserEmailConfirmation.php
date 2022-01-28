<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEmailConfirmation extends Model
{
    protected $fillable = ['user_id', 'token'];
}
