@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4">
                    <div class="card-header pb-0">
                        <a href="{{ route('product') }}" class="btn btn-close bg-danger p-2 float-end"></a>
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
                                <label for="desc">Deskripsi Produk <span class="text-danger">*</span></label>
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
                                @php
                                    $unitofmeasure = ['Pcs', 'Karton/Dus', 'Box', 'Liter', 'Kilogram', 'Rincing', 'PAK', 'BAL', 'Paket', 'Toples'];
                                @endphp
                                <label for="uom">Satuan<span class="text-danger">*</span></label>
                                <select name="uom" class="form-select">
                                    <option value="pilih">--Pilih--</option>
                                    @foreach ($unitofmeasure as $uom)
                                        <option value="{{ $uom }}" {{ old('uom') == $uom ? 'selected' : '' }}>
                                            {{ $uom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('uom')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="weight">Ukuran Produk<span class="text-danger">*</span></label>
                                <input type="number" name="weight" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="qty">Stok Produk</label><span class="text-danger">*</span></label>
                                <input type="number" name="qty" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                @php
                                    $special_status = ['Biasa', 'Pre Order', 'Limited Edition'];
                                @endphp
                                <label for="status">Status<span class="text-danger">*</span></label>
                                <select name="status" class="form-select">
                                    <option value="pilih">--Pilih--</option>
                                    @foreach ($special_status as $ss)
                                        <option value="{{ $ss }}" {{ old('status') == $ss ? 'selected' : '' }}>
                                            {{ $ss }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
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
                                    <option value="pilih">--Pilih--</option>
                                    @foreach ($categories as $key => $data)
                                        <option value="{{ $data->id }}"
                                            {{ old('category') == $data->id ? 'selected' : '' }}>
                                            {{ $data->category }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
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
