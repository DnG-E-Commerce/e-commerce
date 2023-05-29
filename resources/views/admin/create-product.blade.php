@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4">
                    <div class="card-header pb-0">
                        <a href="{{ route('product') }}" class="btn btn-danger float-end"><i class="fa fa-times"></i></a>
                        <h6>Tambah Data Product</h6>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('product') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="desc">Deskripsi Produk</label>
                                <textarea name="desc" name="desc" class="form-control" rows="3">{{ old('desc') }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="desc">Harga Produk (Untuk Customer) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="customer_price" class="form-control"
                                    value="{{ old('price') }}">
                                @error('customer_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="desc">Harga Produk (Untuk Reseller) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="reseller_price" class="form-control"
                                    value="{{ old('price') }}">
                                @error('reseller_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="uom">Satuan Berat <span class="text-danger">*</span></label>
                                <select name="uom" class="form-select">
                                    <option value="Kilogram">KG</option>
                                    <option value="Box">Box</option>
                                    <option value="Liter">L</option>
                                    <option value="Rincing">Rincing</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="weight">Berat Produk (KG)</label>
                                <input type="number" name="weight" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="qty">Stok Produk</label>
                                <input type="number" name="qty" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="desc">Foto Produk <span class="text-danger">*</span></label>
                                <input type="file" name="photo" class="form-control">
                                @error('photo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>
                            <div class="form-group mb-3">
                                <label for="category">Kategori <span class="text-danger">*</span></label>
                                <select name="category" class="form-select">
                                    @foreach ($categories as $key => $data)
                                        <option value="{{ $data->id }}">{{ $data->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-sm btn-success">Tambah</button>
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