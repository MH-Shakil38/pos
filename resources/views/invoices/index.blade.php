@extends('layouts.layout')

@section('title', 'Brands')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <style>
        .jsalertWindow>* {
            font-family: "Open Sans", sans-serif !important;
            text-transform: capitalize;
        }

        .jsalertImg {
            max-height: none !important;
            width: 25%;
            padding: 10px !important;
            border: 1px solid black;
            border-radius: 50%;
            margin: 34px auto !important;
            font-family: "Open Sans", sans-serif !important;
        }
    </style>
@endpush

@section('main')
    <div class="card">
        <div class="card-header">
            <h5>Brands</h5>
        </div>
        <div class="card-body">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice No</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Due</th>
                        <th>Paid</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $key => $invoice)
                        <tr class="table-info">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $invoice->invoice_no }}</td>
                            <td>{{ $invoice->customer->name }}</td>
                            <td>{{ $invoice->total }}</td>
                            <td style="background: {{ isset($invoice->shipping->status) && $invoice->shipping->status == 'Return' ? '#ff000045' : '' }};">

                                <a href="javascript:{}" class="text-info" data-toggle="modal" data-target="#statusModal{{ $invoice->id }}"><i
                                        class="feather icon-edit"></i>
                                    {{ isset($invoice->shipping->status) ? Str::ucfirst($invoice->shipping->status) : '' }}</a><br>
                                <a target="_blank" class="text-info"
                                    href="{{ isset($invoice->shipping->consignment_id) ? 'https://steadfast.com.bd/user/consignment/' . $invoice->shipping->consignment_id : 'javascript:{}' }}"><i
                                        class="feather icon-edit"></i>
                                    {{ Str::ucfirst($invoice->shipping?->shipping_status) }}</a><br>
                                <a class="text-danger" href="javascript:{}"><i class="feather icon-trash"></i>
                                    {{ Str::ucfirst($invoice->status) }}</a>
                                <form method="POST" id="deleteForm_{{ $invoice->id }}"
                                    action="{{ route('invoices.destroy', $invoice->id) }}">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </td>
                            {{-- change status modal --}}
                            <!-- Modal Structure -->
                            <div class="modal fade" id="statusModal{{ $invoice->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add New Customer</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @if (isset($invoice->shipping->id))
                                                <form action="{{ route('shippings.update', $invoice->shipping->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                @else
                                                    <form action="{{ route('shippings.store') }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('POST')
                                            @endif
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div id="errorMessages" class="alert alert-danger d-none">
                                                            <ul></ul>
                                                        </div>
                                                        <input type="hidden" name="invoice_no" value="{{ $invoice->invoice_no }}">
                                                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label for="name">Order Status</label>
                                                                <select id="" class="form-control" name="status">
                                                                    <option {{ isset($invoice->shipping->id) && $invoice->shipping->status == 'Pending' ? 'selected' : '' }} value="Pending">Pending</option>
                                                                    <option {{ isset($invoice->shipping->id) && $invoice->shipping->status == 'Shipping To Courier' ? 'selected' : '' }} value="Shipping To Courier">Shipping To Courier</option>
                                                                    <option {{ isset($invoice->shipping->id) && $invoice->shipping->status == 'Success' ? 'selected' : '' }} value="Success">Success</option>
                                                                    <option {{ isset($invoice->shipping->id) && $invoice->shipping->status == 'Return' ? 'selected' : '' }} value="Return">Return</option>
                                                                </select>
                                                            </div>

                                                            <button type="submit" class="btn btn-primary">Add
                                                                Customer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </form>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- change status modal --}}

                            <td>{{ $invoice->due }}</td>
                            <td>{{ $invoice->total - $invoice->due }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/y') }}</td>
                            <td>
                                <a target="_blank" class="text-info" href="{{ route('invoices.show', $invoice->id) }}"><i
                                        class="feather icon-eye"></i> View</a><br>
                                <a target="_blank" class="text-info" href="{{ route('invoices.edit', $invoice->id) }}"><i
                                        class="feather icon-edit"></i> Adjust Payment</a><br>
                                <a class="text-danger" href="javascript:{}"
                                    onclick="deleteFunction({{ $invoice->id }})"><i class="feather icon-trash"></i>
                                    Cancel invoice</a>
                                <form method="POST" id="deleteForm_{{ $invoice->id }}"
                                    action="{{ route('invoices.destroy', $invoice->id) }}">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Invoice No</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Due</th>
                        <th>Paid</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/jsalert.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });


        function deleteFunction(id) {

            JSAlert.confirm("This cant be restored.", "Sure you want to delete ?", JSAlert.Icons.Deleted).then(function(
                result) {

                // Check if pressed yes
                if (!result)
                    return;

                // User pressed yes!
                $('#deleteForm_' + id).submit();
                JSAlert.alert("Batch deleted!");

            });

        }
    </script>
@endpush
