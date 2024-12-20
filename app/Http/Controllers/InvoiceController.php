<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Shipping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::latest()->get();
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $batches = Batch::where('rem_quantity', '>=', 1)->get();

        return view('invoices.create', compact('batches', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $profit = 0;
        $current_date = Carbon::today();
        $invoice_count = Invoice::whereDate('created_at', $current_date)->count() + 1;

        $request->validate([
            "customer_id" => "required",
            "total" => "required",
            "due_amount" => "required",
            "products" => "required",
            "status" => "required",
        ]);

        try {
            DB::beginTransaction();
            $products = $request['products'];
            foreach ($products as $key => $product) {
                $batch = Batch::find($product['batch_id']);
                $batch->rem_quantity -= $product['quantity'];
                $batch->update();
                $profit += $product['total'] - ($batch->purchase_price * $product['quantity']);
            };

            // store shipping
            $shipping = new Shipping();
            $shipping['currier_number'] = $request->currier_number ?? 0;
            $shipping['shipping_cost'] = $request->shipping_cost ?? 0;
            $shipping->save();

            // store invoice
            $invoice = new Invoice();
            $invoice->invoice_no = Carbon::now()->format('dmY') . '-' . $invoice_count;
            $invoice->customer_id = $request->customer_id;
            $invoice->shipping_id = $shipping->id;
            $invoice->products = json_encode($request->products);
            $invoice->total = $request->total;
            $invoice->due = $request->due_amount;
            $invoice->status = $request->status;
            $invoice->profit = $profit;
            $invoice->save();
            DB::commit();
            return redirect()->route('invoices.show', $invoice);
        } catch (\Throwable $e) {
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $products = json_decode($invoice->products);
        return view('invoices.58m-invoice', compact('invoice', 'products'));
        return view('invoices.invoice', compact('invoice', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $invoice->due = $request->due_amount;
        $invoice->update();
        return redirect()->route('invoices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $products = json_decode($invoice->products);
        foreach ($products as $key => $product) {
            $batch = Batch::find($product->batch_id);
            $batch->rem_quantity += $product->quantity;
            $batch->update();
        };

        $invoice->delete();
        return redirect()->back();
    }
}
