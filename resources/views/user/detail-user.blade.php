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
                        <h4 class="text-center mb-3">{{ $select_user->role }}</h4>
                        <div class="d-grid justify-content-center" style="width: 100%; height: 15rem;">
                            <img src="{{ asset('storage/' . $select_user->photo) }}"
                                style="width:12rem;height: 12rem; border-radius: 100%; object-fit: cover; border:1px solid black"
                                alt="Photo {{ $select_user->name }}">
                        </div>
                        <div class="text-center">
                            <h3 class="fst-italic">{{ $select_user->name }}</h3>
                            <hr class="border border-1 border-dark">
                            <p class="text-break">
                                {{ $select_user->address ? $select_user->address : '-' }}
                            </p>
                            <hr class="border border-1 border-dark">
                            <div class="row">
                                <div class="col-lg-6 align-self-center">
                                    <small>
                                        <i class="fa fa-envelope"></i>
                                        {{ $select_user->email }}
                                    </small>
                                </div>
                                <div class="col-lg-6 align-self-center">
                                    <small>
                                        <i class="fa fa-phone"></i>
                                        {{ $select_user->phone }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 float-end">
                    @switch($select_user->role)
                        @case('Customer')
                            <a href="{{ route('su.customer') }}" class="btn btn-sm btn-danger mt-2 float-end">Kembali</a>
                        @break

                        @case('Reseller')
                            <a href="{{ route('su.reseller') }}" class="btn btn-sm btn-danger mt-2 float-end">Kembali</a>
                        @break

                        @case('Admin')
                            <a href="{{ route('su.admin') }}" class="btn btn-sm btn-danger mt-2 float-end">Kembali</a>
                        @break

                        @case('Driver')
                            <a href="{{ route('su.admin') }}" class="btn btn-sm btn-danger mt-2 float-end">Kembali</a>
                        @break

                        @default
                    @endswitch
                </div>
            </div>
        </div>
    </div>
@endsection
