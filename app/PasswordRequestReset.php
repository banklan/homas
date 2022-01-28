<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordRequestReset extends Model
{
    protected $fillable = ['email', 'expiry'];

    protected $appends = ['created', 'expiry'];

    protected $table = 'password_recovery_token';

    protected $date = ['created_at', 'expiry'];


    public function getCreatedAttribute(){
        $date = $this->created_at->format('F jS, Y');
        return $date;
    }

    public function getExpiryAttribute(){
        $expiry = Carbon::parse($this->created_at)->addHours(6)->format('F jS, Y H:i:s');
        return $expiry;
    }

    public function setExpiryAttribute($value){
        Carbon::parse($this->created_at)->addHour(3);
    }
}
