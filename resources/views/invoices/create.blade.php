@extends('layouts.layout')
@section('title', 'Create a new invoice.')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 2px solid rgba(0, 0, 0, 0.15);
            border-radius: 0px;
            height: 49px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 45px;
            margin-left: 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px;
            right: 5px;
        }

        .invoice-table {
            width: 90%;
            margin-bottom: 10px;
        }

        .invoice-table td {
            padding: 4px;
        }

        .invoice-table th {
            padding-left: 10px;
        }

        .invoice-input {
            padding-left: 7px;
            margin: 7px 0 7px 7px;
            height: 35px;
        }

        .invoice-close {
            padding: 0 5px !important;
            border: 2px solid darksalmon !important;
            border-radius: 50%;

        }

        .invoice-close:focus-visible {
            border: none !important;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush


@section('main')
    <form method="POST" action="{{ route('invoices.store') }}" x-effect="calculateTotal()" x-data="{
        removeRow: function(i) {
            this.$refs['row-' + i].remove();
            let rowCount = this.$refs.invoiceTable.rows.length;
            this.$refs.product.value = null;

            if (rowCount == 1) {
                this.$refs.createInvoiceBtn.style.display = 'none';
            }

            this.$refs.paid.value = 0;
            this.calculateTotal();
        },

        setStatus: function() {
            let total_amount = $('#total').val();
            let paid_amount = $('#paid').val();
            let due_amount = $('#due_amount').val();

            if (total_amount) {
                if (due_amount == total_amount) {
                    $('#status').val('due');
                    $('#status').trigger('change');
                } else if (due_amount == 0) {
                    $('#status').val('paid');
                    $('#status').trigger('change');
                } else {
                    $('#status').val('partial');
                    $('#status').trigger('change');
                }
            }
        },

        calculateTotal: function(refs) {
            let total = 0;
            let inputFields = document.querySelectorAll('input[id^=\'subTotal-\']');
            inputFields.forEach(input => {
                total += parseFloat(input.value);
            });
            this.$refs.total.value = total;
            this.$refs.paid.value = null;
            this.$refs.due.value = this.$refs.total.value;

            this.setStatus();
        },

        calculateSubTotal: function(i) {
            if (i) {
                let price = parseFloat(this.$refs['price-' + i].value);
                let quantity = parseInt(this.$refs['quantity-' + i].value);
                let subTotalAmount = quantity * price;
                this.$refs['subTotal-' + i].value = subTotalAmount;
            }
            this.calculateTotal();
        },
    }">
        @csrf
        <div class="row">
            <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Create Invoice</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            {{-- @foreach ($batches as $product)
                        {{ dd($product->product) }}
                        @endforeach --}}

                            <label for="products">Select Product(s)</label>
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
                        <div class="row">
                            <div class="col-10">
                                <div class="form-group ajax-customer">
                                    <label for="customers">Customer</label>
                                    <select class="form-control js-example-basic-single" name="customer_id" id="customers"
                                        required>
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
                                    <input type="number" class="form-control" id="paid" x-ref="paid"
                                        placeholder="Paid Amount"
                                        x-on:input="$refs.due.value = $refs.total.value - $refs.paid.value"
                                        x-on:blur="setStatus()">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Due<small class="text-info">[Taka]</small></label>
                                    <input type="number" class="form-control" id="due_amount" x-ref="due"
                                        name="due_amount" placeholder="Due Amount"
                                        x-on:input="$refs.paid.value = $refs.total.value - $refs.due.value"
                                        x-on:blur="setStatus()">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="status">Payment Status<small class="text-info">[Taka]</small></label> <br>
                                    <select name="status" id="status" x-ref="status" class="form-control" required>
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
                    </div>
                </div>
            </div>


            <div class="col-md-8 col-lg-8 col-sm-12">
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
                        <table class="invoice-table" id="invoiceTable" x-ref="invoiceTable">
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Sell Price</th>
                                <th>Sub Total</th>
                            </tr>
                        </table>
                        <button x-ref="createInvoiceBtn" id="createInvoiceBtn" style="display: none;" type="submit"
                            class="btn  btn-primary btn-sm show btn-block">Create
                            Invoice</button>
                    </div>
                </div>
            </div>


        </div>
    </form>



    {{-- Add Customer modal --}}
    <!-- Modal Structure -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="customerForm">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div id="errorMessages" class="alert alert-danger d-none">
                                        <ul></ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter Supplier's Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" >
                                            <small>Format: 01XXX-XXXXXX</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="demo@mail.com">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea name="address" id="address" cols="30" rows="3" class="form-control"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Customer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection

