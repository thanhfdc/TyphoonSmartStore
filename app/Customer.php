<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
    ['username','password','email','phone','sex','city_id','district_id','ward_id','admin','status'];
    protected $table = "customers";

    public function city()
    {
        return $this->belongsTo(City::class ,'city_id','matp');
    }

    public function district()
    {
        return $this->belongsTo(District::class ,'district_id','maqh');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class ,'ward_id','xaid');
    }
}
