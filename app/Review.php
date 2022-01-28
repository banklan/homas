<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Review extends Model
{
    protected $fillable = ['user_id', 'service_id', 'title', 'detail', 'rating'];

    protected $with = ['user'];

    protected $appends = ['published', 'ratings'];

    protected $casts = [
        'is_approved'=> 'boolean',
    ];


    protected static function boot(){
        parent::boot();
        // order by created_at in ascending
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function service(){
        return $this->belongsTo('App\Service');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function getPublishedAttribute($value)
    {
        $date = $this->created_at->format('F jS, Y');
        return $date;
    }

    public function getRatingsAttribute($value){
        $rating = floatval($this->rating);
        return $rating;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value);
    }

    public function setDetailAttribute($value)
    {
        $this->attributes['detail'] = ucfirst($value);
    }
}
