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
                    <div class="card-body px-5 p-3 pb-2">
                        <div class="row my-3">
                            <div class="d-flex justify-content-between">
                                <h4>Tabel Customer</h4>
                                <a href="{{ route('su.customer.create') }}" class="btn btn-sm btn-success float-end">Tambah
                                    Data</a>
                            </div>
                        </div>
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="table_customer">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs">No</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Nama</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Email</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Kontak</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $key => $data)
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
                                                @if ($data->request_upgrade == 1)
                                                    <a class="btn btn-sm bg-gradient-primary"
                                                        href="{{ route('su.customer.request-upgrade', ['user' => $data->id]) }}">Upgrade</a>
                                                @endif
                                                <a href="{{ route('su.user.profile', ['role' => 'customer', 'user' => $data->id]) }}"
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
    <script>
        $(document).ready(function() {
            $('#table_customer').DataTable();
        });
    </script>
@endsection
