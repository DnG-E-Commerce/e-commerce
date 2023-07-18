@extends('templates.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card-header pb-0">
                    <div class="text-center mb-4">
                        <h3>Form Pengajuan Reseller</h3>
                    </div>
                </div>
                <div class="card z-index-2 p-2">
                    <div class="card-body mt-0">
                        <div class="row justify-content-center">
                            <div class="d-grid p-2 col-6">
                                <img src="{{ asset('storage/image/blank.jpg') }}"
                                    style="object-fit: cover; border:1px solid black" class="img-fluid" id="instance_photo">
                            </div>
                        </div>
                        <form action="{{ route('su.customer.request.store', ['user' => $user->id]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="instance_name">Nama Toko</label>
                                <input type="text" name="instance_name" class="form-control">
                                @error('instance_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="instance_name">Foto Toko</label>
                                <input type="file" name="photo" id="photo" class="form-control">
                                @error('instance_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-sm bg-gradient-success">Ajukan</button>
                            </div>
                            <p><small class="text-danger">*</small> Syarat & Ketentuan</p>
                            <ul class="list-group-numbered">
                                <!-- <li class="list-group-item">
                                    Pengajuan Reseller hanya akan di approve apabila customer telah melakuakan pembelian
                                    lebih dari 20x serta tiap pembeliannya melebihi Rp. 200.000
                                </li> -->
                                <li class="list-group-item">
                                    Customer WAJIB mengirim foto tampak depan toko atau bukti online shop lainnya apabila ingin mengajukan sebagai reseller
                                </li>
                                <li class="list-group-item">
                                    Customer WAJIB mengirim nama dari toko apabila ingin mengajukan sebagai reseller
                                </li>
                                <li class="list-group-item">
                                    Apabila customer pernah mengajukan pengajuan menjadi reseller, maka data terbaru yang
                                    akan dijadikan patokan untuk direview
                                </li>
                                <li class="list-group-item">
                                    Setiap Customer yang mendapatkan rekomendasi untuk menjadi reseller hanya dapat
                                    mengajukannya sekali!
                                </li>
                                <li class="list-group-item">
                                    Apabila pengajuan ditolak, maka customer dapat mengajukan kembali pengajuan menjadi
                                    reseller pada lain waktu
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const canvasImg = document.getElementById('instance_photo')
        const photo = document.getElementById('photo')
        photo.addEventListener('change', (e) => {
            canvasImg.src = URL.createObjectURL(photo.files[0])
        })
    </script>
@endsection
