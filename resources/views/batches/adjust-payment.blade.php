
<form method="POST" action="{{ url('batches/'.$batch->id.'?type=adjust_payment') }}">
    @csrf
    @method('PUT')
    <div class="row" x-effect="total_purchase_cost = (quantity * purchase_price) + {{ $batch->total_purchase_cost }}"
        x-data="{
        total_purchase_cost: {{ $batch->total_purchase_cost }},
        status: '{{ $batch->status }}',
        paid: false,
        payable_amount: 0,
        due_amount: {{ $batch->due_amount }},
    }">
        <div class="col-8">
            <div class="card">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="card-header">
                    <h5>Ajust Payment: <span class="badge badge-info">{{ $batch->batch_no
                            }}</span></h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="status">Payment Status:</label>
                        <select name="status" id="status" class="form-control" x-model="status"
                            x-on:change="paid = (status === 'paid' || status ==='due') ? true : false, due_amount = (status === 'paid') ? 0 : {{ $batch->due_amount }}, payable_amount = (status === 'paid') ? {{ $batch->due_amount }} : '' ">
                            <option disabled value="">Payment Status</option>
                            @foreach(['paid', 'partial'] as $option)
                            <option value="{{ $option }}">{{ ucfirst($option)
                                }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paid_amount">Payable Amount <small class="text-info">[Taka]</small></label>
                        <input type="number" class="form-control" id="paid_amount" name="paid_amount" placeholder="Paid Amount"
                            x-bind:disabled="paid" x-model.number="payable_amount"
                            x-on:keyup="due_amount = {{ $batch->due_amount }} - payable_amount"
                            max="{{ $batch->due_amount }}">

                        <input type="hidden" name="due_amount" x-model.number="due_amount">
                    </div>
                    <h6 class="text-info">Due Amount: <span class="text-danger" x-text="due_amount"></span> taka</h6>

                    <div class="form-group">
                        <label for="payment_type">Payment Type <small class="text-info">[Taka]</small></label>
                        <select name="payment_type" id="" class="form-control">
                            <option value="Cash">Cash</option>
                            <option value="Mobile Banking">Mobile Banking</option>
                            <option value="Bank">Bank</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paid_amount">Payable Amount <small class="text-info">[Taka]</small></label>
                        <textarea name="note" id="" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn  btn-primary">Adjust Payment</button>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h5>Batch <span class="badge badge-info">{{ $batch->batch_no }}</span> Details:</h5>
                </div>
                <div class="card-body">
                    <table>
                        <tr>
                            <th>Product</th>
                            <td>:</td>
                            <td>{{ $batch->product->name }}</td>
                        </tr>
                        <tr>
                            <th>Quantity</th>
                            <td>:</td>
                            <td>{{ $batch->quantity }}</td>
                            <td class="text-info">Pitch</td>
                        </tr>
                        <tr>
                            <th>Unit Price</th>
                            <td>:</td>
                            <td>{{ $batch->purchase_price }}</td>
                            <td class="text-info">taka</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>:</td>
                            <td>{{ ucfirst($batch->status) }}</td>
                            <td class="text-info">--</td>
                        </tr>
                        <tr class="text-info">
                            <th>Total Amount</th>
                            <td>:</td>
                            <td>{{ $batch->total_purchase_cost }}</td>
                            <td class="text-info">taka</td>
                        </tr>
                        <tr class="text-success">
                            <th>Paid Amount</th>
                            <td>:</td>
                            <td>{{ $batch->total_purchase_cost - $batch->due_amount }}</td>
                            <td class="text-info">taka</td>
                        </tr>
                        <tr class="text-danger">
                            <th>Due Amount</th>
                            <td>:</td>
                            <td>{{ $batch->due_amount }}</td>
                            <td class="text-info">taka</td>
                        </tr>
                    </table>
                </div>
            </div>

            @include('batches.payment-history')
        </div>
</form>
</div>
