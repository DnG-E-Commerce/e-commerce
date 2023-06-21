@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4">
                    <div class="card-header pb-0">
                        <a href="{{ route('product') }}" class="btn btn-close bg-danger p-2 float-end"></a>
                        <h6>Edit Data Product</h6>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('area.update', ['area' => $area->id]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <label for="status">Alamat<span class="text-danger">*</span></label>
                            <hr class="border border-1 border-dark">
                            <div class="row">
                                <div class="col-lg-6">
                                <div class="form-group mb-3">
                                        <label for="provinsi">Provinsi</label>
                                        <select name="provinsi" id="provinsi" class="form-select">
                                            <option value="pilih">{{ $area->provinsi }}</option>
                                        </select>
                                        @error('provinsi')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kabupaten">Kabupaten</label>
                                        <select name="kabupaten" id="kabupaten" class="form-select">
                                            <option value="pilih">{{ $area->kabupaten }}</option>
                                        </select>
                                        @error('kabupaten')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="kecamatan">Kecamatan</label>
                                        <select name="kecamatan" id="kecamatan" class="form-select">
                                            <option value="pilih">{{ $area->kecamatan }}</option>
                                        </select>
                                        @error('kecamatan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="kelurahan">Kelurahan</label>
                                        <select name="kelurahan" id="kelurahan" class="form-select">
                                            <option value="pilih">{{ $area->kelurahan }}</option>
                                        </select>
                                        @error('kelurahan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr class="border border-1 border-dark">
                            <div class="form-group">
                                <label for="">Ongkir</label>
                                <input type="number" name="ongkir" id="ongkir" class="form-control"
                                    value="{{ $area->ongkir }}">
                                @error('ongkir')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-sm btn-success">Edit</button>
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
                    var tampung = `<option value='pilih'>Pilih</option>`;
                    document.getElementById('kabupaten').innerHTML = `<option value='pilih'>Pilih</option>`;
                    document.getElementById('kecamatan').innerHTML = `<option value='pilih'>Pilih</option>`;
                    document.getElementById('kelurahan').innerHTML = `<option value='pilih'>Pilih</option>`;
                    data.forEach((element) => {
                        tampung +=
                            `<option data-prov="${element.id}" value="${element.name}" {{ $area->provinsi == '${element.name}' ? selected : '' }}>${element.name}</option>`;
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
