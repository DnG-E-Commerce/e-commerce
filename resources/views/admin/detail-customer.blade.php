@extends('templates.admin')
@section('content')
@include('templates.layouts.admin-navbar')
<div class="container-fluid py-4">
  <div class="row mt-4 justify-content-center">
    <div class="col-lg-4 mb-lg-0 mb-4">
      <div class="card z-index-2 mb-4 p-3">
        <h4 class="text-center">{{$customer->name}}</h4>
      </div>
      <div class="card p-2">
        <div class="card-header">
          Detail Informasi
        </div>
        <div class="card-body">
          <div class="input-group">
            <span for="nama" class="input-group-text border-0">Nama :</span>
            <input type="text" class="form-control border-0 text-center bg-transparent" value="{{$customer->name}}"readonly>
          </div>
          <div class="input-group">
            <span for="email" class="input-group-text border-0">Email :</span>
            <input type="text" class="form-control border-0 text-center text-center bg-transparent" value="{{$customer->email}}" readonly>
          </div>
          <div class="input-group">
            <span for="phone" class="input-group-text border-0">No Telp :</span>
            <input type="text" class="form-control border-0 text-center text-center bg-transparent" value="{{$customer->phone}}" readonly>
          </div>
          <div class="input-group">
            <span for="address" class="input-group-text border-0">Alamat :</span>
            <input type="text" class="form-control border-0 text-center text-center bg-transparent" value="{{$customer->address}}" readonly>
          </div>
          <div class="input-group">
            <span for="role" class="input-group-text border-0">Role :</span>
            <input type="text" class="form-control border-0 text-center text-center bg-transparent" value="{{$role[$customer->role-1]}}" readonly>
          </div>
        </div>
      </div>
      <a href="{{route('user.customer')}}" class="btn btn-sm btn-danger mt-2 float-end">Kembali</a>
    </div>
  </div>
</div>
@endsection