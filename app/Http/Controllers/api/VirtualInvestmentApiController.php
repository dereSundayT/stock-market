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
            $request['purchase_price'] = $purchasePrice;

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
        $stockPurchase =  VirtualInvestment::where('client_id', $client_id)->get();
        if ($stockPurchase) {
            return successResponse($stockPurchase, 200, 'Successfully fetched Stock Purchase');
        } else {
            return errorResponse(500, 'Failed to fetch purchase history for this client');
        }
    }
}
