@extends('templates.admin')
@section('content')
@include('templates.layouts.admin-navbar')
@if(Session::get('message'))
    <script>
      $(document).ready(function () {
        $('#modal-notification').modal("show");
      })
    </script>    
@endif
<div class="container-fluid py-4">
  <div class="row mt-4">
    <div class="col-lg mb-lg-0 mb-4">
      <div class="card z-index-2 mb-4">
        <div class="card-header pb-0">
          <a href="{{route('product.create')}}" class="btn btn-sm btn-success float-end">Tambah Data</a>
          <h6>Resellers Table</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kontak</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                </tr>
              </thead>
              <tbody>
                {{--@foreach ($products as $key => $data)   
                <tr>
                  <td>
                    <h6 class="mb-0 text-sm">{{$data->name}}</h6>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold">{{!$data->qty ? '-' : $data->qty}}</p>
                  </td>
                  <td class="align-middle text-center text-sm">
                    <span class="text-secondary text-xs font-weight-bold">{{!$data->uom ? '-' : $data->uom}}</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">Rp. {{$data->price}}</span>
                  </td>
                  <td class="align-end text-center text-sm">
                    <span class="text-secondary text-xs font-weight-bold">{{$data->category->name}}</span>
                  </td>
                  <td class="text-center align-end">
                    <a href="{{route('product.stock', ['product' => $data->id])}}" class="badge badge-sm bg-gradient-warning">
                      Tambah Stok
                    </a>
                    {{-- <a href="javascript:;" class="badge badge-sm bg-gradient-warning">
                      Edit Prodak
                    </a> --}}
                    {{-- <a href="{{route('product.delete', ['product' => $data->id])}}" class="badge badge-sm bg-gradient-danger" onclick="return confirm('Apakah anda yakin ingin menghapus data {{$data->name}} ?')">
                      Hapus Prodak
                    </a>
                  </td>
                </tr>
                @endforeach --}}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal Notification --}}
<div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
  <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="modal-title-notification">{{Session::get('type')}}</h6>
        <button type="button" class="bg-danger btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <div class="py-3 text-center">
          <i class="ni ni-bell-55 ni-3x"></i>
          <h4 class="text-gradient text-{{Session::get('class')}} mt-4">{{Session::get('alert')}}</h4>
          <p>{{Session::get('message')}}</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection