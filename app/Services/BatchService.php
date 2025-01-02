<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\BatchPayment;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BatchService
{

        public static function getBatch(){
            return Batch::with(['product' => function ($query) {
                $query->withTrashed();
            }])->get();
        }

        public static function batchStore($payload){
            $request = request();
            $batch = new Batch();
            $batch->batch_no = 'batch-' . Str::random(5) . '-' . Carbon::now()->format('D-M-Y');
            $batch->product_id = $request->product_id;
            $batch->quantity = $request->quantity;
            $batch->rem_quantity = $request->quantity;
            $batch->purchase_price = $request->purchase_price;
            $batch->sell_price = $request->sell_price;
            $batch->supplier_id = $request->supplier_id;
            $batch->total_purchase_cost = $request->total_purchase_cost;
            $batch->due_amount = $request->due_amount;
            $batch->status = $request->status;
            $batch->save();
            return $batch;
        }

        public static function batchUpdate($batch){
            $request = request();
            if ($request->type === 'adjust_payment') {
                $request->validate([
                    'status' => 'required',
                    'due_amount' => 'required'
                ]);

                $batch->status = $request->status;
                $batch->due_amount = $request->due_amount;
            } else {

                if ($batch->status == 'paid' && $request->status == 'partial') {
                    $batch->status = 'partial';
                } elseif ($batch->status == 'paid' && $request->status == 'due') {
                    $batch->status = 'partial';
                } elseif ($batch->status == 'due' && $request->status == 'paid') {
                    $batch->status = 'partial';
                }

                $batch->quantity = $batch->quantity + $request->quantity;
                $batch->total_purchase_cost = $batch->total_purchase_cost + $request->total_purchase_cost;
                $batch->due_amount = $request->due_amount + $batch->due_amount;
            }
            $payment = BatchService::bacthPayment($batch);
            return $batch->update();
        }


        public static function bacthPayment($batch){
                $request = request();
                if($request->paid_amount || $request->status == 'paid'){
                    $data = [];
                    if ($request->status          == 'partial') {
                        $data['paid_amount'] = $request->paid_amount;

                    } elseif ($request->status == 'paid') {
                        $data['paid_amount'] = $request->total_purchase_cost;
                    } elseif ($request->status   == 'due') {
                        $data['paid_amount'] = $request->paid_amount;
                    }

                    $data['batch_id']           = $batch->id;
                    $data['date']               = $request->date ??  Carbon::now();
                    $data['payment_type']       = $request->payment_type;
                    $data['note']               = $request->note ?? '';
                    $payment =  BatchPayment::query()->create($data);
                }else{
                    $payment = null;
                }
                return $payment;

        }
}
