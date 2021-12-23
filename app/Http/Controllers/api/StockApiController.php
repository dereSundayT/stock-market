<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $stocks = Stock::where('status', 1)->get();
        return successResponse($stocks, 200, 'Stock feteched Successfully');
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
            'company_name' => 'required',
            'unit_price' => 'required'
        ]);

        $stock =  Stock::create($request->all());
        if ($stock) {
            return successResponse($stock, 201, 'New Stock added Succesfully');
        } else {
            return errorResponse(500, 'Error while saving new Stock');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $stock = Stock::where('id', $id)->first();
        if ($stock) {
            $this->validate($request, [
                'company_name' => 'required',
                'unit_price' => 'required'
            ]);
            $q =  $stock->update($request->all());
            if ($q) {
                $updatedStock = Stock::where('id', $id)->first();
                return successResponse($updatedStock, 200, 'Stock updated Successfully');
            } else {
                return errorResponse(500, 'Failed to update Stock');
            }
        } else {
            return errorResponse(500, 'Stock not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $stock = Stock::where('id', $id)->first();
        if ($stock) {
            $stock->update(['status' => 2]);
        }
    }
}
