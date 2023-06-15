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
                    <div class="card-header pb-0">
                        <h6>Detail Invoice</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="row p-3">
                            <div class="col-lg-6">
                                @foreach ($invoice->order as $order)
                                    <div class="card mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="{{ asset('storage/' . $order->product->photo) }}"
                                                    class="img-fluid rounded-start"
                                                    alt="Image of {{ $order->product->name }}">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <span
                                                        class="badge bg-primary rounded-pill float-end">{{ $order->qty }}</span>
                                                    <h5 class="card-title">{{ $order->product->name }}</h5>
                                                    <p class="card-text">{{ substr($order->product->desc, 0, 50) }}...</p>
                                                    <p class="card-text">Rp. {{ number_format($order->total, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-lg-6"></div>
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
@endsection
