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
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-4">List Orderan Produk</h4>
                    <h4>Total : {{ count($orders) }}</h4>
                </div>
                <form action="{{ route('cart.checkout') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if (!$orders)
                        <div class="card shadow mb-3">
                            <div class="card-body p-3">
                                <h4 class="text-center">Tidak ada Item!</h4>
                            </div>
                        </div>
                    @endif
                    @foreach ($orders as $key => $order)
                        <div class="card shadow-lg mb-3">
                            <div class="card-body">
                                <div class="d-flex gap-5 form-check form-check-inline float-end">
                                    @switch($order->status)
                                        @case('Recive')
                                            <span class="badge badge-sm bg-gradient-success">Diterima</span>
                                        @break

                                        @case('Delivery')
                                            <span class="badge badge-sm bg-gradient-success">Dalam Perjalanan</span>
                                        @break

                                        @case('Order Confirmed')
                                            <span class="badge badge-sm bg-gradient-success">Pembayaran Dikonfirmasi</span>
                                        @break

                                        @case('Ordered')
                                            <span class="badge badge-sm bg-gradient-success">Dipesan</span>
                                        @break

                                        @default
                                            <span class="badge badge-sm bg-gradient-warning">Harap Lengkapi data</span>
                                    @endswitch
                                    <div class="dropdown">
                                        <button class="fa-solid fa-ellipsis-vertical" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach ($order->invoice as $invoice)
                                                @if ($invoice->status == 'Paid')
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('invoice.order', ['invoice' => $invoice->id]) }}"
                                                            target="_blank">Detail
                                                            Invoice</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('order.delete', ['order' => $order->id]) }}"
                                                    onclick="return confirm('Apakah anda ingin menghapus produk ini dari keranjang?')">Hapus</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <img src="{{ asset('storage/' . $order->product->photo) }}"
                                            style="width: 100%; object-fit: cover;" class="rounded"
                                            alt="Photo {{ $order->product->name }}">
                                    </div>
                                    <div class="col-lg-8">
                                        <h5 class="card-title">{{ $order->product->name }}</h5>
                                        <p class="card-text">Dikirim ke : {{ $order->send_to ? $order->send_to : '-' }}
                                        </p>
                                        <div class="d-flex gap-5 g-3 align-items-center mb-3">
                                            <div class="col-2">
                                                <label for="qty" class="col-form-label">Kuantitas</label>
                                            </div>
                                            <div class="col-2">
                                                <input type="number" name="qty[{{ $order->id }}]"
                                                    class="form-control-plaintext" value="{{ $order->qty }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-items-center mb-3">
                                            <div class="col-2">
                                                <label for="total" class="col-form-label">Total</label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="number" name="total[{{ $order->id }}]"
                                                    class="form-control-plaintext" value="{{ $order->total_price }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (!$order->status)
                                    <a href="{{ route('order.show', ['order' => $order->id]) }}"
                                        class="btn btn-sm btn-warning float-end">Lenkapi
                                        data</a>
                                @endif
                                @if ($order->status == 'Ordered')
                                    @foreach ($order->invoice as $invoice)
                                        @if ($invoice->status == 'Paid')
                                        @else
                                            <a href="{{ route('invoice.order', ['invoice' => $invoice->id]) }}"
                                                class="btn btn-sm btn-success float-end">Selesaikan Pembayaran</a>
                                        @endif
                                    @endforeach
                                @endif
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
