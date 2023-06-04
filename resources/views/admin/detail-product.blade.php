@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-4 mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4 p-3">
                    <h4 class="text-center">{{ $product->name }}</h4>
                </div>
                <div class="card p-2">
                    <div class="card-header">
                        Detail Informasi
                    </div>
                    {{-- <img src="{{asset('storage/'.$select_user->photo)}}" alt="Photo {{$select_user->name}}" style="width: 12rem; background-size: cover;"> --}}
                    <div class="card-body mt-0">
                        <div class="d-grid justify-content-center" style="width: 100%; height: 15rem;">
                            <img src="{{ asset('storage/' . $product->photo) }}"
                                style="width: 20rem ;height: 12rem; object-fit: cover;" alt="Photo {{ $product->name }}">
                        </div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td class="text-center">{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <td>Stok</td>
                                    <td>:</td>
                                    <td class="text-center">{{ $product->qty }}</td>
                                </tr>
                                <tr>
                                    <td>Harga Customer</td>
                                    <td>:</td>
                                    <td class="text-center">{{ $product->customer_price }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Harga Reseller</td>
                                    <td>:</td>
                                    <td class="text-center">
                                        {{ $product->reseller_price }}</td>
                                </tr>
                                <tr>
                                    <td>Kategori</td>
                                    <td>:</td>
                                    <td class="text-center">{{ $product->category->category }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="{{ route('product') }}" class="btn btn-sm btn-danger mt-2 float-end">Kembali</a>
            </div>
        </div>
    </div>
@endsection
