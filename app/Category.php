<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = ['name'];

    protected $appends = ['slug', 'name', 'created'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('name');
        });
    }

    public function service()
    {
        return $this->hasMany('App\Service');
    }

    public function getSlugAttribute($value)
    {
        $slug = Str::slug($this->name);

        return $slug;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function getNameAttribute($value){
        $name = ucwords($this->attributes['name']);
        return $name;
    }

    public function getCreatedAttribute(){
        $date = $this->created_at->format('F jS, Y');
        return $date;
    }
}
