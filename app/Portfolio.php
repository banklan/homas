<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Portfolio extends Model
{
    protected $fillable = ['service_id', 'title', 'detail', 'cost'];

    protected $with = ['file'];

    protected $appends = ['slug', 'published'];

    protected $casts = ['is_approved' => 'boolean'];


    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function service(){
        return $this->belongsTo('App\Service');
    }

    public function file(){
        return $this->hasMany('App\PortfolioFile');
    }

    public function getSlugAttribute($value)
    {
        $slug = Str::slug($this->title);
        return $slug;
    }

    public function getPublishedAttribute($value)
    {
        $date = $this->created_at->format('F jS, Y');
        return $date;
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
