<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Stock;
use App\Models\VirtualInvestment;
use Illuminate\Http\Request;

class VirtualInvestmentApiController extends Controller
{
    public function addNewClient(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:clients,username'
        ]);
        $request['created_by'] = auth()->user()->id;

        $newClient =  Client::create($request->all());
        if ($newClient) {
            return successResponse($newClient, 201, 'New Client added Successfully');
        } else {
            return errorResponse(500, 'Failed to create new client');
        }
    }

    public function getAllClient()
    {
        $clients = Client::all();
        if ($clients) {
            return successResponse($clients, 200, 'Clients record fetched Successfully');
        } else {
            return errorResponse(500, 'Error while getting clients record');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'client_id' => 'required',
            'stock_id' => 'required',
            'volume' => 'required',
        ]);

        $user = auth()->user();
        ////gett only the items needed
        $stock =  Stock::where('id', $request->stock_id)->first();
        $client = Client::where('id', $request->client_id)->first();
        //
        $currentWalletBalance = $client->virtual_wallet;
        //
        $purchasePrice = $request->volume * $stock->unit_price;
        //check if the current balance is greater than the current purchase price
        $newBal = $currentWalletBalance - $purchasePrice;
        if ($newBal > 0) {
            $request['purchase_price'] = $stock->unit_price;
            $request['created_by'] = $user->id;
            $newPurchase = VirtualInvestment::create($request->all());
            if ($newPurchase) {
                //use db Transaction
                $client_update =  Client::where('id', $request->client_id)->update(['virtual_wallet' => $newBal]);
                if ($client_update) {
                    return successResponse($newPurchase, 201, 'Stock purchase successful');
                }
            } else {
                return errorResponse(500, 'Failed to complete Purchase process');
            }
        } else {
            return errorResponse(419, 'Insuffient Balance');
        }
    }
    //
    public function allStockPurchaseByClient($client_id)
    {
        $stockPurchase =  VirtualInvestment::with(['stock'])->where('client_id', $client_id)->get();
        $stocks = Stock::where('status', 1)->get();
        $user = Client::where('id', $client_id)->first();

        if (count($stockPurchase) > 0) {
            $invested = 0;
            $total = 0;
            foreach ($stockPurchase as $item) {
                $amount_invested = $item->purchase_price * $item->volume;
                $invested += $amount_invested;
                //
                $stock = Stock::where('id', $item->stock_id)->first();
                $total_current_price = $stock->unit_price * $item->volume;
                $total  += $total_current_price - $amount_invested;
            }
            $performance = number_format(($total / $invested) * 100, 2);
        } else {
            $invested = 0;
            $total = 0;
            $performance = 0;
        }



        //total
        $summary = [
            'invested' => "€ $invested",
            'total' =>  $total > 0 ? "+ € $total" : ($total == 0 ? "€ $total" : "-€" . abs($total)),
            'total_status' => $total > 0 ? true : ($total == 0 ? '' : false),
            'performance' => $performance > 0 ? "+ $performance %" : "$performance %",
            'performance_status' => $performance > 0 ? true : false
        ];
        // return $summary;
        if ($stockPurchase) {
            return successResponse($stockPurchase, 200, 'Successfully fetched Stock Purchase', ["stocks" => $stocks, 'user' => $user, 'summary' => $summary]);
        } else {
            return errorResponse(500, 'Failed to fetch purchase history for this client');
        }
    }


    public function fundClientWallet(Request $request)
    {
        //
        $this->validate($request, [
            'amount' => 'required',
            'client_id' => 'required'
        ]);
        //
        $client = Client::where('id', $request->client_id)->first();

        $newAmount = $request->amount +  $client->virtual_wallet;
        if ($client->update(['virtual_wallet' => $newAmount])) {
            return successResponse([], 200, 'Client wallet funded Successfully');
        }
    }
}
