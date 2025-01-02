@extends('layouts.layout')

@section('title','Brands')

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
        <h5>Supplier - <span class="text-success">{{ $supplier->name }}</span> - Report</h5>
    </div>
    <div class="card-body">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th width="10">#</th>
                    <th>Batch No</th>
                    <th>Qty</th>
                    <th>Reminder Qty</th>
                    <th>Total Amount</th>
                    <th>Total Due</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($supplier->batch as $key => $info)
                <tr class="table-info">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $info->batch_no }}</td>
                    <td>{{ $info->quantity }} pitch</td>
                    <td>{{ $info->rem_quantity }}</td>
                    <td>{{ $info->total_purchase_cost }}</td>
                    <td>{{ $info->due_amount }}</td>
                    <td>
                            <a class="text-info" href="{{ url('batches/'.$info->id.'/edit?type=adjust_payment') }}"><i
                                class="feather icon-edit"></i> Adjust Payment</a>

                    </td>
                </tr>
                @endforeach

            </tbody>
            <tfoot>
                <tr>

                    <td colspan="5" class="text-right"></td>
                    <td colspan="2">
                        <table style="width: 100%;border:none">
                            <tr>
                                <td>Total Amount</td>
                                <td>{{ $supplier->batch->sum('total_purchase_cost') }}</td>
                            </tr>

                            <tr>
                                <td>Total Paid</td>
                                <td>{{ $supplier->batch->sum('total_purchase_cost') - $supplier->batch->sum('due_amount') }}</td>
                            </tr>

                            <tr>
                                <td>Total Due</td>
                                <td>{{ $supplier->batch->sum('due_amount') }}</td>
                            </tr>

                        </table>
                    </td>
                    {{-- <td  class="">{{ $supplier->batch->sum('total_purchase_cost') }}</td>
                    <td>{{ $supplier->batch->sum('due_amount') }}</td> --}}
                </tr>
                {{-- <tr>
                    <th width="10">#</th>
                    <th>Batch No</th>
                    <th>Qty</th>
                    <th>Reminder Qty</th>
                    <th>Total Amount</th>
                    <th>Total Due</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr> --}}
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
    $(document).ready(function () {
        $('#example').DataTable();
    });


    function deleteFunction (id) {

        JSAlert.confirm("This cant be restored.", "Sure you want to delete ?", JSAlert.Icons.Deleted).then(function(result) {

        // Check if pressed yes
        if (!result)
        return;

        // User pressed yes!
        $('#deleteForm_'+id).submit();
        JSAlert.alert("Supplier deleted!");

        });

    }
</script>


@endpush
