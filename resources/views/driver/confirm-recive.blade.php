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
                        <h5 class="text-center">Form Konfirmasi Barang Diterima</h5>
                        <div style="display: grid;" class="justify-content-center">
                            <img src="{{ asset('storage/image/blank.jpg') }}"
                                style="width: 30rem; object-fit: cover; border: 1px solid black;" id="canvas-photo">
                        </div>
                        <form action="{{ route('drive.store', ['invoice' => $invoice->id]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="photo">Foto</label>
                                <input type="file" name="photo" id="photo" class="form-control">
                            </div>
                            <button class="btn btn-sm bg-gradient-success">Kirim</button>
                        </form>
                    </div>
                    <div class="col-lg-4">
                        <h5 class="text-center">Detail Pesanan</h5>
                        <ol class="list-group list-group-numbered">
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
