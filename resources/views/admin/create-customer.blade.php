@extends('templates.admin')
@section('content')
@include('templates.layouts.admin-navbar')
<div class="container-fluid py-4">
  <div class="row mt-4 justify-content-center">
    <div class="col-lg-6 mb-lg-0 mb-4">
      <div class="card z-index-2 mb-4">
        <div class="card-header pb-0">
          <a href="{{route('user.customer')}}" class="btn btn-danger float-end"><i class="fa fa-times"></i></a>
          <h6>Tambah Data Product</h6>
        </div>
        <div class="card-body p-3">
          <form action="{{ route('customer.create') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
              <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="name" value="{{old('name')}}">
              @if ($errors->has('name'))
              <small class="text-danger">{{$errors->first('name')}}</small>
              @endif
            </div>
            <div class="form-group mb-3">
              <label for="email">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" value="{{old('email')}}">
              @if ($errors->has('email'))
                  <small class="text-danger">{{$errors->first('email')}}</small>
              @endif
            </div>
            <div class="form-group mb-3">
              <label for="password">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control">
              @if ($errors->has('password'))
                  <small class="text-danger">{{$errors->first('password')}}</small>
              @endif
            </div>
            <div class="form-group mb-3">
              <label for="address">Alamat <span class="text-danger">*</span></label>
              <textarea name="address" rows="3" class="form-control">{{old('address')}}</textarea>
            </div>
            <div class="form-group mb-3">
              <label for="phone">No. Telp</label>
              <input type="text" name="phone" class="form-control">
              @if ($errors->has('phone'))
                  <small class="text-danger">{{$errors->first('phone')}}</small>
              @endif
            </div>
            <div class="form-group mb-3">
              <label for="photo">Foto <span class="text-danger">*</span></label>
              <input type="file" name="photo" class="form-control">
              @if ($errors->has('photo'))
                  <small class="text-danger">{{$errors->first('photo')}}</small>
              @endif
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-sm btn-success">Tambah</button>
            </div>
            <small>
              <ul>
                <li>Yang bertandakan <span class="text-danger">*</span> wajib diisi</li>
              </ul>
            </small>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection