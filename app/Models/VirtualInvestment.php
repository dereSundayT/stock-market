<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualInvestment extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'stock_id', 'volume', 'purchase_price'];
    protected $appends = ['current_price', 'profit_status', 'formated_purchase_price', 'formated_current_price'];


    public function Stock()
    {
        return $this->belongsTo(Stock::class);
    }




    public function getCurrentPriceAttribute()
    {
        $stock = Stock::select('unit_price')->where('id', $this->stock_id)->first();
        return $stock->unit_price;
    }

    public function getProfitStatusAttribute()
    {
        $stock = Stock::where('id', $this->stock_id)->first();

        $total_current_price = $stock->unit_price * $this->volume;
        $purchase_price = $this->purchase_price;

        $amount = $total_current_price - $purchase_price;

        return [
            'amount' =>   $amount,
            'profit' => $amount > 0 ? true : false
        ];
    }





    public function getFormatedPurchasePriceAttribute($value)
    {
        return "€ $this->purchase_price";
    }

    public function getFormatedCurrentPriceAttribute($value)
    {
        return "€ $this->current_price";
    }
}
