<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['username', 'virtual_wallet'];
    protected $appends = ['formated_wallet_bal', 'profit_status'];




    public function getProfitStatusAttribute()
    {
        //client_id
        $virtual =  VirtualInvestment::where('client_id', $this->id)->get();
        $lifeTimePurchase = $virtual->sum('purchase_price');
        return $lifeTimePurchase;
    }

    public function getFormatedWalletBalAttribute()
    {
        return "€ $this->virtual_wallet";
    }
}
