<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="card" style="margin-bottom:70px">
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
                <h5>Products Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group ajax-customer">
                                    <label for="customers">Customer</label>
                                    <br>
                                    <select class="form-control js-example-basic-single" name="customer_id"
                                        style="width: 100%" id="customers" required>
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
                                    <a class="btn btn-primary form-control" data-toggle="modal"
                                        data-target="#exampleModal">
                                        <i class="feather icon-plus"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Currier Number</label>
                            <input type="text" class="form-control" name="currier_number">
                        </div>
                    </div>
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
                </div>

                <button x-ref="createInvoiceBtn" id="createInvoiceBtn" style="display: none;" type="submit"
                    class="btn  btn-primary btn-sm show btn-block">Create
                    Invoice</button>
            </div>
        </div>
    </div>
</div>
