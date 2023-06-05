<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->

    <title>Toko Buah</title>
</head>

<body>
    <div class="container">
        <h1 class="my-3">Toko Buah</h1>

        <div class="card" style="width: 18rem;">
            <img src="{{ asset('assets/img/apple2.jpg') }}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Detail Barang</h5>
                <table>
                    <tr>
                        <td>Nama</td>
                        <td>: {{ $users->name }}</td>
                    </tr>
                    <tr>
                        <td>No. Hp</td>
                        <td>: {{ $users->phone }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $users->address }}</td>
                    </tr>
                    <tr>
                        <td>Qty</td>
                        <td>: {{ $order->qty }}</td>
                    </tr>
                    <tr>
                        <td>Total Harga</td>
                        <td>: {{ $order->total_price }}</td>
                    </tr>
                </table>
                <button class="btn btn-primary mt-3" id="pay-button">Bayar sekarang</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        // For example trigger on button clicked, or any time you need
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    /* You may add your own implementation here */
                    // alert("payment success!");
                    window.location.href = '/invoice/{{ $order->id }}';
                    console.log(result);
                },
                onPending: function(result) {
                    /* You may add your own implementation here */
                    alert("wating your payment!");
                    console.log(result);
                },
                onError: function(result) {
                    /* You may add your own implementation here */
                    alert("payment failed!");
                    console.log(result);
                },
                onClose: function() {
                    /* You may add your own implementation here */
                    alert('you closed the popup without finishing the payment');
                }
            })
        });
    </script>
</body>

</html>
