<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title>Invoice</title>
            <style>
            body {
                font-family: Arial, sans-serif;
            }
    
            .invoice {
                width: 80%;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ccc;
            }
    
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .logo {
                max-width: 150px; /* Set the maximum width for the logo */
                margin-bottom: 20px;
                text-align: center;
                
            }

            .address {
                margin-bottom: 20px;
                text-align: center;
            }
    
            th, td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ccc;
            }
    
            th {
                background-color: #f2f2f2;
            }
    
            .total {
                text-align: right;
            }
        </style> 
        </head>
        <body>
        <div class='invoice'>
        <div class='logo'>
    <img src='argon/img/logos/logo.png' >
</div>
    <div class='address'>
        <p>D&G Store</p>
        <p>Gg. Gn. Tangkuban Perahu No.12, Pasirkareumbi, 
        Kec. Subang, <br> Subang, Jawa Barat 41211 <br> 
        Phone: 082319244700
        <br>Instagram: @dapur.jajanan.subang</p>
    </div>
        <h2>Invoice</h2>
        <hr class='border border-1 border-dark'>
                    <div class='col-lg-6'>
                        <table class='table'>
                            <tr>
                                <th>No Invoice</th>
                                <td>:</td>
                                <td>$invoice->invoice_code</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>:</td>
                                <td>" . substr($invoice->created_at, 0, 10) . "</td>
                            </tr>
                            <tr>
                                <th>Nama Pemesan</th>
                                <td>:</td>
                                <td>";
        foreach ($invoice->order as $key => $order) {
            $html .= $order->user->name;
            break;
        }
        $html .= "</td>
                            </tr>
                            <tr>
                            <th>No Telp</th>
                            <td>:</td>
                            <td>";
    foreach ($invoice->order as $key => $order) {
        $html .= $order->user->phone;
        break;
    }
    $html .= "</td>
                        </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>:</td>
                                <td>";
        $invoice->send_to ? $html .= $invoice->send_to : $html .= "-";
        $html .= "</td>
                            </tr>
                            
                        </table>
                    </div>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> ";
                    foreach ($invoice->order as $o => $order) {
                        $html .= $order->product->name;
                    }
                    $html .= "</td>
                    <td> ";
                    foreach ($invoice->order as $o => $order) {
                        $html .= $order->qty;
                    }
                    $html .= "</td>
                    <td> ";
                    foreach ($invoice->order as $o => $order) {
                        $html .= number_format($order->product->reseller_price ? $order->product->reseller_price : $order->product->customer_price, 0, ',', '.' )  ;
                    }
                    $html .= "</td>
                    <td>";
                    foreach ($invoice->order as $o => $order) {
                        $html .=  number_format($order->total, 0, ',', '.')    ;
                    }
                    $html .= "</td>
                </tr>
                
            </tbody>
            <tfoot>
                <tr>
                    <td colspan='3' class='total'>Ongkir:</td>
                    <td>" . number_format($invoice->ongkir, 0, ',', '.') . "</td>
                </tr>
                <tr>
                    <td colspan='3' class='total'>Total:</td>
                    <td>" . number_format($invoice->grand_total, 0, ',', '.') . "</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

        </html>
        ";
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->stream();
</body>
</html>