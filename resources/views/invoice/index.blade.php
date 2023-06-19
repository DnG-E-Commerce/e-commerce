@extends('templates.main')
@section('content')
    @if (Session::get('message'))
        <script>
            $(document).ready(function() {
                $('#modal-notification').modal("show");
            })
        </script>
    @endif
    <div class="container-fluid py-4">
        <div class="card-header pb-0">
            <div class="text-center">
                <h3>Transaksi</h3>
            </div>
        </div>

        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-4">List</h4>
                    <h4>Total : {{ count($invoices) }}</h4>
                </div>
                <form action="{{ route('cart.checkout') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if (!$invoices)
                        <div class="card shadow mb-3">
                            <div class="card-body p-3">
                                <h4 class="text-center">Tidak ada Item!</h4>
                            </div>
                        </div>
                    @endif
                    @foreach ($invoices as $key => $invoice)
                        <div class="card shadow-lg mb-3">
                            <div class="card-body">
                                <div class="d-flex gap-5 form-check form-check-inline float-end">
                                    <div class="dropdown">
                                        <button class="fa-solid fa-ellipsis-vertical" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href=""
                                                    onclick="return confirm('Apakah anda ingin menghapus produk ini dari keranjang?')">Hapus</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="d-flex gap-3">
                                            <h5 class="card-title">{{ $invoice->invoice_code }}</h5>
                                            @if ($invoice->status == 'Pending')
                                                <label for="status"
                                                    class="badge badge-sm bg-gradient-secondary">Pending</label>
                                            @else
                                                @if ($invoice->status == 'Belum Lunas')
                                                    <label for="status" class="badge badge-sm bg-gradient-danger">Belum
                                                        Lunas</label>
                                                @else
                                                    @if ($invoice->status == 'Lunas')
                                                        <label for="status"
                                                            class="badge badge-sm bg-gradient-success">Lunas</label>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        </p>
                                        <div class="row gap-3 g-3 align-items-center mb-3">
                                            <div class="col-4">
                                                <label for="total" class="col-form-label">Ongkir</label>
                                            </div>
                                            <div class="col-4">
                                                <input type="number" name="ongkir" id="ongkir"
                                                    class="form-control-plaintext"
                                                    value="{{ $invoice->ongkir ? $invoice->ongkir : 0 }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row gap-3 g-3 align-items-center mb-3">
                                            <div class="col-4">
                                                <label for="total" class="col-form-label">Grand Total</label>
                                            </div>
                                            <div class="col-4">
                                                <input type="number" name="grand_total" id="grand_total"
                                                    class="form-control-plaintext" value="{{ $invoice->grand_total }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="row gap-3 g-3 align-items-center mb-3">
                                            <div class="col-4">
                                                <label for="total" class="col-form-label">Status Barang</label>
                                            </div>
                                            <div class="col-4">
                                                @foreach ($invoice->order as $key => $o)
                                                    @if ($key == 0)
                                                        <input type="text" name="grand_total" id="grand_total"
                                                            class="form-control-plaintext" value="{{ $o->status }}"
                                                            readonly>
                                                    @break
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="float-end">
                                @if ($invoice->status == 'Pending')
                                    <a href="{{ route('invoice.edit', ['invoice' => $invoice->id]) }}"
                                        class="btn btn-sm bg-gradient-warning">Lakukan
                                        Pembayaran</a>
                                @endif
                                <a href="{{ route('invoice.show', ['invoice' => $invoice->id]) }}"
                                    class="btn btn-sm bg-gradient-primary">Detail
                                    Invoice</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </form>
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
@endsection
