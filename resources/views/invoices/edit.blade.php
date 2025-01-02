@extends('layouts.layout')
@section('title','Create a new invoice.')

@push('css')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
{{-- {{ dd($invoice) }} --}}

@section('main')
<form method="POST" action="{{ route('invoices.update',$invoice->id) }}" x-data="{
    total_due: {{ $invoice->due }},
    total: {{ $invoice->total }},
    due: null,
    paid: null,
    setStatus: function(){
        if(this.due == 0){
            this.$refs.status.value = 'paid';
        }else{
            this.$refs.status.value = 'partial';
        }
    }
}">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h5>Create Invoice</h5>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="total">Total <small class="text-info">[Taka]</small></label>
                        <input type="number" class="form-control" id="total" name="total" x-model="total" readonly>
                    </div>

                    <div class="form-group">
                        <label for="total_due">Total Due <small class="text-info">[Taka]</small></label>
                        <input type="number" class="form-control" id="total_due" name="total_due" x-model="total_due" readonly>
                    </div>
                    <div class="form-group">
                        <label for="paid">Pay Now <small class="text-info">[Taka]</small></label>
                        <input type="number" class="form-control" id="paid" name="paid_amount" x-model="paid"
                            x-on:input="due = total_due - paid" x-on:blur="setStatus()">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Remaining Due<small class="text-info">[Taka]</small></label>
                        <input type="number" class="form-control" id="due_amount" name="due_amount" x-model="due"
                            x-on:input="paid = total_due - due" x-on:blur="setStatus()">
                    </div>
                    <div class="form-group">
                        <label for="status">Payment Status<small class="text-info"></small></label>
                        <select name="status" id="status" x-ref="status" class="form-control" required readonly>
                            <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="partial" {{ $invoice->status == 'partial' ? 'selected' : '' }}>Partial
                            </option>
                            <option value="due" {{ $invoice->status == 'due' ? 'selected' : '' }}>Due</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="payment_type">Payment Type <small class="text-info"></small></label>
                        <select name="payment_type" id="" class="form-control">
                            <option value="Cash">Cash</option>
                            <option value="Mobile Banking">Mobile Banking</option>
                            <option value="Bank">Bank</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="note">Note <small class="text-info"></small></label>
                        <textarea name="note" id="" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn  btn-primary show btn-block">Adjust Invoice</button>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h5>Products Details</h5>
                </div>
                <div class="card-body">
                    <table>
                        <tbody>
                            <tr>
                                <th>Invoice NO:</th>
                                <td>{{ $invoice->invoice_no }}</td>
                            </tr>
                            <tr>
                                <th>Customer Name:</th>
                                <td>{{ $invoice->customer->name }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount:</th>
                                <td>{{ $invoice->total }}</td>
                            </tr>
                            <tr>
                                <th>Paid:</th>
                                <td>{{ $invoice->total - $invoice->due }}</td>
                            </tr>

                            <tr>
                                <th>Due:</th>
                                <td>{{ $invoice->due }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @forelse ($invoice->payment as $info)
            <div class="card">

                <div class="card-body">
                    <table>
                        <tbody>

                            <tr>
                                <th>Date</th>
                                <td>:</td>
                                <td >{{ Carbon\Carbon::parse($info->created_at)->format('d M y') }}</td>
                            </tr>
                            <tr>
                                <th>Paid</th>
                                <td>:</td>
                                <td>{{ $info->paid_amount }}</td>
                                <td>tk</td>
                            </tr>

                            <tr>
                                <th>Payment Type</th>
                                <td>:</td>
                                <td >{{ $info->payment_type }}</td>
                            </tr>

                            <tr>
                                <th>Note</th>
                                <td>:</td>
                                <td >{{ $info->note }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @empty

            @endforelse
        </div>
    </div>
</form>
@endsection

@push('scripts')
@endpush
