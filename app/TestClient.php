<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestClient extends Model
{
    protected $fillable = [
        'name', 'age', 'phone', 'address',
    ];

    public function setNameAttribute($value){
        $this->attributes['name'] = ucwords($value);
    }

    public function setPhoneAttribute($value){
        $prec = 0;
        if($value[0] !== 0){
            $this->attributes['phone'] = $prec.$value;
        }
    }
}
