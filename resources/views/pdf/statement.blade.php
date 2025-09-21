<html>
<head>
    <meta charset="utf-8" />
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f4f6; text-align: left; }
    </style>
</head>
<body>
    <h3>Cari Ekstre - {{ $customer->name }}@if($customer->surname) {{ ' ' . $customer->surname }}@endif</h3>
    <p>Tarih: {{ now()->format('d.m.Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Açıklama</th>
                <th style="text-align:right">Borç</th>
                <th style="text-align:right">Alacak</th>
                <th style="text-align:right">Bakiye</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $r)
            <tr>
                <td>{{ $r['date'] }}</td>
                <td>{{ $r['desc'] }}</td>
                <td style="text-align:right">₺{{ $r['debit'] }}</td>
                <td style="text-align:right">₺{{ $r['credit'] }}</td>
                <td style="text-align:right">₺{{ $r['balance'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top:10px">Cari Bakiye: <strong>₺{{ number_format($balance,2,',','.') }}</strong></p>
</body>
</html>

