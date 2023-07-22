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
            <div class="col-lg-8 mb-lg-0 mb-4">
                <div class="card-header pb-0">
                    <div class="text-center mb-4">
                        <h3>Detail Produk</h3>
                    </div>
                </div>
                <div class="card z-index-2 p-2">
                    <div class="card-body mt-0">

                        <div class="row">
                            <div class="col-lg-6">
                                <img src="{{ asset('storage/' . $product->photo) }}"
                                    style="width: 100%; height: 30rem; object-fit: cover;" class="rounded mb-3"
                                    alt="Photo {{ $product->name }}">
                            </div>
                            <div class="col-lg-6">
                                <h3>{{ $product->name }} </h3>
                                <h4 class="fst-italic text-secondary">
                                    {{ $product->category->category }}
                                    <div class="d-flex gap-3 float-end">
                                        @if ($product->qty_status == 'Ready')
                                            <span
                                                class="badge badge-sm bg-gradient-success">{{ $product->qty_status }}</span>
                                        @else
                                            <span
                                                class="badge badge-sm bg-gradient-danger">{{ $product->qty_status }}</span>
                                        @endif
                                        @switch($product->special_status)
                                            @case('Biasa')
                                                <span
                                                    class="badge badge-sm bg-gradient-success">{{ $product->special_status }}</span>
                                            @break

                                            @case('Pre Order')
                                                <span
                                                    class="badge badge-sm bg-gradient-primary">{{ $product->special_status }}</span>
                                            @break

                                            @case('Limited Edition')
                                                <span
                                                    class="badge badge-sm bg-gradient-secondary">{{ $product->special_status }}</span>
                                            @break
                                        @endswitch
                                    </div>
                                </h4>
                                <hr class="border border-1 border-dark">
                                <form method="post" id="form-checkout-cart">
                                    @csrf
                                    <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-3">
                                            <label for="price" class="col-form-label">Harga</label>
                                        </div>
                                        <div class="col-auto">
                                            @if (!$user)
                                                <h5>Rp.
                                                    {{ number_format($product->customer_price, 0, ',', '.') }}</h5>
                                                <input type="hidden" name="price" id="price"
                                                    class="form-control-plaintext" value="{{ $product->customer_price }}"
                                                    readonly>
                                            @else
                                                <h5>Rp.
                                                    {{ number_format($product->customer_price, 0, ',', '.') }}</h5>
                                                <input type="hidden" name="price" id="price"
                                                    class="form-control-plaintext"
                                                    value="{{ $user->role == 'Customer' ? $product->customer_price : $product->reseller_price }}"
                                                    readonly>
                                            @endif
                                        </div>
                                        <div class="col-3">
                                            <h5 id="prices_for" class="form-text">
                                                @if (!$user)
                                                    Harga Satuan
                                                @else
                                                    {{ $user->role == 'Customer' ? 'Harga Customer' : 'Harga Reseller' }}
                                                @endif
                                                </span>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-3">
                                            <label for="uom" class="col-form-label">Satuan</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="text" name="uom" id="uom"
                                                class="form-control-plaintext" value="{{ $product->uom }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-3">
                                            <label for="qty" class="col-form-label">Kuantitas</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="number" name="qty" id="qty"
                                                class="form-control @error('qty') is-invalid @enderror">
                                        </div>
                                        <div class="col-2">
                                            <span class="form-text">
                                                Stok : {{ $product->qty }}
                                            </span>
                                        </div>
                                        @error('qty')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-3">
                                            <label for="total_harga" class="col-form-label">Total Harga</label>
                                        </div>
                                        <div class="col-auto d-flex fw-bolder">
                                            <input type="text" id="canvas-harga" class="form-control-plaintext" readonly>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-3 float-end">
                                        <button class="btn btn-sm bg-gradient-success check" id="checkout"
                                            data="checkout">Beli
                                            sekarang</button>
                                        <button class="btn btn-sm bg-gradient-warning check" id="cart"
                                            data="cart">Masukkan
                                            Keranjang</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr class="border border-1 border-dark">
                        <div class="text-break"> 
                        <strong>Deskripsi Produk:</strong> <br>
                            {!! htmlspecialchars_decode($product->desc) !!}
                        </div><hr class="border border-1 border-dark">
                         <div class="class=  text-break">
                         <strong>Catatan:</strong> <br>
                         <p>Pengiriman Barang Hanya Untuk Wilayah Subang, Indramayu, Purwakarta, Cirebon, Bandung dan sekitarnya<br>
                         Jika di luar wilayah tersebut harap menghubungi atau konfirmasi kepada Admin terlebih dahulu. <br>
                        Klik link WhatsApp di bawah ini untuk konfirmasi.</p>
                         <div class="col-lg-6">
    <ul class="nav nav-footer justify-content-center justify-content-lg-start">
        <li class="nav-item">
            <a href="https://wa.me/085658270260" class="nav-link text-muted" target="_blank">
                
               
<i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
            </a>
        </li>
    </ul>
</div>
                        
                    </ul>
                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let qty = $('#qty')
            let price = $('#price')
            let canvasHarga = $('#canvas-harga')
            canvasHarga.val(`Rp. 0`)
            qty.keyup(() => {
                console.log('Berubah')
                canvasHarga.val(`Rp. ${qty.val() * price.val()}`)
            })
        })
    </script>
    <script>
        const checkout = $('#checkout')
        const cart = $('#cart')
        let mode = $('#mode')
        checkout.click(() => {
            mode.val('checkout')
            $('#form-checkout-cart').attr('action', "{{ route('StoreOrder') }}")
            $('#form-checkout-cart').submit()
        })
        cart.click(() => {
            mode.val('cart')
            $('#form-checkout-cart').attr('action', "{{ route('StoreCart') }}")
            $('#form-checkout-cart').submit()
        })
    </script>
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
