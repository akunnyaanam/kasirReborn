@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        .container-fluid{
            font-size: 8px;
        }
        .table-bordered {
            border-collapse: collapse;
            width: 100%;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid black;
            padding: 2px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid black;
        }
        .alamat {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .no_telp {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .ttd-container {
            margin-top: 50px;
            width: 30%;
            display: flex;
            float: right;
        }
        .ttd-nama {
            font-weight: bold;
        }
        .ttd-tulisan {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container-fluid clearfix">
        <div class="header">
            <div class="judul">
                <h1 class="text-center">Laporan Histori Transaksi</h1>
            </div>
            <div class="alamat">
                Jalan 15, weleri. pusat
            </div>
            <div class="no_telp">
                089748484847
            </div>
        </div>
        <table>
            <tr style="font-size: 15px;">
                <td><p>Tanggal</p></td>
                <td><p class="ms-3">:</p></td>
                <td><p>{{ Carbon::parse($tanggal)->isoFormat('LL') }}</p></td>
            </tr>
        </table>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Waktu Transaksi</th>
                    <th>Nama Karyawan</th>
                    <th>Total Keseluruhan</th>
                    <th>Uang Pembayaran</th>
                    <th>Uang Kembalian</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Total Pembelian</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksis as $index => $transaksi)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $transaksi->kode_transaksi }}</td>
                        <td>{{ $transaksi->created_at->isoFormat('LLLL') }}</td>
                        <td>{{ $transaksi->user->name }}</td>
                        <td>Rp {{ number_format($transaksi->harga_total, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaksi->uang_pembayaran, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaksi->uang_kembalian, 2, ',', '.') }}</td>
                        <td>
                            @foreach ($transaksi->detailTransaksis as $detail)
                                {{ $detail->barangtoko->barang->kode_barang }} |
                                {{ $detail->barangtoko->barang->nama }}<br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($transaksi->detailTransaksis as $detail)
                                {{ $detail->jumlah }}<br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($transaksi->detailTransaksis as $detail)
                                Rp {{ number_format($detail->total, 2, ',', '.') }}<br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
      </div>
</body>
</html>
