<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;
use App\Review;


class Service extends Model
{
    protected $fillable = ['user_id', 'category_id', 'location_id', 'title', 'description', 'experience_years', 'keystrength', 'highlight', 'phone_number'];

    protected $with = ['review', 'portfolio', 'location', 'category', 'user'];

    protected $appends = ['slug', 'published', 'ratings'];

    protected $dates = ['created_at'];

    protected $casts = [
        'is_approved'=> 'boolean', 'is_premium'=> 'boolean', 'is_featured'=> 'boolean'
    ];


    public function user(){
        return $this->belongsTo('App\User');
    }

    public function portfolio(){
        return $this->hasMany('App\Portfolio');
    }

    public function review(){
        return $this->hasMany('App\Review');
    }

    public function location(){
        return $this->belongsTo('App\Location');
    }

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function getSlugAttribute($value)
    {
        $slug = Str::slug($this->title);
        return $slug;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ucfirst($value);
    }

    public function setHighlightAttribute($value)
    {
        $this->attributes['highlight'] = ucfirst($value);
    }

    public function setKeystrengthAttribute($value)
    {
        $this->attributes['keystrength'] = ucfirst($value);
    }

    public function getPublishedAttribute($value)
    {
        // $date = Carbon::parse($this->created_at)->toFormattedDateString();
        $date = $this->created_at->format('F jS, Y');
        return $date;
    }

    public function getRatingsAttribute($value){
        $total = 0;
        $id = $this->id;
        $reviews = Review::where('service_id', $id)->get();
        foreach ($reviews as $review) {
            $total = $total + $review->rating;
        }

        if($reviews->count() > 0){
            return intval(round($total / $reviews->count()));
        }else{
            return 0;
        }
    }

    public function setCityAttribute($value){
        $this->attributes['city'] = ucfirst($value);
    }
}
