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
                            <div class="card-header">
                                <div class="float-end">
                                    @if ($invoice->payment_method != 'transfer')
                                        @foreach ($invoice->order as $order)
                                            @if ($order->status == 'Dipesan')
                                                <a href="{{ route('us.invoice.delete', ['invoice' => $invoice->id]) }}"
                                                    class="btn btn-sm bg-gradient-danger"
                                                    onclick="return confirm('Apakah anda ingin membatalkan pesanan ini?')">Batalkan</a>
                                            @endif
                                        @break
                                    @endforeach
                                @endif
                            </div>
                            <div class="d-flex gap-3">
                                <h5 class="card-title">{{ $invoice->invoice_code }}</h5>
                                @if ($invoice->status == 'Pending')
                                    <label for="status" class="badge badge-sm bg-gradient-secondary">Pending</label>
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
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    </p>
                                    <div class="row gap-3 g-3 align-items-center mb-3">
                                        <div class="col-4">
                                            <label for="total" class="col-form-label">Ongkir</label>
                                        </div>
                                        <div class="col-4">
                                            <h5>Rp. {{ number_format($invoice->ongkir, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                    <div class="row gap-3 g-3 align-items-center mb-3">
                                        <div class="col-4">
                                            <label for="total" class="col-form-label">Grand Total</label>
                                        </div>
                                        <div class="col-4">
                                            <h5>Rp. {{ number_format($invoice->grand_total, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                    <div class="row gap-3 g-3 align-items-center mb-3">
                                        <div class="col-4">
                                            <label for="total" class="col-form-label">Status Barang</label>
                                        </div>
                                        <div class="col-4">
                                            @foreach ($invoice->order as $key => $o)
                                                @if ($key == 0)
                                                    <h5>{{ $o->status }}</h5>
                                                @break
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="float-end">
                            @if ($invoice->status == 'Pending')
                                <a href="{{ route('us.invoice.edit', ['invoice' => $invoice->id]) }}"
                                    class="btn btn-sm bg-gradient-warning">Lakukan
                                    Pembayaran</a>
                            @endif
                            <a href="{{ route('us.invoice.show', ['invoice' => $invoice->id]) }}"
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
                <p>{!! Session::get('message') !!}</p>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
