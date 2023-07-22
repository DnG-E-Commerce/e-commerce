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
        <div class="card z-index-2 p-3">
            <div class="card-body">
                <div class="row my-3">
                    <div class="d-flex justify-content-between">
                        <h4>Tabel Pengiriman Pesanan</h4>
                    </div>
                </div>
                <div class="table-responsive p-0">
                    <table class="table table-stripped table-hover" id="table_driver">
                        <thead>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Invoice Code</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Nama</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Tanggal</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Grand Total</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Status Pembayaran</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Status Pemesanan</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                Opsi</th>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($invoices as $key => $invoice)
                                @foreach ($invoice->order as $i => $order)
                                    @if (in_array($order->status, ['Dikirim', 'Diterima']))
                                        <tr>

                                            <td class="align-middle text-sm">
                                                <span class="text-center font-weight-bold ">{{ $no++ }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $invoice->invoice_code }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{  $invoice->user->name }}</span>
                                            </td>
                                             <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $invoice->created_at->format('d-m-Y') }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold"> Rp.
                                                    {{ number_format($invoice->grand_total, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">{{ $invoice->status }}</span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <span class="text-xs font-weight-bold">
                                                    @if ($i == 0)
                                                        {{ $order->status }}
                                                    @endif

                                                </span>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <a href="{{ route('driver.invoice', ['invoice' => $invoice->id]) }}"
                                                    class="btn btn-sm bg-gradient-primary">Upload Pengiriman</a>
                                            </td>
                                        </tr>
                                    @endif
                                @break
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
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
<script>
    $(document).ready(function() {
        $('#table_driver').DataTable();
    });
</script>
@endsection
