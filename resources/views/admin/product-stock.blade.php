@extends('templates.admin')
@section('content')
@include('templates.layouts.admin-navbar')
<div class="container-fluid py-4">
  <div class="row mt-4 justify-content-center">
    <div class="col-lg-6 mb-lg-0 mb-4">
      <div class="card z-index-2 mb-4">
        <div class="card-header pb-0">
          <h6>Stock {{$product->name}}</h6>
        </div>
        <div class="card-body p-3">
          <form action="{{ route('stock.store', ['product' => $product->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
              <label for="name">Stock Barang</label>
              <input type="number" class="form-control" name="qty" value="{{old('qty')}}">
              @if ($errors->has('qty'))
              <small class="text-danger">{{$errors->first('qty')}}</small>
              @endif
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-sm btn-success">Tambah</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection