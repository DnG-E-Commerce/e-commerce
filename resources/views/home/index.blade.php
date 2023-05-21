@extends('templates.main')

@section('content')
  @if(Session::get('message'))
    <script>
      $(document).ready(function () {
        $('#modal-notification').modal("show");
      })
    </script>    
  @endif
  <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-cover.jpg'); background-position: top;">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5 text-center mx-auto">
          <h1 class="text-white mb-2 mt-5">Selamat datang,</h1>
          <h2 class="text-white mb-2">{{ Session::get('name') }}!</h2>
          <p class="text-lead text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Eaque, natus laudantium recusandae ab impedit consectetur.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="col-md col-lg col-sm">
      <div class="d-flex justify-content-center" style="flex-wrap: wrap;">
      @for ($i = 1; $i < 10; $i++)
        <div class="card col-md-3 mx-1 my-1 shadow-md">
          <img src="{{asset('assets/img/curved-images/curved-6.jpg')}}" alt="Image" class="image-fluid">
          <div class="card-body">
            <h3>Hello world</h3>
            <p class="fst-italic">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos quis voluptatibus esse pariatur. A inventore tenetur, laboriosam totam facere ratione.</p>
            <a href="#" class="text-primary">Read more &RightArrow;</a>
          </div>
        </div> 
        @endfor
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
