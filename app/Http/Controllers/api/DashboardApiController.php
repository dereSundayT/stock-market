<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Stock;
use App\Models\VirtualInvestment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class DashboardApiController extends Controller
{
    //
    public function index(Request $request)
    {
        $fromDate = $request->fromDate ?? new Carbon('first day of this month');
        $toDate = $request->toDate ?? new Carbon('last day of this month');
        //
        $stocks = Stock::where('status', 1)->where('created_by', '!=', 2)->whereBetween('created_at', [$fromDate, $toDate])->count();
        $clients = Client::where('created_by', '!=', 2)->whereBetween('created_at', [$fromDate, $toDate])->count();
        $purchasedStock = VirtualInvestment::whereBetween('created_at', [$fromDate, $toDate])->count();

        $virtualInv = VirtualInvestment::where('created_by', '!=', 2)->whereBetween('created_at', [$fromDate, $toDate])->select(DB::raw('client_id,stock_id,volume,purchase_price,sum(purchase_price * volume) as total'))->groupBy('client_id')->get();

        $lineGraph = [];
        $labels = [];
        foreach ($virtualInv as $key => $value) {
            # code...
            array_push($lineGraph, $value->total);
            array_push($labels, $value->client->username);
        }


        return successResponse([
            'no_of_stocks' => $stocks,
            'no_of_clients' => $clients,
            'purchased_stock' => $purchasedStock,
            'lineGraph' => $lineGraph,
            'labels' => $labels
        ], 200, '');
    }
}
