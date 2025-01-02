<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Shipping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class InvoiceService
{

        public static function bulkCreate($data){
            $item = array();
            $customer = Customer::query()->findOrFail($data->customer_id);
            // foreach(json_decode($data->products) as $order){
            $item[] = [
                'invoice' => $data->id,
                'recipient_name' => $customer->name ? $customer->name : 'N/A',
                'recipient_address' => $customer->address ? $customer->address : 'N/A',
                'recipient_phone' => $customer->phone ? $customer->phone : '',
                'cod_amount' => $data->due + (request()->shipping_cost ?? 0),
                'note' => $data->note ?? '',
                ];
            // }


            $steadfast = new Steadfast();

            $result = $steadfast->bulkCreate(json_encode($item));
            return $result;
        }

        public static function invoiceStore(){
            $request = request();
            $profit = 0;
            $current_date = Carbon::today();
            $invoice_count = Invoice::whereDate('created_at', $current_date)->count() + 1;
            $products = $request['products'];
            foreach ($products as $key => $product) {
                $batch = Batch::find($product['batch_id']);
                $batch->rem_quantity -= $product['quantity'];
                $batch->update();
                $profit += $product['total'] - ($batch->purchase_price * $product['quantity']);
            };

            // store invoice
            $invoice = new Invoice();
            $invoice->invoice_no = Carbon::now()->format('dmY') . '-' . $invoice_count;
            $invoice->customer_id = $request->customer_id;
            $invoice->products = json_encode($request->products);
            $invoice->total = $request->total;
            $invoice->due = $request->due_amount;
            $invoice->status = $request->status;
            $invoice->profit = $profit;
            $invoice->save();
            return $invoice;
        }

        public static function shippingCreate($payload){
            $request  = request();
            $info     = $payload->data[0];
            $shipping = new Shipping();
            $shipping->consignment_id = $info->consignment_id ?? null;
            $shipping->tracking_code = $info->tracking_code ?? null;
            $shipping->total = $request->total ?? 0.0;
            $shipping->paid = $request->total - $request->due_amount;
            $shipping->due = $request->due_amount ?? 0.0;
            $shipping->cod_amount = $info->cod_amount ?? null;
            $shipping->recipient_name = $info->recipient_name ?? null;
            $shipping->recipient_phone = $info->recipient_phone ?? null;
            $shipping->recipient_address = $info->recipient_address ?? null;
            $shipping->status = 'pending';
            $shipping->shipping_cost = $request->shipping_cost ?? 0;
            $shipping->invoice_no = $info->invoice_no ?? null;
            $shipping->save();
            return $shipping;
        }


        public static function changeShippingStatus(){
            $orders = Invoice::query()->get();
            $steadfast = new Steadfast();
            foreach($orders as $info){
                if($info->shipping){
                    $status =  $steadfast->getStatusByCid($info);
                    $info->shipping->update(['shipping_status'=>$status->delivery_status]);
                }
            }
        }

        public static function invoicePayment($invoice){
            $request = request();
            if($request->paid_amount || $request->status == 'paid'){
                $data = [];
                $data['paid_amount'] = $request->paid_amount;
                $data['invoice_id']         = $invoice->id;
                $data['date']               = $request->date ??  Carbon::now();
                $data['payment_type']       = $request->payment_type;
                $data['note']               = $request->note ?? '';
                $payment =  InvoicePayment::query()->create($data);
            }else{
                $payment = null;
            }
            return $payment;

    }
}
