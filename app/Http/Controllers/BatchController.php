<?php

namespace App\Http\Controllers;

use App\Http\Requests\Batch\BatchStoreRequest;
use App\Http\Requests\Batch\BatchUpdateRequest;
use App\Models\Batch;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\BatchService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = BatchService::getBatch();
        return view('batches.index', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('batches.create', compact('products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BatchStoreRequest $request)
    {
        try{
            DB::beginTransaction();
            $batch   = BatchService::batchStore($request->all());
            $payment = BatchService::bacthPayment($batch);
            DB::commit();
            return redirect()->route('batches.index');
        }catch(\Throwable $e){
            DB::rollBack();
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Batch $batch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Batch $batch,  Request $request)
    {
        $type = $request->type;
        $products = Product::all();
        $suppliers = Supplier::all();

        return view('batches.edit', compact('batch', 'products', 'suppliers', 'type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Batch $batch)
    {
        if ($request->type === 'adjust_payment') {
            $request->validate([
                'status' => 'required',
                'due_amount' => 'required'
          ]);
        }
        try{
            DB::beginTransaction();
            BatchService::batchUpdate($batch);
            DB::commit();
            return redirect()->route('batches.index');
        }catch(\Throwable $e){
            DB::rollBack();
            dd($e);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect()->back();
    }
}
