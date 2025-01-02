<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Shipping;
use App\Services\InvoiceService;
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

        $request->validate([
            "customer_id"   => "required",
            "total"         => "required",
            "due_amount"    => "required",
            "products"      => "required",
            "status"        => "required",
        ]);

        try {
            DB::beginTransaction();
            $invoice    = InvoiceService::invoiceStore();
            $courrier   = InvoiceService::bulkCreate($invoice);
            $shipping   = InvoiceService::shippingCreate($courrier);
            $payment    = InvoiceService::invoicePayment($invoice);
            // update shipping id from courier cons_id
            $invoice->shipping_id = $shipping->id;
            $invoice->update();
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
        try {
            DB::beginTransaction();
            $invoice->due = $request->due_amount;
            $invoice->update();
            $payment = InvoiceService::invoicePayment($invoice);
            DB::commit();
            return redirect()->route('invoices.index');
        } catch (\Throwable $e) {
            dd($e);
        }
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
