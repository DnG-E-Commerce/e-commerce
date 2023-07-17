@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between">
                            <h4>Tambah Data Customer</h4>
                            <a href="{{ route('su.customer') }}" class="btn btn-close bg-danger p-2 float-end"></a>
                        </div>
                        <form action="{{ route('su.user.create', ['role' => 'customer']) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                    <small class="text-danger">{{ $errors->first('email') }}</small>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control">
                                @if ($errors->has('password'))
                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                @endif
                            </div>
                            <div class="row">
                                <label for="address">Alamat</label>
                                <hr class="border border-1 border-dark">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="provinsi">Provinsi</label>
                                        <select name="provinsi" id="provinsi" class="form-select">
                                            <option value="pilih">--Pilih--</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kabupaten">Kabupeten</label>
                                        <select name="kabupaten" id="kabupaten" class="form-select">
                                            <option value="pilih">--Pilih--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="kecamatan">Kecamatan</label>
                                        <select name="kecamatan" id="kecamatan" class="form-select">
                                            <option value="pilih">--Pilih--</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="keluratan">Kelurahan</label>
                                        <select name="kelurahan" id="kelurahan" class="form-select">
                                            <option value="pilih">--Pilih--</option>
                                        </select>
                                    </div>
                                </div>
                                <hr class="border border-1 border-dark">
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone">No. Telp<span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control">
                                @if ($errors->has('phone'))
                                    <small class="text-danger">{{ $errors->first('phone') }}</small>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="photo">Foto <span class="text-danger">*</span></label>
                                <input type="file" name="photo" class="form-control">
                                @if ($errors->has('photo'))
                                    <small class="text-danger">{{ $errors->first('photo') }}</small>
                                @endif
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-sm btn-success">Tambah</button>
                            </div>
                            <small>
                                <ul>
                                    <li>Yang bertandakan <span class="text-danger">*</span> wajib diisi</li>
                                </ul>
                            </small>
                        </form>
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
@endsection
