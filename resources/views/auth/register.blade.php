@extends('templates.front')

@section('content')
  <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-cover.jpg'); background-position: top;">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5 text-center mx-auto">
          <h1 class="text-white mb-2 mt-5">Welcome!</h1>
          <p class="text-lead text-white">Use these awesome forms to login or create new account in your project for free.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
      <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
        <div class="card z-index-0">
          <div class="card-header text-center pt-4">
            <h5>Register with</h5>
          </div>
          <div class="card-body">
            <form role="form" action="{{ route('auth.store') }}" method="POST">
              @csrf
              <div class="mb-3">
                <input type="text" class="form-control" placeholder="Name" aria-label="Name" name="name" value="{{old('name')}}">
                @if ($errors->has('name'))
                  <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
              <div class="mb-3">
                <input type="email" class="form-control" placeholder="Email" aria-label="Email" name="email" value="{{old('email')}}">
                @if ($errors->has('email'))
                  <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
              </div>
              <div class="mb-3">
                <input type="password" class="form-control" placeholder="Password" aria-label="Password" name="password">
                @if ($errors->has('password'))
                  <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
              </div>
              <div class="mb-3">
                <input type="password" class="form-control" placeholder="Repeat Password" aria-label="Password" name="repeat-password">
                @if ($errors->has('repeat-password'))
                  <span class="text-danger">{{ $errors->first('repeat-password') }}</span>
                @endif
              </div>
              <div class="form-check form-check-info text-start">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                <label class="form-check-label" for="flexCheckDefault">
                  I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
                </label>
              </div>
              <div class="text-center">
                <button class="btn bg-gradient-dark w-100 my-4 mb-2" type="submit">Sign up</button>
              </div>
              <p class="text-sm mt-3 mb-0">Already have an account? <a href="{{route('auth.index')}}" class="text-dark font-weight-bolder">Sign in</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
