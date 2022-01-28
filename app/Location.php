<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Location extends Model
{
    protected $fillable = ['state', 'is_popular'];

    protected $appends = ['slug', 'created'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('state');
        });
    }


    public function service()
    {
        return $this->hasMany('App\Service');
    }

    public function setStateAttribute($value)
    {
        $this->attributes['state'] = ucwords($value);
    }

    public function getSlugAttribute($value)
    {
        $slug = Str::slug($this->state);

        return $slug;
    }

    public function getCreatedAttribute(){
        $date = $this->created_at->format('F jS, Y');
        return $date;
    }
}
