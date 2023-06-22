@php
    switch ($user->role) {
        case 'Owner':
            $templates = 'templates.owner';
            $layout = 'templates.layouts.owner-navbar';
            break;
    
        case 'Admin':
            $templates = 'templates.admin';
            $layout = 'templates.layouts.admin-navbar';
            break;
    
        case 'Driver':
            $templates = 'templates.driver';
            $layout = 'templates.layouts.driver-navbar';
            break;
    }
@endphp
@extends($templates)
@section('content')
    @include($layout)
    <div class="container-fluid py-4">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 col-md-6 mb-lg-0 mb-4">
                <div class="card z-index-2 p-2">
                    <div class="card-header">
                        <h5 class="text-center">
                            {{ $user->name }}
                        </h5>
                    </div>
                    {{-- <img src="{{asset('storage/'.$select_user->photo)}}" alt="Photo {{$select_user->name}}" style="width: 12rem; background-size: cover;"> --}}
                    <div class="card-body mt-0">
                        <div class="d-grid justify-content-center" style="width: 100%; height: 15rem;">
                            <img src="{{ asset('storage/' . $user->photo) }}"
                                style="width:12rem;height: 12rem; border-radius: 100%; object-fit: cover; border:1px solid black"
                                alt="Photo {{ $user->name }}" id="admin-photo">
                        </div>
                        <form action="{{ route('admin.update', ['user' => $user->id]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <input type="file" name="photo"
                                    class="form-control @error('photo') is-invalid @enderror" id="photo">
                                @error('photo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">Nama</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ $user->name }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ $user->email ? $user->email : '-' }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone">No Telp</label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ $user->phone ? $user->phone : '-' }}">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="address">Alamat</label>
                                <textarea name="address" cols="30" rows="5" class="form-control @error('address') is-invalid @enderror">{{ $user->address ? $user->address : '-' }}</textarea>
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-sm bg-gradient-warning" type="submit">Edit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const canvasImg = document.getElementById('admin-photo')
        const photo = document.getElementById('photo')
        photo.addEventListener('change', (e) => {
            canvasImg.src = URL.createObjectURL(photo.files[0])
        })
    </script>
@endsection
