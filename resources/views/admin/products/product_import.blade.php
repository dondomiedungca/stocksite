@extends('admin.index')

@section('body')
    <import-product stock_number="{{ $stock_number }}" :cosmetics="{{ $cosmetics }}" :statuses="{{ $statuses}}" :product_types="{{ $product_types }}"></import-product>
@endsection