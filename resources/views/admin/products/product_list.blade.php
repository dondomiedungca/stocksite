@extends('admin.index')

@section('body')
    <product-lists :product_types="{{ $product_types }}" :currency="{{ $currency }}" :statuses="{{ $statuses }}" :cosmetics="{{ $cosmetics }}"></product-lists>
@endsection