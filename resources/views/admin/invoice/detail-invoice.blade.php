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
                        <h4>Detail Invoice</h4>
                    </div>
                    <div class="card-body pt-0 pb-2">
                        <div class="row p-3">
                            <div class="col-lg-6">
                                <h6>List Pemesanan</h6>
                                <ol class="list-group list-group-numbered mb-3">
                                    @foreach ($invoice->order as $order)
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">{{ $order->product->name }}</div>
                                                <ul>
                                                    <li>Harga Satuan :
                                                        Rp.
                                                        {{ number_format(($order->total - $order->invoice->ongkir) / $order->qty, 0, ',', '.') }}
                                                    </li>
                                                    <li>Total Harga :
                                                        Rp. {{ number_format($order->total, 0, ',', '.') }}
                                                    </li>
                                                </ul>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">QTY : {{ $order->qty }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                                @if (in_array($invoice->payment_method, ['cod', 'transfer']))
                                    {{-- Comment ini --}}
                                    <h5 class="text-center">Bukti barang diterima</h5>

                                    <div style="display: grid;" class="justify-content-center">
                                        @if (!$invoice->shipping)
                                            <img src="{{ asset('storage/image/blank.jpg') }}"
                                                style="width: 30rem; object-fit: cover; border: 1px solid black;"
                                                id="canvas-photo">
                                        @else
                                            <a href="{{ asset('storage/' . $invoice->shipping->photo) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $invoice->shipping->photo) }}"
                                                    style="width: 30rem; object-fit: cover; border: 1px solid black;"
                                                    id="canvas-photo">
                                            </a>
                                        @endif
                                    </div>
                                @endif {{-- Comment ini juga  --}}

                            </div>
                            <div class="col-lg-6">
                                <h6>Invoice</h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>No Invoice</th>
                                            <td>:</td>
                                            <td>{{ $invoice->invoice_code }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal</th>
                                            <td>:</td>
                                            <td>{{ $invoice->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Pemesan</th>
                                            <td>:</td>
                                            <td>{{ $invoice->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>No Telp</th>
                                            <td>:</td>
                                            <td>{{ $invoice->user->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>:</td>
                                            <td>{{ $invoice->send_to }}</td>
                                        </tr>
                                        <tr>
                                            <th>Catatan</th>
                                            <td>:</td>
                                            <td>{{ $invoice->notes ? $invoice->notes : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ongkir</th>
                                            <td>:</td>
                                            <td>Rp. {{ number_format($invoice->ongkir, 0, '.', ',') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Harga</th>
                                            <td>:</td>
                                            <td>Rp. {{ number_format($invoice->grand_total, 0, '.', ',') }}</td>
                                        </tr>
                                       
                                    <tr>
                                    <tr>
                                        <th>Metode Pengambilan Barang</th>
                                        <td>:</td>
                                        <td>
                                            {{ $invoice->is_pickup == 1 ? 'Diambil ke toko' : 'Dikirim' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Metote Pembayaran</th>
                                        <td>:</td>
                                        <td>{{ $invoice->payment_method }}</td>
                                    </tr>
                                    <tr>
                                    <tr>
                                        <th>Status Pesanan</th>
                                        <td>:</td>
                                        @if ($invoice->is_recive == 1)
                                            <td>Telah Diambil Pembeli</td>
                                        @else
                                            <td>{{ $order->status }}</td>
                                        @endif
                                    </tr>
                                    @foreach ($invoice->order as $order)
                                        @if ($invoice->payment_method == 'cash' && $order->status == 'Dipesan')
                                            <tr>
                                                <th>Update Pesanan</th>
                                                <td>:</td>
                                                <td>
                                                    <form
                                                        action="{{ route('su.order.update-status', ['invoice' => $invoice->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        @switch($order->status)
                                                            @case('Dipesan')
                                                                <input type="hidden" name="status"
                                                                    value="Dikonfirmasi/Dikemas">
                                                                <button type="submit"
                                                                    class="btn btn-sm bg-gradient-warning">Konfirmasi
                                                                    / Dikemas</button>
                                                            @break
                                                        @endswitch
                                                    </form>

                                                </td>
                                            </tr>
                                        @elseif ($invoice->payment_method == 'cod' &&
                                                in_array($order->status, ['Dipesan', 'Dikonfirmasi/Dikemas']))
                                            <tr>
                                                <th>Update Pesanan</th>
                                                <td>:</td>
                                                <td>
                                                    <form
                                                        action="{{ route('su.order.update-status', ['invoice' => $invoice->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        @switch($order->status)
                                                            @case('Dipesan')
                                                                <input type="hidden" name="status"
                                                                    value="Dikonfirmasi/Dikemas">
                                                                <button type="submit"
                                                                    class="btn btn-sm bg-gradient-warning">Konfirmasi
                                                                    / Dikemas</button>
                                                            @break

                                                            @case('Dikonfirmasi/Dikemas')
                                                                <input type="hidden" name="status" value="Dikirim">
                                                                <button type="submit"
                                                                    class="btn btn-sm bg-gradient-warning">Kirim</button>
                                                            @break
                                                        @endswitch
                                                    </form>

                                                </td>
                                            </tr>
                                            @elseif ($invoice->payment_method == 'transfer' && $invoice->status == 'Lunas' && $invoice->is_pickup == 1 && $invoice->is_recive == 0
                                             &&
                                                in_array($order->status, ['Dipesan', 'Dikonfirmasi/Dikemas']))
                                                <tr>
                                                    <th>Update Pesanan</th>
                                                        <td>:</td>
                                                    <td>
                                                        <form action="{{ route('su.order.update-status', ['invoice' => $invoice->id]) }}" method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            @switch($order->status)
                                                            @case('Dipesan')
                                                                <input type="hidden" name="status"
                                                                    value="Dikonfirmasi/Dikemas">
                                                                <button type="submit"
                                                                    class="btn btn-sm bg-gradient-warning">Konfirmasi
                                                                    / Dikemas</button>
                                                            @break

                                                            @case('Dikonfirmasi/Dikemas')
                                                                <input type="hidden" name="status" value="Diterima">
                                                                <button type="submit"
                                                                    class="btn btn-sm bg-gradient-warning">Diterima</button>
                                                            @break
                                                            @endswitch
                                                        </form>
                                                    </td>
                                                </tr>
                                                
                                                @elseif ($invoice->payment_method == 'transfer' && $invoice->status == 'Lunas' && $invoice->is_pickup == 0 &&
                                                in_array($order->status, ['Dipesan', 'Dikonfirmasi/Dikemas']))
                                                <tr>
                                                    <th>Update Pesanan</th>
                                                        <td>:</td>
                                                    <td>
                                                        <form action="{{ route('su.order.update-status', ['invoice' => $invoice->id]) }}" method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            @switch($order->status)
                                                            @case('Dipesan')
                                                                <input type="hidden" name="status"
                                                                    value="Dikonfirmasi/Dikemas">
                                                                <button type="submit"
                                                                    class="btn btn-sm bg-gradient-warning">Konfirmasi
                                                                    / Dikemas</button>
                                                            @break

                                                            @case('Dikonfirmasi/Dikemas')
                                                                <input type="hidden" name="status" value="Dikirim">
                                                                <button type="submit"
                                                                    class="btn btn-sm bg-gradient-warning">Dikirim</button>
                                                            @break
                                                            @endswitch
                                                        </form>
                                                    </td>
                                                </tr>
   
                                        @endif
                                    
                                @endforeach
                                <tr>
                                            <th>Status Pembayaran</th>
                                            <td>:</td>
                                            <td>
                                                {{ $invoice->status }}
                                            </td>
                                        </tr>
                                        @foreach ($invoice->order as $order)
                                            @if ($invoice->payment_method == 'cash' && $invoice->status != 'Lunas' && $order->status == 'Dikonfirmasi/Dikemas')
                                                <tr>
                                                    <th>Update Status Pembayaran</th>
                                                    <td>:</td>
                                                    <td>
                                                        <a href="{{ route('su.invoice.confirm-cash', ['invoice' => $invoice->id]) }}"
                                                            class="btn btn-sm bg-gradient-warning">Konfirmasi Lunas</a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @break
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                <a href="{{ route('su.order') }}" class="btn btn-sm bg-gradient-danger">Kembali</a>
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
                <p>{!! Session::get('message') !!}</p>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
