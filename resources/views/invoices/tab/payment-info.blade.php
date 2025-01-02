<div class="col-md-4 col-lg-4 col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5>Create Invoice</h5>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-10">
                    <div class="form-group ajax-customer">
                        <label for="customers">Customer</label>
                        <br>
                        <select class="form-control js-example-basic-single" name="customer_id" id="customers"
                            style="width: 100%" required>
                            <option></option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} -
                                    ({{ $customer->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-2 " style="padding: 0px; margin-top: 30px;color:#fff">
                    <div class="form-group">
                        <a class="btn btn-primary form-control" data-toggle="modal" data-target="#exampleModal">
                            <i class="feather icon-plus"></i> </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="total">Total <small class="text-info">[Taka]</small></label>
                        <input type="number" class="form-control" id="total" x-ref="total" name="total"
                            placeholder="Total Purchase Cost">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="paid">Paid <small class="text-info">[Taka]</small></label>
                        <input type="number" class="form-control" id="paid" name="paid_amount" x-ref="paid"
                            placeholder="Paid Amount"
                            x-on:input="$refs.due.value = $refs.total.value - $refs.paid.value" x-on:blur="setStatus()">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Due<small class="text-info">[Taka]</small></label>
                        <input type="number" class="form-control" id="due_amount" x-ref="due" name="due_amount"
                            placeholder="Due Amount" x-on:input="$refs.paid.value = $refs.total.value - $refs.due.value"
                            x-on:blur="setStatus()">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="status">Payment Status<small class="text-info">[Taka]</small></label> <br>
                        <select name="status" id="status" x-ref="status" class="form-control" style="width:100%"
                            required>
                            <option></option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partial</option>
                            <option value="due">Due</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Shipping Cost<small class="text-info">[Taka]</small></label>
                        <select name="shipping_cost" class="form-control" required>
                            <option value="0">Free Shipping</option>
                            <option value="80">80</option>
                            <option value="150">150</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_type">Payment Type <small class="text-info">[Taka]</small></label>
                        <select name="payment_type" id="" class="form-control">
                            <option value="Cash">Cash</option>
                            <option value="Mobile Banking">Mobile Banking</option>
                            <option value="Bank">Bank</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                <div class="form-group">
                    <label for="note">Note <small class="text-info">[Taka]</small></label>
                    <textarea name="note" id="" class="form-control" cols="30" rows="3"></textarea>
                </div>
                </div>
            </div>
            <button x-ref="createInvoiceBtn" id="createInvoiceBtn" style="display: none;" type="submit"
                class="btn  btn-primary btn-sm show btn-block">Create
                Invoice</button>
        </div>
    </div>

</div>
