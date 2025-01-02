@extends('layouts.layout')

@section('title', 'Edit {{ $size->name }}')

@push('css')
@endpush

@section('main')
<form method="POST" action="{{ route('sizes.update', $size->id) }}" enctype="multipart/form-data">
    @method('PUT')
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
                    <h5>Edit {{ $size->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Size Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $size->name }}">
                    </div>
                    <button type="submit" class="btn  btn-primary">Update Size</button>
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
