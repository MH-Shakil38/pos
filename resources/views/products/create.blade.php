@extends('layouts.layout')
@section('title','Add a new product')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css'>
<style>
    .select2-container--default .select2-selection--multiple {
        border-radius: 0px !important;
        border: 2px solid rgba(0, 0, 0, 0.15) !important;
        padding: 8px 5px 13px 15px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #1abc9c;
        border-radius: 0px;
        border: 1px solid #000000;
        color: #ffffff;
        padding-left: 40px;
        padding-right: 15px;
    }


    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        background-color: #fff;
        border: none;
        border-radius: 0;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover,
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:focus {
        background-color: #ff6161;
        color: #ffffff;
    }
</style>
@endpush


@section('main')
<form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8 col-lg-8 col-sm-12">
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
                    <h5>Add a new product</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Product Name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Add A Brief Description</label>
                        <textarea name="description" class="form-control" id="exampleFormControlTextarea1"
                            rows="4">{{ old('description') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Select A Brand</label>
                                <select class="form-control" id="exampleFormControlSelect1" name="brand_id">
                                    {{-- <option selected disabled>Please select a brand</option> --}}
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id ?? old('brand_id') }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Select A Color</label>
                                <select class="form-control" id="exampleFormControlSelect1" name="color_id">
                                    <option selected disabled>Please select a Color</option>
                                    @foreach ($colors as $color)
                                    <option value="{{ $color->id  ?? old('color_id')}}">{{ $color->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Select A Size</label>
                                <select class="form-control" id="exampleFormControlSelect1" name="size_id">
                                    <option selected disabled>Please select a Size</option>
                                    @foreach ($sizes as $size)
                                    <option value="{{ $size->id  ?? old('size_id')}}" >{{ $size->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="categories">Select Categories</label> <br>
                        <select class="form-control js-examples-basic-multiple" name="categories[]" id="categories"
                            multiple resolve>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id  ?? old('categories')}}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <input type="file" class="dropify form-control" id="image" name="image">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn  btn-primary form-control" >Add</button>
                    </div>
                </div>
            </div>
        </div>
</form>
</div>
@endsection

@push('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js'></script>
<script src="./script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" />
<script>
    $(document).ready(function() {
        $('.js-examples-basic-multiple').select2({
            placeholder: "Select categories.",
        });

        $('.dropify').dropify();
    });
</script>
@endpush
