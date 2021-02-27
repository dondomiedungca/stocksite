@extends('admin.index')

@section('body')
    <purchasing-details :purchase_order_details="{{ $purchase_order }}"></purchasing-details>
@endsection