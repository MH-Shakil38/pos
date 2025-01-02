@extends('layouts.layout')

@section('title', 'Create A New Size')

@push('css')
@endpush

@section('main')
<form method="POST" action="{{ route('sizes.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8 col-sm-12">
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
                    <h5>Add a new size</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Size Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Size Name Ex. Samsung">
                    </div>
                    <button type="submit" class="btn  btn-primary">Add Size</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">

        </div>
</form>
</div>
@endsection

@push('script')
@endpush
