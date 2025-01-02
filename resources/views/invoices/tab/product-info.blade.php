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
                <div class="form-group">
                    {{-- @foreach ($batches as $product)
                {{ dd($product->product) }}
                @endforeach --}}

                    <label for="products">Select Product(s)</label>
                    <br>
                    <select class="form-control js-example-basic-single" id="products" x-ref="product">
                        <option></option>
                        @foreach ($batches as $product)
                            <option
                                product_name="{{ $product->product->name }} ({{ $product->product->color->name ?? '' }},{{ $product->product->size->name ?? '' }})"
                                product_price="{{ $product->sell_price }}" value="{{ $product->id }}">
                                {{ $product->product->name }}
                                ({{ $product->product->color->name ?? '' }},{{ $product->product->size->name ?? '' }})
                                -
                                <span>Purchase Price: {{ $product->purchase_price }} tk/-</span>
                            </option>
                        @endforeach
                    </select>
                </div>
                <table class="invoice-table" id="invoiceTable" x-ref="invoiceTable">
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Sell Price</th>
                        <th>Sub Total</th>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>
