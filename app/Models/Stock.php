<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;


    protected $fillable = ['company_name', 'unit_price', 'status', 'created_by'];
    protected $appends = ['formated_unit_price'];



    public function setCompanyNameAttribute($value)
    {
        $this->attributes['company_name'] = strtolower($value);
    }

    public function getCompanyNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function getFormatedUnitPriceAttribute()
    {
        return "€ $this->unit_price";
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    // public function getUnit
}
