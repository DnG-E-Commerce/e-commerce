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
                    <div class="text-center">
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

                                    @if (strtoupper($product->status) == 'READY')
                                        <span
                                            class="badge badge-sm bg-gradient-success float-end">{{ $product->status }}</span>
                                    @else
                                        <span
                                            class="badge badge-sm bg-gradient-danger float-end">{{ $product->status }}</span>
                                    @endif
                                </h4>
                                <hr class="border border-1 border-dark">
                                <form method="post">
                                    <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-2">
                                            <label for="price" class="col-form-label">Harga</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="number" name="price" id="price"
                                                class="form-control-plaintext"
                                                value="{{ $user->role == 4 ? $product->customer_price : $product->reseller_price }}"
                                                readonly>
                                        </div>

                                        <div class="col-4">
                                            <span id="prices_for" class="form-text">
                                                {{ $user->role == 4 ? 'Harga Customer' : 'Harga Reseller' }}
                                            </span>
                                        </div>
                                        <div class="col-2">
                                            <label for="uom" class="col-form-label">Satuan </label>
                                        </div>
                                        <div class="col-4">
                                            <span id="prices_for" class="form-text">
                                                {{ $product->uom }}
                                            </span>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-2">
                                            <label for="qty" class="col-form-label">Kuantitas</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="number" name="qty" id="qty" class="form-control">
                                        </div>
                                        <div class="col-2">
                                            <span id="passwordHelpInline" class="form-text">
                                                Stok : {{ $product->qty }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <h3>Harga :
                                            <h3 id="canvas-harga"></h3>
                                        </h3>
                                    </div>
                                    <div class="d-flex gap-3 float-end">
                                        <button class="btn btn-sm bg-gradient-success" id="checkout">Beli
                                            sekarang</button>
                                        <button class="btn btn-sm bg-gradient-warning" id="keranjang">Masukkan
                                            Keranjang</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr class="border border-1 border-dark">
                        <div class="text-break">
                            {!! htmlspecialchars_decode($product->desc) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let qty = document.getElementById('qty')
        let price = document.getElementById('price').value
        let canvasHarga = document.getElementById('canvas-harga')
        canvasHarga.innerText = "0"
        qty.addEventListener('change', () => {
            canvasHarga.innerText = qty.value * price
        })
    </script>
    <script>
        const checkout = document.getElementById('checkout')
        const keranjang = document.getElementById('keranjang')

        checkout.addEventListener('click', (e) => {
            e.preventDefault()
            let qty = document.getElementById('qty').value
            $.ajax({
                url: '{{ route('order.checkout', ['product' => $product->id]) }}',
                method: 'post',
                data: {
                    // product_id: '{{ $product->id }}',
                    qty: qty,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    window.location.assign('{{ route('order') }}')
                }
            })
        })
        keranjang.addEventListener('click', (e) => {
            e.preventDefault()
            let qty = document.getElementById('qty').value
            let price = document.getElementById('price').value
            $.ajax({
                url: '{{ route('cart.store', ['product' => $product->id]) }}',
                method: 'post',
                data: {
                    // product_id: '{{ $product->id }}',
                    qty: qty,
                    price: price,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#modal-notification').modal('show')
                    window.location.assign(
                        `{{ route('home.product', ['product' => $product->id]) }}`)

                }
            })
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
                        <p>{{ Session::get('message') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
