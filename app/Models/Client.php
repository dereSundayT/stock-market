<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['username', 'virtual_wallet', 'created_by'];
    protected $appends = ['formated_wallet_bal', 'profit_status'];




    public function getProfitStatusAttribute()
    {
        //client_id
        $virtual =  VirtualInvestment::where('client_id', $this->id)->get();
        $sum = 0;
        foreach ($virtual as $item) {
            $amount_invested = $item->purchase_price * $item->volume;

            $stock = Stock::where('id', $item->stock_id)->first();
            $total_current_price = $stock->unit_price * $item->volume;
            $sum  += $total_current_price - $amount_invested;
        }

        return $sum;
    }

    public function getFormatedWalletBalAttribute()
    {
        return "€ $this->virtual_wallet";
    }
}
