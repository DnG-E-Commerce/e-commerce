@extends('templates.admin')
@section('content')
    @include('templates.layouts.admin-navbar')
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-4 mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4 p-3">
                    <h4 class="text-center">{{ $select_user->name }}</h4>
                </div>
                <div class="card p-2">
                    <div class="card-header">
                        Detail Informasi
                    </div>
                    {{-- <img src="{{asset('storage/'.$select_user->photo)}}" alt="Photo {{$select_user->name}}" style="width: 12rem; background-size: cover;"> --}}
                    <div class="card-body mt-0">
                        <div class="d-grid justify-content-center" style="width: 100%; height: 15rem;">
                            <img src="{{ asset('storage/' . $select_user->photo) }}"
                                style="width:12rem;height: 12rem; border-radius: 100%; object-fit: cover; border:1px solid black"
                                alt="Photo {{ $select_user->name }}">
                        </div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td class="text-center">{{ $select_user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td class="text-center">{{ $select_user->email }}</td>
                                </tr>
                                <tr>
                                    <td>No Telp</td>
                                    <td>:</td>
                                    <td class="text-center">{{ $select_user->phone ? $select_user->phone : 'belum ada' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td class="text-center">
                                        {{ $select_user->address ? $select_user->address : 'belum ada' }}</td>
                                </tr>
                                <tr>
                                    <td>Role</td>
                                    <td>:</td>
                                    <td class="text-center">{{ $role[$select_user->role] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($select_user->role == 4)
                    <a href="{{ route('customer') }}" class="btn btn-sm btn-danger mt-2 float-end">Kembali</a>
                @else
                    <a href="{{ route('reseller') }}" class="btn btn-sm btn-danger mt-2 float-end">Kembali</a>
                @endif
            </div>
        </div>
    </div>
@endsection
