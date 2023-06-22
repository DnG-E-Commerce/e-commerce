@extends('templates.front')

@section('content')
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
        style="background-image: url('https://i.ibb.co/WpK2ThV/Whats-App-Image-2023-06-23-at-01-12-19-1.jpg'); background-position: cover;">
        <!-- <img src="{{ asset('argon/img/logos/logo.png') }}" class="navbar-brand-img h-100" alt="main_logo"> -->
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 text-center mx-auto">
                    <h1 class="text-white mb-2 mt-5">D&G Store</h1>
                    <!-- <p class="text-lead text-white">Use these awesome forms to login or create new account in your project
                                for free.</p> -->
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                <div class="card z-index-0">
                    <div class="card-header text-center pt-4">
                        <h5>Form Register</h5>
                    </div>
                    <div class="card-body">
                        <form role="form" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Name" aria-label="Name"
                                    name="name" value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Email" aria-label="Email"
                                    name="email" value="{{ old('email') }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Nomor Telp" aria-label="Phone"
                                    name="phone" value="{{ old('email') }}">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Password" aria-label="Password"
                                    name="password">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Repeat Password"
                                    aria-label="Password" name="repeat-password">
                                @error('repeat-password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- <div class="form-check form-check-info text-start">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"
                                            checked>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and
                                                Conditions</a>
                                        </label>
                                    </div> -->
                            <div class="text-center">
                                <button class="btn bg-gradient-dark w-100 my-4 mb-2" type="submit">Register</button>
                            </div>
                            <p class="text-sm mt-3 mb-0">Sudah Memiliki Akun? <a href="{{ route('login') }}"
                                    class="text-primary text-gradient font-weight-bold">Login</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
