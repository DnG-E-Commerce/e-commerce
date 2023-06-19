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
                <div class="card z-index-2 mb-4 p-3">
                    <div class="card-header pb-0">
                        <h6>Pengiriman</h6>
                    </div>
                    <div class="card-body pt-0 pb-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Invoice</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Total Harga</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $i => $invoice)
                                        @foreach ($invoice->order as $o => $order)
                                            @if ($order->status == 'Dikirim')
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 text-sm text-center">{{ $i + 1 }}</h6>
                                                    </td>
                                                    <td class="align-middle text-sm">
                                                        <span
                                                            class="text-xs font-weight-bold">{{ $invoice->invoice_code }}</span>
                                                    </td>
                                                    <td class="align-middle text-sm">
                                                        <span class="text-xs font-weight-bold">Rp.
                                                            {{ number_format($invoice->grand_total, 0, '.', ',') }}</span>
                                                    </td>
                                                    <td class="align-middle text-sm">
                                                        <a href="#" class="btn btn-sm bg-gradient-primary">Detail</a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
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
