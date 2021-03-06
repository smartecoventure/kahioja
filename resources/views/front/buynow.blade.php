@extends('layout.template')

@section('main')
    <!-- Banner -->
    <div id="banner" class="relative top-36 md:top-0 mt-6 md:px-14 px-4">
        <buynow-component
            :productid="{{ json_encode($product->id) }}" 
            :productphoto="{{ json_encode($product->photo) }}" 
            :productname="{{ json_encode($product->name) }}" 
            :productquantity="{{ json_encode($quantity) }}" 
            :productprice="{{ json_encode($product->price) }}" 
            :deliveryfee="{{ json_encode($product->ship_fee) }}"
        ></buynow-component>
    </div>
@endsection