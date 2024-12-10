@extends('layouts.layout')

@section('title', 'Edit {{ $color->name }}')

@push('css')
@endpush

@section('main')
<form method="POST" action="{{ route('colors.update', $color->id) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="row">
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
                    <h5>Edit {{ $color->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Color Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $color->name }}">
                    </div>
                    <button type="submit" class="btn  btn-primary">Update Color</button>
                </div>
            </div>
        </div>
        <div class="col-4">

        </div>
</form>
</div>
@endsection

@push('script')
@endpush
