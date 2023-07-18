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
        <div class="row justify-content-center">

            <div class="row mt-4">
                <div class="col-lg-6 mb-lg-0 mb-4">
                    <div class="card z-index-2 h-100">
                        <div class="card-header pb-0 pt-3 bg-transparent">
                            <h6 class="text-capitalize">Insight 10 Pelanggan Terloyal</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart">
                                {!! $CustomerResellerChart->container() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-lg-0 mb-4">
                    <div class="card z-index-2 h-100">
                        <div class="card-header pb-0 pt-3 bg-transparent">
                            <h6 class="text-capitalize">Grafik Top 10 Produk Terjual</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart">
                                {!! $productChart->container() !!}
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
        <script src="{{ $CustomerResellerChart->cdn() }}"></script>
        <script src="{{ $productChart->cdn() }}"></script>
        {{ $CustomerResellerChart->script() }}
        {{ $productChart->script() }}
    @endsection
