@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    @if (Session::get('message'))
        <script>
            $(document).ready(function() {
                $('#modal-notification').modal("show");
            })
        </script>
    @endif
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-4 col-md-4 mb-lg-0 mb-4">
                <div class="card z-index-2 p-2">
                    <div class="card-body mt-0">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-center mb-3">
                                Ubah Password
                            </h5>
                            <a href="{{ route('su.profile') }}" class="btn btn-close bg-danger float-end p-2"></a>
                        </div>
                        <form action="{{ route('su.profile.update-password', ['user' => $user->id]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="old_password">Password Lama</label>
                                <input type="password" name="old_password"
                                    class="form-control @error('old_password') is-invalid @enderror" id="old_password">
                                @error('old_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">Password Baru</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">Ulangi Password Baru</label>
                                <input type="password" name="repeat_password"
                                    class="form-control @error('repeat_password') is-invalid @enderror">
                                @error('repeat_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-sm bg-gradient-warning" type="submit">Ubah</button>
                            </div>
                        </form>
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
                        <p>{!! Session::get('message') !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
