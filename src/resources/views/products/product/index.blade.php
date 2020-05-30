@extends('layout.main')
@section('title', 'Products list')
@section('content')
    <div class="container m-3 mt-lg-5 ">
        <h1 class="text-center">Products</h1>

        <div class="container m-5">
            <button type="button"
                    class="btn btn-primary"
                    id="upload-products"
                    data-upload="{{route('upload-products')}}"
                    data-check="{{route('check-status')}}"
                    {{!empty($lastFeedInProgress)  ? 'disabled' : ''}}
            >Upload/Refresh Products</button>
            <p>Uploading status:<span id="upload-status">{{ !empty($lastFeed) ? ($lastFeed['status'] ?? '') : ''}}</span></p>
            <p>Uploading uploaded:<span id="uploaded">{{$productsLastFeed ?? ''}}</span></p>
        </div>

        @if(!empty($products['data']))
            <p>Last Feed: {{date('d M Y', strtotime($lastFeedDone['updated_at']))}}</p>
            <p>Source: {{$lastFeedDone['source']}}</p>
            <p>Report: {{$lastFeedDone['report']}}</p>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">SKU</th>
                    <th scope="col">PRICE</th>
                    <th scope="col">NAME</th>
                    <th scope="col">DESCRIPTION</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products['data'] as $product)
                    <tr>
                        <th><a href="{{route('show-product', ['id' => $product['id']])}}">{{$product['sku']}}</a></th>
                        <td>{{$product['price']}}</td>
                        <td>{{$product['product_attributes']['name'] ?? ''}}</td>
                        <td>{{$product['product_attributes']['description'] ?? ''}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <nav>
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="{{$products['prev_page_url']}}">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="{{$products['next_page_url']}}">Next</a></li>
                </ul>
            </nav>
        @else
            <p>No products</p>
        @endif
    </div>
@endsection
