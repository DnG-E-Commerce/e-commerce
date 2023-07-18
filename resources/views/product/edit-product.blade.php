@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4">
                    <div class="card-header pb-0">
                        <a href="{{ route('su.product') }}" class="btn btn-close bg-danger p-2 float-end"></a>
                        <h6>Edit Data Product</h6>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('su.product.ubah', ['product' => $product->id]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="name">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ $product->name }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="desc">Deskripsi Produk</label>
                                <textarea name="desc" name="desc" class="form-control" rows="3">{{ $product->desc }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="desc">Harga Produk (Untuk Customer) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="customer_price" class="form-control"
                                    value="{{ $product->customer_price }}">
                                @error('customer_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="desc">Harga Produk (Untuk Reseller) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="reseller_price" class="form-control"
                                    value="{{ $product->reseller_price }}">
                                @error('reseller_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                @php
                                    $uom = ['Karton/Dus', 'Kilogram', 'Box', 'Liter', 'Rincing', 'Paket', 'Pcs', 'Toples', 'BAL', 'PAK'];
                                @endphp
                                <label for="uom">Satuan Produk <span class="text-danger">*</span></label>
                                <select name="uom" class="form-select">
                                    @foreach ($uom as $satuan)
                                        <option value="{{ $satuan }}"
                                            {{ $product->uom == $satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="desc">Foto Produk</label>
                                <input type="file" name="photo" class="form-control">
                                @error('photo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="category">Kategori <span class="text-danger">*</span></label>
                                <select name="category" class="form-select">
                                    @foreach ($categories as $key => $data)
                                        <option value="{{ $data->id }}"
                                            {{ $product->category_id == $data->id ? 'selected' : '' }}>
                                            {{ $data->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                @php
                                    $special_status = ['Pre Order', 'Biasa', 'Limited Edition'];
                                @endphp
                                <label for="special_status">Status <span class="text-danger">*</span></label>
                                <select name="special_status" class="form-select">
                                    @foreach ($special_status as $ss)
                                        <option value="{{ $ss }}"
                                            {{ $product->special_status == $ss ? 'selected' : '' }}>
                                            {{ $ss }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-sm btn-success">Edit</button>
                            </div>
                            <small>
                                <ul>
                                    <li>Yang bertandakan <span class="text-danger">*</span> wajib diisi</li>
                                </ul>
                            </small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
