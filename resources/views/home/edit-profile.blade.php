@extends('templates.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-4 col-md-4 mb-lg-0 mb-4">
                <div class="card z-index-2 p-2">
                    <div class="card-body mt-0">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-center mb-3">
                                Edit Profile
                            </h5>
                            <a href="{{ route('us.profile') }}" class="btn btn-close bg-danger float-end p-2"></a>
                        </div>
                        <div class="d-grid justify-content-center" style="width: 100%; height: 15rem;">
                            <img src="{{ asset('storage/' . $user->photo) }}"
                                style="width:12rem;height: 12rem; border-radius: 100%; object-fit: cover; border:1px solid black"
                                alt="Photo {{ $user->name }}" id="admin-photo">
                        </div>
                        <form action="{{ route('us.profile.update', ['user' => $user->id]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <input type="file" name="photo"
                                    class="form-control @error('photo') is-invalid @enderror" id="photo">
                                @error('photo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">Nama</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ $user->name }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ $user->email ? $user->email : '-' }}" readonly>
                                <small class="text-danger">* Email telah terverifikasi tidak dapat diubah</small>
                            </div>
                            <div class="form-group">
                                <label for="phone">No Telp</label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ $user->phone ? $user->phone : '-' }}">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
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
                            <div class="d-grid">
                                <button class="btn btn-sm bg-gradient-warning" type="submit">Edit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const canvasImg = document.getElementById('admin-photo')
        const photo = document.getElementById('photo')
        photo.addEventListener('change', (e) => {
            canvasImg.src = URL.createObjectURL(photo.files[0])
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
                    document.getElementById('kabupaten').innerHTML = "<option>Pilih</option>";
                    document.getElementById('kecamatan').innerHTML = "<option>Pilih</option>";
                    document.getElementById('kelurahan').innerHTML = "<option>Pilih</option>";
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
                    document.getElementById('kecamatan').innerHTML = "<option>Pilih</option>";
                    document.getElementById('kelurahan').innerHTML = "<option>Pilih</option>";
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
                    document.getElementById('kelurahan').innerHTML = "<option>Pilih</option>";
                    data.forEach((element) => {
                        tampung +=
                            `<option data-prov="${element.id}" value="${element.name}">${element.name}</option>`;
                    });
                    document.getElementById("kelurahan").innerHTML = tampung;
                });
        });
    </script>
@endsection
