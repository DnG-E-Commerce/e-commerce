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
                                <img src="{{ asset('storage/' . $product->photo) }}"
                                    style="width: 100%; height: 30rem; object-fit: cover;" class="rounded mb-3"
                                    alt="Photo {{ $product->name }}">
                            </div>
                            <div class="col-lg-6">
                                <h3>{{ $product->name }} ({{ $product->uom }})</h3>
                                <h4 class="fst-italic text-secondary">
                                    {{ $product->category->category }}
                                   
                                    <!-- @if (strtoupper($product->status) == 'READY') -->
                                        <span
                                            class="badge badge-sm bg-gradient-success float-end">{{ $product->status }}</span>
                                    <!-- @else
                                        <span
                                            class="badge badge-sm bg-gradient-danger float-end">{{ $product->status }}</span>
                                    @endif -->
                                </h4>
                                <hr class="border border-1 border-dark">
                                <form action="{{ route('cart.store', ['product' => $product->id]) }}" method="post">
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-2">
                                            <label for="price" class="col-form-label">Harga</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="number" name="price" class="form-control-plaintext"
                                                value="{{ $user->role == 4 ? $product->customer_price : $product->reseller_price }}"
                                                readonly>
                                        </div>
                                        <div class="col-4">
                                            <span id="prices_for" class="form-text">
                                                {{ $user->role == 4 ? 'Harga Customer' : 'Harga Reseller' }}
                                            </span>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-2">
                                            <label for="qty" class="col-form-label">Kuantitas</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="number" name="qty" class="form-control">
                                        </div>
                                        <div class="col-2">
                                            <span id="passwordHelpInline" class="form-text">
                                                Stok : {{ $product->qty }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <button class="btn btn-sm bg-gradient-success float-end">Masukkan Keranjang</button>
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
