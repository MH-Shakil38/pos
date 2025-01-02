<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $data['status'] = $request->status;
            $data['invoice_no'] = intval($request->invoice_no);
            $data = Shipping::query()->create($data);
            $invocie = Invoice::query()->where('invoice_no',$data['invoice_no'])->first();
            $invocie->shipping_id = $data->id;
            $invocie->update(['shipping_id'=>$invocie->shipping_id]);
            DB::commit();
            return redirect()->back()->with('success','Order Status Change Successfully');
        }catch(\Throwable $e){
            DB::rollBack();
            dd($e);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Shipping $shipping)
    {
        dd(123);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipping $shipping)
    {
        dd(123);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipping $shipping)
    {
        try{
            DB::beginTransaction();
            $data['status'] = $request->status;
            $data['invoice_no'] = intval($request->invoice_no);
            $shipping->update($data);
            $invocie = Invoice::query()->where('invoice_no',$request->invoice_no)->first();
            $invocie->update(['shipping_id'=>$shipping->id]);
            DB::commit();
            return redirect()->back()->with('success','Order Status Change Successfully');
        }catch(\Throwable $e){
            DB::rollBack();
            dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipping $shipping)
    {
        //
    }
}