<style>
    /* Media Query for Mobile Devices */
    @media (max-width: 768px) {
        .invoice-input.p_name{
            width: 120px;
        }
        .invoice-input.quantity {
            max-width: 40px;
        }

        .invoice-table th {
            padding-left: 10px;
            font-size: 10px;
        }
        .price{
            max-width: 50px;
        }
        .sub_total{
            max-width: 50px !important;
        }
    }
    @media (min-width: 1024px) {
        .invoice-input.p_name{
            width: 220px;
        }
        .invoice-input.quantity {
            max-width: 130px;
        }


        .price{
            max-width: 150px;
        }
        .sub_total{
            max-width: 150px!important;
        }
    }
</style>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#customerForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Clear previous error messages
                $('#errorMessages').addClass('d-none').find('ul').empty();

                // Gather form data
                let formData = {
                    _token: $('input[name="_token"]').val(), // CSRF Token
                    name: $('#name').val(),
                    phone: $('#phone').val(),
                    email: $('#email').val(),
                    address: $('#address').val()
                };

                // AJAX POST request
                $.ajax({
                    url: "{{ route('customers.store') }}", // Laravel route
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle success response
                        $('#customerForm')[0].reset(); // Clear the form
                        $('#exampleModal').modal('hide'); // Hide the modal
                        $('.ajax-customer').html(response.customer); // Hide the modal

                    },
                    error: function(xhr) {
                        // Handle validation errors
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                $('#errorMessages')
                                    .removeClass('d-none')
                                    .find('ul')
                                    .append('<li>' + errors[field][0] + '</li>');
                            }

                            // Ensure the modal stays open
                            $('#exampleModal').modal('show');
                        } else {

                        }
                    }
                });
            });

            // Ensure modal opens initially if validation errors exist on page load
            @if ($errors->any())
                $('#exampleModal').modal('show');
            @endif
        });
    </script>

    <script>
        function calculateTotal() {
            let total = 0;
            let inputFields = document.querySelectorAll('input[id^=\'subTotal-\']');
            inputFields.forEach(input => {
                total += parseFloat(input.value);
            });
            $('#total').val(total);
            $('#paid').val(null);
            let due_amount = $('#due_amount').val();
            due_amount = $('#total').val();
        }

        function checkRow() {
            let rowCount = $('#invoiceTable').find('tr').length
            if (rowCount <= 1) {
                $('#createInvoiceBtn').hide();
                $('#products').val(null);
            } else {
                $('#createInvoiceBtn').show();
            }

            if (rowCount == 2) {
                let firstProductPrice = $('#price-1').val()
                $('#total').val(firstProductPrice);
                $('#due_amount').val(firstProductPrice);
                $('#status').val('due').change();
            }

            if (rowCount == 3) {
                let firstProductPrice = $('#price-1').val()
                $('#total').val(firstProductPrice);
                $('#due_amount').val(firstProductPrice);
                $('#status').val('due').change();
            }
        }


        $(document).ready(function() {

            let i = 0;
            $('#products').select2({
                placeholder: "Please select a product",
                containerCssClass: "form-control-sm",
            }).on('change', function(event) {

                let product_name = $('#products option:selected').attr('product_name');
                let product_price = $('#products option:selected').attr('product_price');
                let batch_id = $('#products').val();
                i += 1;



                $('#invoiceTable').append(`<tr id="row-${i}" x-ref="row-${i}" class="border-bottom border-dark single-product">
                <input type="hidden" name="products[${i}][batch_id]" value="${batch_id}">
                <td><input class="invoice-input p_name" type="text" name="products[${i}][name]" value="${product_name}" readonly></td>
                <td>
                    <input x-ref="quantity-${i}" class="invoice-input quantity" type="number" name="products[${i}][quantity]" x-on:input="calculateSubTotal(${i})" min="1" value="1" required>
                </td>
                <td>
                    <input id="price-${i}" x-ref="price-${i}" class="invoice-input price" type="number" name="products[${i}][price]" value="${product_price}" x-on:input="calculateSubTotal(${i})" required>
                </td>
                <td>
                    <input id="subTotal-${i}" x-ref="subTotal-${i}" class="invoice-input sub_total" type="number" name="products[${i}][total]" required value="${product_price}">
                </td>
                <td>
                    <button @click="removeRow(${i})" type="button" class="close invoice-close" aria-label="Close">
                        <span class="text-danger" aria-hidden="true">&times;</span>
                    </button>
                </td>
            </tr>`);

                checkRow();

                calculateTotal();

            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $('#customers').select2({
                placeholder: "Select a customer",
            });

            $('#status').select2({
                placeholder: "Payment Status",
            });
        });
    </script>
@endpush
