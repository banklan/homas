<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortfolioFile extends Model
{
    protected $fillable = ['portfolio_id', 'file'];

    public function portfolio(){
        return $this->belongsTo('App\Portfolio');
    }
}
