@extends('templates.owner')
@section('content')
    @include('templates.layouts.owner-navbar')
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
                    <div class="card-body px-5 p-3 pb-2">
                        <div class="row justify-content-between mt-3">
                            <div class="col-lg-6">
                                <h4>Laporan Transaksi</h4>
                            </div>
                            <div class="col-lg-6">
                                <form action="" method="get">
                                    @csrf
                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="fromDate">Dari Tanggal</label>
                                            <input type="date" name="fromDate_transaction" id="fromDate_transaction"
                                                class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="fromDate">Sampai Tanggal</label>
                                            <input type="date" name="toDate_transaction" id="toDate_transaction"
                                                class="form-control">
                                        </div>
                                        <div class="form-group align-self-end">
                                            <button class="btn bg-gradient-success">Cari</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive p-1">
                            <table class="table align-items-center mb-0" id="table_transaction">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            No</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Tanggal</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Nama Pelanggan</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $o => $order)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm text-center">{{ $o + 1 }}</h6>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span
                                                    class="text-xs font-weight-bold">{{ substr($order->created_at, 0, 10) }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $order->user->name }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">Rp.
                                                    {{ number_format($order->total, 0, ',', '.') }}</span>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

                <div class="card z-index-2 mb-4">
                    <div class="card-body px-5 p-3 pb-2">
                        <div class="row justify-content-between mt-3">
                            <div class="col-lg-6">
                                <h4>Laporan Produk</h4>
                            </div>
                            <div class="col-lg-6">
                                <form action="" method="get">
                                    @csrf
                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="fromDate">Dari Tanggal</label>
                                            <input type="date" name="fromDate_product" id="fromDate_product"
                                                class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="fromDate">Sampai Tanggal</label>
                                            <input type="date" name="toDate_product" id="toDate_product"
                                                class="form-control">
                                        </div>
                                        <div class="form-group align-self-end">
                                            <button class="btn bg-gradient-success">Cari</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive p-1">
                            <table class="table align-items-center mb-0" id="table_product">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            No</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Tanggal</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Nama Produk</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Total Penjualan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $p => $product)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm text-center">{{ $p + 1 }}</h6>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span
                                                    class="text-xs font-weight-bold">{{ substr($product->created_at, 0, 10) }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $product->product->name }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $product->qty }}</span>
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
                            <h4 class="text-gradient text-{{ Session::get('class') }} mt-4">{{ Session::get('alert') }}
                            </h4>
                            <p>{{ Session::get('message') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#table_transaction').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'csv', 'excel', 'pdf', 'print'
                    ]
                });
                $('#table_product').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'csv', 'excel', 'pdf', 'print'
                    ]
                });
            });
        </script>
    @endsection
