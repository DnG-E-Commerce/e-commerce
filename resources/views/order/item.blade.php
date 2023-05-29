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
        <h1 class="my-3">Toko Buah</h1>

        <div class="card" style="width: 18rem;">
            <img src="{{ asset('assets/img/apple2.jpg') }}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Apple</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                    card's content.</p>
                <form action="/checkout" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="qty" class="form-label">Barang dibeli</label>
                        <input type="number" name="qty" class="form-control" id="qty"
                            placeholder="Jumlah barang yang dipesan">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="Masukkan nama anda">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">No. HP</label>
                        <input type="text" name="phone" class="form-control" id="phone"
                            placeholder="Masukkan no. hp">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" id="address" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Checkout</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>
