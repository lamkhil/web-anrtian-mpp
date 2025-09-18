<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Antrian SKCK Hari Ini</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; text-align: center; padding: 4px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h3 style="text-align: center;">Antrian SKCK Hari Ini</h3>
    <h4 style="text-align: center;">Tanggal: {{ date('d M Y') }}</h4>
    <div style="text-align: center;"> 
        <img style="text-align: center; width:250px;" src="data:image/png;base64,{{ $logo }}" alt="Logo">
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Nomor Antrian</th>
                <th>No. Whatsapp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ strtoupper($row->nama) }}</td>
                    <td>{{ $row->nik }}</td>
                    <td>{{ str_pad($row->antrian, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $row->nomor_whatsapp }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
