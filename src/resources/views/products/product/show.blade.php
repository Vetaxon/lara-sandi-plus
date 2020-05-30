@extends('layout.main')
@section('title', 'Welcome to the task')
@section('content')
    <div class="container m-5">
        <div class="container m-3 mt-lg-5 ">
            <h1>{{ $product['sku'] }}</h1>
            <h3>{{ $product['product_attributes']['name'] }}</h3>
            <h3>PRICE: {{ $product['price'] }}</h3>
            <h3>{{ $product['product_attributes']['description'] }}</h3>
            @foreach($product['product_characteristics'] as $item)
                <p>{{$item['name']}}: {{$item['value']}}</p>
            @endforeach
        </div>
    </div>
@endsection
