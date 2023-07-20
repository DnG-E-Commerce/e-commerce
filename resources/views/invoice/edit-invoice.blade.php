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
                <form action="{{ route('us.invoice.update', ['invoice' => $invoice->id]) }}" method="post"
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
                    <div class="form-group" id="pickup_method">
                        <label for="is_pickup">Pilih metode pengiriman barang</label>
                        <select name="is_pickup" id="is_pickup" class="form-select">
                            <option value="diambil">Diambil ke toko</option>
                            <option value="dikirim">Dikirim sesuai dengan alamat</option>
                        </select>
                    </div>

                    <div class="row" id="address_check" hidden>
                        <small>Isi Alamat Jika Produk Anda ingin di Kirim</small>
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
                    <div class="form-group">
                        <label for="">Catatan (Detail alamat atau sebagainya)</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control"></textarea>
                        <small class="text-secondary">*catatan diisi untuk memberitahu detail alamat atau patokan alamat
                            yang
                            dituju</small>
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
                                Rp. {{ number_format($order->total, 0, ',', '.') }}
                            </div>
                            <span class="badge bg-primary rounded-pill">qty : {{ $order->qty }}</span>
                        </li>
                    @endforeach
                </ol>
                <h5>Total Harga : Rp. {{ number_format($invoice->grand_total, 0, ',', '.') }}</h5>
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
            $('#payment_method').change(function() {
                let payment_method = $('#payment_method').val()
                let provinsi = $('#provinsi').val()
                let kabupaten = $('#kabupaten').val()
                let kecamatan = $('#kecamatan').val()
                let kelurahan = $('#kelurahan').val()
                switch (payment_method) {
                    case 'transfer':
                        $('#address_check').attr('hidden', false)
                        $('#pickup_method').attr('hidden', false)
                        break
                    case 'cod':
                        $('#address_check').attr('hidden', false)
                        $('#pickup_method').attr('hidden', true)
                        break
                    case 'cash':
                        $('#address_check').attr('hidden', true)
                        $('#pickup_method').attr('hidden', true)
                        break
                }
            })
            $('#is_pickup').change(function() {
                let is_pickup = $('#is_pickup').val()
                switch (is_pickup) {
                    case 'dikirim':
                        $('#address_check').attr('hidden', false)
                        break
                    case 'diambil':
                        $('#address_check').attr('hidden', true)
                        break
                }
            })

            function midtrans() {
                let provinsi = $('#provinsi').val()
                let kabupaten = $('#kabupaten').val()
                let kecamatan = $('#kecamatan').val()
                let kelurahan = $('#kelurahan').val()
                let payment_method = $('#payment_method').val()
                $.ajax({
                    url: "{{ route('us.invoice.checkout', ['invoice' => $invoice->id]) }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        provinsi: provinsi,
                        kabupaten: kabupaten,
                        kecamatan: kecamatan,
                        kelurahan: kelurahan,
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

            $('#btn-pay').click(function(e) {
                e.preventDefault()
                let pickup_method = $('#is_pickup').val()
                let provinsi = $('#provinsi').val()
                let kabupaten = $('#kabupaten').val()
                let kecamatan = $('#kecamatan').val()
                let kelurahan = $('#kelurahan').val()
                let payment_method = $('#payment_method').val()
                if (payment_method == 'cash') {
                    $('#form-checkout').submit()
                } else if (payment_method == 'cod') {
                    if (provinsi != 'Pilih' && kabupaten != 'Pilih' && kecamatan != 'Pilih' &&
                        kelurahan !=
                        'Pilih') {
                        $('#form-checkout').submit()
                    } else {
                        alert('Harap isi alamat dengan lengkap!')
                        window.location.reload()
                    }
                } else {
                    if (pickup_method == 'dikirim') {
                        if (provinsi != 'Pilih' && kabupaten != 'Pilih' && kecamatan != 'Pilih' &&
                            kelurahan !=
                            'Pilih') {
                            midtrans()
                        } else {
                            alert('Harap isi alamat dengan lengkap!')
                            window.location.reload()
                        }
                    } else {
                        midtrans()
                    }
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
