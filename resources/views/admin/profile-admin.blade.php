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
            <div class="col-lg-4 mb-lg-0 mb-4">
                <div class="card z-index-2 p-2">
                    <div class="card-body mt-0">
                        <h4 class="text-center">{{ $user->role }}</h4>
                        <div class="d-grid justify-content-center" style="width: 100%; height: 15rem;">
                            <img src="{{ asset('storage/' . $user->photo) }}"
                                style="width:12rem;height: 12rem; border-radius: 100%; object-fit: cover; border:1px solid black"
                                alt="Photo {{ $user->name }}">
                        </div>
                        <div class="text-center">
                            <h3 class="fst-italic">{{ $user->name }}</h3>
                            <hr class="border border-1 border-dark">
                            <p class="text-break">
                                {{ $user->address ? $user->address : 'Belum ada' }}
                            </p>
                            <hr class="border border-1 border-dark">
                            <div class="row">
                                <div class="col-lg-6">
                                    <small>
                                        <i class="fa fa-envelope"></i>
                                        {{ $user->email }}
                                    </small>
                                </div>
                                <div class="col-lg-6">
                                    <small>
                                        <i class="fa fa-phone"></i>
                                        {{ $user->phone ? $user->phone : 'Belum ada' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 float-end">
                    <a href="{{ route('su.profile.edit') }}" class="btn btn-sm bg-gradient-warning mt-2 float-end">Edit</a>

                    <a href="{{ route('su.profile.change-password') }}"
                        class="btn btn-sm bg-gradient-warning mt-2 float-end">Ubah Password</a>

                    @if (in_array($user->role, ['Owner', 'Admin']))
                        <a href="{{ route('su.dashboard') }}" class="btn btn-sm btn-danger mt-2 float-end">Kembali</a>
                    @else
                        <a href="{{ route('su.delivery') }}" class="btn btn-sm btn-danger mt-2 float-end">Kembali</a>
                    @endif

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
