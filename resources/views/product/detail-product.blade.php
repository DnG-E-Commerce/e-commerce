@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-8 mb-lg-0 mb-4">
                <div class="card z-index-2 p-2">
                    <div class="card-body mt-0">

                        <div class="row">
                            <div class="col-lg-6">
                                <img src="{{ asset('storage/' . $product->photo) }}"
                                    style="width: 100%; height: 30rem; object-fit: cover;" class="rounded mb-3"
                                    alt="Photo {{ $product->name }}">
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-grid">
                                            <h3>{{ $product->name }} </h3>
                                            <h4 class="fst-italic text-secondary">
                                                {{ $product->category->category }}
                                            </h4>
                                        </div>
                                        <div class="float-end">
                                            <span
                                                class="badge badge-sm bg-gradient-{{ $product->qty_status == 'Ready' ? 'success' : 'danger' }}">{{ $product->qty_status }}</span>
                                            <span
                                                class="badge badge-sm bg-gradient-secondary">{{ $product->special_status }}</span>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border border-1 border-dark">
                                <h6>Satuan : {{ $product->uom }}</h6>
                                <h6>Stock : {{ $product->qty }}</h6>
                                <h6>Tipe Produk : {{ $product->special_status }}</h6>
                                <hr class="border border-1 border-dark">
                                <div class="row justify-content-center text-center my-5">
                                    <div class="col-lg-6">
                                        <h5>Harga Customer</h5>
                                        <p>Rp. {{ $product->customer_price }}</p>
                                    </div>
                                    <div class="col-lg-6">
                                        <h5>Harga Reseller</h5>
                                        <p>Rp. {{ $product->reseller_price }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="border border-1 border-dark">
                        <h6>Deskripsi Produk :</h6>
                        <p style="text-align: justify;">{{ $product->desc }}</p>
                    </div>
                </div>
                <a href="{{ route('product') }}" class="btn btn-sm btn-danger mt-2 float-end">Kembali</a>
            </div>
        </div>
    </div>
@endsection
