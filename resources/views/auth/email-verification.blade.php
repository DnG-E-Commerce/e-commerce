@extends('templates.front')

@section('content')
    @if (Session::get('message'))
        <script>
            $(document).ready(function() {
                $('#modal-notification').modal("show");
            })
        </script>
    @endif
    <div class="container">
        <div class="row justify-content-center my-5">
            <div class="col-lg-4">
                <div class="card z-index-0 shadow">
                    <div class="card-body">
                        <h5 class="text-center mb-4">Email Verifikasi</h5>
                        <form role="form" action="{{ route('email-verification.check') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="otp_code">Kode OTP</label>
                                <input type="text" class="form-control" name="otp" value="{{ old('otp') }}">
                                @error('otp')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-secondary">Harap cek email yang telah anda daftarkan</small>
                            </div>
                            <div class="text-center">
                                <button class="btn bg-gradient-dark w-100 my-4 mb-2" type="submit">Verifikasi</button>
                            </div>
                        </form>
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
