<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Toko Buah</title>
</head>

<body>
    <div class="container">
        <h1 class="my-3">Invoice</h1>

        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Detail Pesanan</h5>
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
                    <tr>
                        <td>Status</td>
                        <td>: {{ $order->status }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>
