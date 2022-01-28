<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Enquiry extends Model
{
    use Notifiable;

    protected $fillable = ['fullname', 'position', 'email', 'phone', 'subject', 'message'];

    protected $appends = ['sent'];


    public function setFullnameAttribute($value){
        $this->attributes['fullname'] = ucwords($value);
    }

    public function setPositionAttribute($value){
        $this->attributes['position'] = ucwords($value);
    }

    public function setOrganizationAttribute($value){
        $this->attributes['organization'] = ucfirst($value);
    }

    public function setSubjectAttribute($value){
        $this->attributes['subject'] = ucfirst($value);
    }

    public function setMessageAttribute($value){
        $this->attributes['message'] = ucfirst($value);
    }

    public function getSentAttribute($value)
    {
        $date = $this->created_at->format('F jS, Y');
        return $date;
    }
}
