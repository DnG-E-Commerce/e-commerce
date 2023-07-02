@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    @if (Session::get('message'))
        <script>
            $(document).ready(function() {
                $('#modal-notification').modal("show");
            })
        </script>
    @endif
    <div class="container-fluid py-4">
        <div class="row mt-4">
            <div class="col-lg mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4">
                    <div class="card-body p-3 pb-2">
                        <div class="row my-3">
                            <div class="d-flex justify-content-between">
                                <h4>Tabel Produk</h4>
                                <a href="{{ route('su.product.create') }}" class="btn btn-sm btn-success float-end">Tambah
                                    Data</a>
                            </div>
                        </div>
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="table_product">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs">No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama
                                            Produk</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Stok</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Satuan</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Harga Customer</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Harga Reseller</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Kategori</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $data)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm text-center">{{ $key + 1 }}</h6>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 text-sm">{{ $data->name }}</h6>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold">{{ !$data->qty ? '-' : $data->qty }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ !$data->uom ? '-' : $data->uom }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">Rp.
                                                    {{ $data->customer_price }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">Rp.
                                                    {{ $data->reseller_price }}</span>
                                            </td>
                                            <td class="align-end text-center text-sm">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $data->category }}</span>
                                            </td>
                                            <td class="text-center align-end">
                                                <a href="{{ route('su.product.stock', ['product' => $data->id]) }}"
                                                    class="badge badge-sm bg-gradient-success">
                                                    Tambah Stok
                                                </a>
                                                <a href="{{ route('su.product.edit', ['product' => $data->id]) }}"
                                                    class="badge badge-sm bg-gradient-warning">
                                                    Edit
                                                </a>
                                                <a href="{{ route('su.product.detail', ['product' => $data->id]) }}"
                                                    class="badge badge-sm bg-gradient-primary">
                                                    Detail
                                                </a>
                                                <a href="{{ route('su.product.delete', ['product' => $data->id]) }}"
                                                    class="badge badge-sm bg-gradient-danger"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data {{ $data->name }} ?')">
                                                    Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Notification --}}
    <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification"
        aria-hidden="true">
        <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modal-title-notification">{{ Session::get('type') }}</h6>
                    <button type="button" class="bg-danger btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="py-3 text-center">
                        <i class="ni ni-bell-55 ni-3x"></i>
                        <h4 class="text-gradient text-{{ Session::get('class') }} mt-4">{{ Session::get('alert') }}</h4>
                        <p>{{ Session::get('message') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#table_product').DataTable();
        });
    </script>
@endsection
