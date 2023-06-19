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
        <div class="row mt-4">
            <div class="col-lg mb-lg-0 mb-4">
                <div class="card z-index-2 mb-4">
                    <div class="card-header pb-0">
                        <a href="{{ route('reseller.create') }}" class="btn btn-sm btn-success float-end">Tambah Data</a>
                        <h6>Tabel Reseller</h6>
                    </div>
                    <div class="card-body px-5 pt-0 pb-2">
                        <div class="row justify-content-end mx-2">
                            <div class="col-lg-4 col-md-6 col-sm-4">
                                <form action="" method="get" role="form">
                                    <!-- <div class="input-group">
                                        <input type="text" name="search" class="form-control">
                                        <button class="input-group-text bg-success text-white" type="submit">Cari</button>
                                    </div> -->
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-center text-center text-uppercase text-secondary text-xxs">No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Email</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Kontak</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resellers as $key => $data)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 text-sm text-center">{{ $key + 1 }}</h6>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $data->name }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span
                                                    class="text-xs font-weight-bold">{{ $data->email ? $data->email : '-' }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span
                                                    class="text-xs font-weight-bold">{{ $data->phone ? $data->phone : 'Belum ada' }}</span>
                                            </td>
                                            <td class="text-center align-end">
                                                <a href="{{ route('user.show', ['user' => $data->id]) }}"
                                                    class="btn btn-sm bg-gradient-warning">
                                                    Detail
                                                </a>
                                            </td>
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
