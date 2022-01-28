<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushNotif extends Model
{
    protected $fillable = ['name'];
    protected $table = 'test_notif';
}
