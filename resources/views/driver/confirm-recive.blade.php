@extends('templates.driver')
@section('content')
    @include('templates.layouts.driver-navbar')
    @if (Session::get('message'))
        <script>
            $(document).ready(function() {
                $('#modal-notification').modal("show");
            })
        </script>
    @endif
    <div class="container-fluid py-4">
        <div class="card z-index-2 p-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <h5 class="text-center">
                            {{ !$invoice->shipping ? 'Form Konfirmasi Barang Diterima' : 'Bukti Barang Diterima' }}</h5>
                        <div style="display: grid;" class="justify-content-center">
                            @if (!$invoice->shipping)
                                <img src="{{ asset('storage/image/blank.jpg') }}"
                                    style="width: 30rem; object-fit: cover; border: 1px solid black;" id="canvas-photo">
                            @else
                                <img src="{{ asset('storage/' . $invoice->shipping->photo) }}"
                                    style="width: 30rem; object-fit: cover; border: 1px solid black;" id="canvas-photo">
                            @endif
                        </div>
                        @if (!$invoice->shipping)
                            <form action="{{ route('drive.store', ['invoice' => $invoice->id]) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="photo">Foto</label>
                                    <input type="file" name="photo" id="photo" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="photo">Grand Total</label>
                                    <input type="text" name="grand_total" id="grand_total" class="form-control"
                                        value="Rp. {{ number_format($invoice->grand_total, 0, ',', '.') }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="photo">Metode Pembayaran</label>
                                    @php
                                        switch ($invoice->payment_method) {
                                            case 'cod':
                                                $payment = 'Cash On Delivery';
                                                break;
                                        
                                            case 'cash':
                                                $payment = 'Tunai';
                                                break;
                                        
                                            case 'transfer':
                                                # code...
                                                $payment = 'Bank Transfer';
                                                break;
                                        }
                                    @endphp
                                    <input type="text" name="payment_method" id="payment_method" class="form-control"
                                        value="{{ $payment }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="photo">Alamat</label>
                                    <textarea name="send_to" id="send_to" class="form-control" rows="3" readonly>{{ $invoice->send_to }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="photo">Catatan</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" readonly>{{ $invoice->notes }}</textarea>
                                </div>
                                <button class="btn btn-sm bg-gradient-success">Kirim</button>
                            </form>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <h5 class="text-center">Detail Pesanan</h5>
                        <ol class="list-group list-group-numbered mb-3">
                            @foreach ($invoice->order as $key => $order)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">{{ $order->product->name }}</div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Harga Satuan : Rp.
                                                {{ number_format($order->user->role == 'Customer' ? $order->product->customer_price : $order->product->reseller_price, 0, ',', '.') }}
                                            </li>
                                            <li class="list-group-item">Total Harga : Rp.
                                                {{ number_format($order->total, 0, ',', '.') }}</li>
                                        </ul>
                                    </div>
                                    <span class="badge bg-primary rounded-pill"> QTY : {{ $order->qty }}</span>
                                </li>
                            @endforeach
                        </ol>
                        <a href="{{ route('driver') }}" class="btn btn-sm bg-gradient-danger float-end">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const canvasImg = document.getElementById('canvas-photo')
        let photo = document.getElementById('photo')
        photo.addEventListener('change', (e) => {
            canvasImg.src = URL.createObjectURL(photo.files[0])
        })
    </script>
@endsection
