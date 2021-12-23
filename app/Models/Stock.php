<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;


    protected $fillable = ['company_name', 'unit_price'];



    public function setCompanyNameAttribute($value)
    {
        $this->attributes['company_name'] = strtolower($value);
    }

    public function getCompanyNameAttribute()
    {
        return strtoupper($this->company_name);
    }
}
