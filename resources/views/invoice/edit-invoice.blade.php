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
                <h4 class="mb-4">{{ $title }}</h4>
                <form action="{{ route('invoice.update', ['invoice' => $invoice->id]) }}" method="post"
                    enctype="multipart/form-data" id="form-checkout">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="payment_method" class="form-label">Pilih Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="form-select">
                            @foreach ($payment_method as $pm)
                                <option value="{{ $pm['bank'] }}">{{ $pm['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="provinsi" class="form-label">Provinsi</label>
                                <select name="provinsi" id="provinsi" class="form-select">
                                    <option>Pilih</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kabupaten" class="form-label">Kabupaten</label>
                                <select name="kabupaten" id="kabupaten" class="form-select">
                                    <option>Pilih</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                <select name="kecamatan" id="kecamatan" class="form-select">
                                    <option>Pilih</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kelurahan" class="form-label">Kelurahan</label>
                                <select name="kelurahan" id="kelurahan" class="form-select">
                                    <option>Pilih</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button class="btn bg-gradient-success" id="btn-pay">Checkout Sekarang</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 mb-lg-0 mb-4">
                <h4 class="mb-4">List Produk</h4>
                <ol class="list-group list-group-numbered mb-3">
                    @foreach ($invoice->order as $order)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ $order->product->name }}</div>
                                Rp. {{ $order->total }}
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $order->qty }}</span>
                        </li>
                    @endforeach
                </ol>
                <h5>Total Harga : Rp. {{ $invoice->grand_total }}</h5>
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
    <script>
        $(document).ready(function() {
            $('#btn-pay').click(function(e) {
                e.preventDefault()
                let provinsi = $('#provinsi').val()
                let kabupaten = $('#kabupaten').val()
                let kecamatan = $('#kecamatan').val()
                let kelurahan = $('#kelurahan').val()
                let payment_method = $('#payment_method').val()
                if (payment_method == 'cash' || payment_method == 'cod') {
                    $('#form-checkout').submit()
                } else {
                    $.ajax({
                        url: "{{ route('api.invoice.checkout', ['invoice' => $invoice->id]) }}",
                        type: 'post',
                        data: {
                            address: `${kelurahan}, ${kecamatan}, ${kabupaten}, ${provinsi}`,
                            payment_method: payment_method
                        },
                        success: function(response) {
                            window.snap.pay(response, {
                                onSuccess: function(result) {
                                    /* You may add your own implementation here */
                                    // alert("payment success!");
                                    $('#form-checkout').submit()
                                    console.log(result)
                                },
                                onPending: function(result) {
                                    /* You may add your own implementation here */
                                    alert("wating your payment!");
                                    console.log(result);
                                },
                                onError: function(result) {
                                    /* You may add your own implementation here */
                                    alert("payment failed!");
                                    console.log(result);
                                },
                                onClose: function() {
                                    /* You may add your own implementation here */
                                    alert(
                                        'you closed the popup without finishing the payment'
                                    );
                                }
                            })
                        },
                        error: function(error) {
                            console.log(error)
                        }
                    })
                }
            })
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
@endsection
