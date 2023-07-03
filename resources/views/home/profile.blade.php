@extends('templates.main')
@section('content')
    @if (Session::get('message'))
        <script>
            $(document).ready(function() {
                $('#modal-notification').modal("show");
            })
        </script>
    @endif
    <div class="main-content position-relative max-height-vh-100 h-100">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-8 mb-3">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pembelian</p>
                                                <h5 class="font-weight-bolder">
                                                    @foreach ($total as $o)
                                                        {{ $o->total_order }}
                                                    @break
                                                @endforeach
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                            <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pengeluaran
                                            </p>
                                            <h5 class="font-weight-bolder">
                                                @foreach ($total as $o)
                                                    Rp. {{ number_format($o->total_pengeluaran, 0, ',', '.') }}
                                                @break
                                            @endforeach
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                        <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="ps-2">#</th>
                                            <th class="ps-2">Produk</th>
                                            <th class="ps-2">Total Pembelian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product as $key => $p)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $p->name }}</td>
                                                <td>{{ $p->total_order }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-profile mb-3">
                <img src="{{ asset('storage/image/curved-6.jpg') }}" style="height: 15rem; object-fit: cover;"
                    alt="Image placeholder" class="card-img-top image-fluid">
                <div class="row justify-content-center">
                    <div class="col-4 col-lg-4 order-lg-2">
                        <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                            <div class="d-grid justify-content-center" style="width: 100%; height: 15rem;">
                                <img src="{{ asset('storage/' . $user->photo) }}"
                                    style="width:12rem;height: 12rem; border-radius: 100%; object-fit: cover; border:1px solid white"
                                    alt="Photo {{ $user->name }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="text-center mt-4">
                        <h5>
                            {{ $user->name }}<span class="font-weight-light"></span>
                        </h5>
                        as
                        <div class="h6 font-weight-300">
                            <i class="ni location_pin mr-2"></i> {{ $user->role }}
                        </div>
                        <div>
                            <i class="ni education_hat mr-2"></i>Telah bergabung sejak
                            {{ substr($user->created_at, 0, 10) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-evenly">
                <a href="{{ route('us.edit.profile') }}" class="btn btn-sm bg-gradient-warning">Edit Profile</a>
                <a href="{{ route('us.change.password') }}" class="btn btn-sm bg-gradient-secondary">Ubah
                    Password</a>
            </div>
        </div>
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
@endsection
