<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body { font-family: sans-serif; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #000; padding:6px; }
    </style>
</head>
<body>

<h2>INVOICE</h2>

<p>
    Nama: {{ $trx->name }} <br>
    Email: {{ $trx->email }} <br>
    Tanggal: {{ $trx->created_at->format('d-m-Y') }}
</p>

<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
<tr>
    <td>{{ $trx->produk->name }}</td>
    <td>{{ $trx->quantity }}</td>
    <td>{{ number_format($trx->price) }}</td>
    <td>{{ number_format($trx->price * $trx->quantity) }}</td>
</tr>
</tbody>

</table>

<h3>Total: {{ number_format($trx->grand_total) }}</h3>

</body>
</html>
