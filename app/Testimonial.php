<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'user_id', 'title', 'detail', 'is_confirmed', 'is_featured'
    ];

    protected $with = ['user'];


    public function user(){
        return $this->belongsTo('App\User');
    }

    public function setTitleAttribute($value){
        $this->attributes['title'] = ucfirst($value);
    }

    public function setDetailAttribute($value){
        $this->attributes['detail'] = ucfirst($value);
    }
}
