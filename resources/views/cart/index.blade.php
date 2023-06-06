@extends('templates.main')
@section('content')
    @if (Session::get('message'))
        <script>
            $(document).ready(function() {
                $('#modal-notification').modal("show");
            })
        </script>
    @endif
    <div class="container-fluid py-4">
    <div class="card-header pb-0">
        <div class="text-center">        
                        <h3>Keranjang</h3>
                    </div>
        
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <form action="#" method="post">
                    @csrf
                    @foreach ($carts as $key => $cart)
                        <div class="card mb-3">
                            <div class="card-body">
                                
                                <div class="form-check form-check-inline float-end">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox"
                                        name="cart[{{ $key }}]">
                                    <label class="form-check-label" for="inlineCheckbox1">Pilih</label>
                                    <!-- <label class="form-check-label" for="inlineCheckbox1">Hapus</label> -->
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <img src="{{ asset('storage/' . $cart->product->photo) }}"
                                            style="width: 100%; object-fit: cover;" class="rounded"
                                            alt="Photo {{ $cart->product->name }}">
                                    </div>
                                    <div class="col-lg-8">
                                        <h5 class="card-title">{{ $cart->product->name }}</h5>
                                        <p class="card-text">Dikirim ke : {{ $cart->send_to }}
                                        </p>
                                        <p>Kuantitas : {{ $cart->qty }}</p>
                                        <p>Total : Rp. {{ $cart->total }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <button class="btn btn-sm bg-gradient-success float-end">Checkout</button>
                </form>
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
