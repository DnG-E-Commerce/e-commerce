@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between">
                            <h4 class="fst-italic text-center">
                                Tambah Kategori Baru
                                </h5>
                                <a href="{{ route('su.category') }}" class="btn btn-close bg-danger p-2 float-end"></a>
                        </div>
                        <form action="{{ route('su.category.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">Nama Kategori</label>
                                <input type="text" class="form-control" name="category" value="{{ old('category') }}">
                                @error('category')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
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
