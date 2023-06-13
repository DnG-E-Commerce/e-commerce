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
                <div class="card z-index-2 p-2">
                    <div class="card-body mt-0">
                        <div class="row">
                            <div class="col-lg-6">
                                <img src="{{ asset('storage/' . $order->product->photo) }}"
                                    style="width: 100%; height: 30rem; object-fit: cover;" class="rounded mb-3"
                                    alt="Photo {{ $order->product->name }}">
                            </div>
                            <div class="col-lg-6">
                                <h3>{{ $order->product->name }} ({{ $order->product->uom }})</h3>
                                <hr class="border border-1 border-dark">
                                <form action="{{ route('order.update', ['order' => $order->id]) }}" method="post"
                                    id="form-checkout">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="normal_price" id="normal_price"
                                        value="{{ $user->role == 4 ? $order->product->customer_price : $order->product->reseller_price }}">
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-2">
                                            <label for="price" class="col-form-label">Total Harga</label>
                                            <h5 id="canvas-harga"></h5>
                                        </div>
                                        <div class="col-auto">
                                            <input type="number" name="price" id="price"
                                                class="form-control-plaintext" value="{{ $order->total_price }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-2">
                                            <label for="qty" class="col-form-label">Kuantitas</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="number" name="qty" id="qty"
                                                class="form-control @error('qty')
                                                is-invalid
                                            @enderror"
                                                value="{{ $order->qty }}">
                                        </div>
                                        <div class="col-2">
                                            <span id="passwordHelpInline" class="form-text">
                                                Stok : {{ $order->product->qty }}
                                            </span>
                                        </div>
                                        @error('qty')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-2 align-self-start">
                                            <label for="qty" class="col-form-label">Dikirim ke</label>
                                        </div>
                                        <div class="col-10">
                                            <div class="form-group mb-3">
                                                <label for="provinsi">Provinsi</label>
                                                <select name="provinsi" class="form-select" id="provinsi">
                                                    <option>Pilih</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="kabupaten">Kabupaten</label>
                                                <select name="kabupaten" class="form-select" id="kabupaten">
                                                    <option>Pilih</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="">Kecamatan</label>
                                                <select name="kecamatan" class="form-select" id="kecamatan">
                                                    <option>Pilih</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="kelurahan">Kelurahan</label>
                                                <select name="kelurahan" class="form-select" id="kelurahan">
                                                    <option>Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-8 align-items-center">
                                        <div class="col-2">
                                            <label for="price" class="col-form-label">Metode Pembayaran</label>
                                        </div>
                                        <div class="col-auto">
                                            <select name="payment_method" id="payment_method" class="form-select">
                                                <option value="BRI VA">BRI Virtual Account</option>
                                                <option value="BNI VA">BNI Virtual Account</option>
                                                <option value="BCA VA">BCA Virtual Account</option>
                                                <option value="COD">Cash On Delivery (COD)</option>
                                                <option value="Cash">Cash</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <div class="d-grid">
                                                <button class="btn bg-gradient-success float-end"
                                                    id="pay-button">Checkout!</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let qty = document.getElementById('qty')
        let price = document.getElementById('price')
        let normalPrice = document.getElementById('normal_price')
        let canvasHarga = document.getElementById('canvas-harga')
        qty.addEventListener('keyup', () => {
            price.value = parseInt(qty.value * normalPrice.value)
        })
    </script>
    <script>
        fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/provinces.json`)
            .then((response) => response.json())
            .then((provinces) => {
                var data = provinces;
                var tampung = `<option>Pilih</option>`;
                data.forEach((element) => {
                    tampung +=
                        `<option data-prov="${element.id}" value="${element.name}">${element.name}</option>`;
                });
                document.getElementById("provinsi").innerHTML = tampung;
            });
    </script>
    <script>
        const selectProvinsi = document.getElementById('provinsi');
        const selectKota = document.getElementById('kabupaten');
        const selectKecamatan = document.getElementById('kecamatan');
        const selectKelurahan = document.getElementById('kelurahan');

        selectProvinsi.addEventListener('change', (e) => {
            var provinsi = e.target.options[e.target.selectedIndex].dataset.prov;
            fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/regencies/${provinsi}.json`)
                .then((response) => response.json())
                .then((regencies) => {
                    var data = regencies;
                    var tampung = `<option>Pilih</option>`;
                    document.getElementById('kabupaten').innerHTML = '<option>Pilih</option>';
                    document.getElementById('kecamatan').innerHTML = '<option>Pilih</option>';
                    document.getElementById('kelurahan').innerHTML = '<option>Pilih</option>';
                    data.forEach((element) => {
                        tampung +=
                            `<option data-prov="${element.id}" value="${element.name}">${element.name}</option>`;
                    });
                    document.getElementById("kabupaten").innerHTML = tampung;
                });
        });

        selectKota.addEventListener('change', (e) => {
            var kota = e.target.options[e.target.selectedIndex].dataset.prov;
            fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/districts/${kota}.json`)
                .then((response) => response.json())
                .then((districts) => {
                    var data = districts;
                    var tampung = `<option>Pilih</option>`;
                    document.getElementById('kecamatan').innerHTML = '<option>Pilih</option>';
                    document.getElementById('kelurahan').innerHTML = '<option>Pilih</option>';
                    data.forEach((element) => {
                        tampung +=
                            `<option data-prov="${element.id}" value="${element.name}">${element.name}</option>`;
                    });
                    document.getElementById("kecamatan").innerHTML = tampung;
                });
        });
        selectKecamatan.addEventListener('change', (e) => {
            var kecamatan = e.target.options[e.target.selectedIndex].dataset.prov;
            fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/villages/${kecamatan}.json`)
                .then((response) => response.json())
                .then((villages) => {
                    var data = villages;
                    var tampung = `<option>Pilih</option>`;
                    document.getElementById('kelurahan').innerHTML = '<option>Pilih</option>';
                    data.forEach((element) => {
                        tampung +=
                            `<option data-prov="${element.id}" value="${element.name}">${element.name}</option>`;
                    });
                    document.getElementById("kelurahan").innerHTML = tampung;
                });
        });
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
