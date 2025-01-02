@extends('layouts.layout')
@section('title', 'Edit batch {{ $batch->batch_no }}')

@push('css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        table tr th,
        td {
            padding: 5px;
        }
    </style>
@endpush


@section('main')
    @if ($type == 'adjust_payment')
        @include('batches.adjust-payment')
    @else
        @include('batches.edit-form')
    @endif
@endsection

@push('scripts')
@endpush
