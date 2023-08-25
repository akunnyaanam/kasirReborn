<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        .container-fluid{
            border-bottom: 2px solid black;
            font-size: 10px;
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
        @media print {
            @page {
                size: a4;
                margin: 0;
            }

            /* Gaya khusus untuk versi cetak A6 */
            @page :left {
                size: a6;
            }
            @page :right {
                size: a6;
            }

            .container-fluid {
                padding: 10px;
                font-size: 12px; /* Ukuran font lebih kecil pada versi cetak A6 */
            }
        }
    </style>
  </head>
  <body>

    <div class="container-fluid clearfix">
        <div class="header">
            <div class="judul">
                <h1 class="text-center">Laporan Pengeluaran Keuangan</h1>
            </div>
            <div class="alamat">
                Jalan 15, weleri. pusat
            </div>
            <div class="no_telp">
                089748484847
            </div>
        </div>
        <table>
            @php use Carbon\Carbon; @endphp
            <tr>
                <td><p>Hari, Tanggal</p></td>
                <td><p class="ms-3">:</p></td>
                <td><p>{{ Carbon::parse($tanggal)->format('l, j F Y') }}</p></td>
            </tr>
        </table>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Pengeluaran</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Pengeluaran</th>
                    <th>Waktu Masuk</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detailPengeluaran as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->pengeluaran->kode_pengeluaran }}</td>
                        <td>{{ $data->pengeluaran->deskripsi }}</td>
                        <td>Rp {{ number_format($data->pengeluaran->jumlah, 2, ',', '.') }}</td>
                        <td>{{ $data->pengeluaran->created_at->format('d F Y | H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        

          {{-- <div class="ttd-container clearfix">
            <div class="ttd-nama">
                <p class="text-center">Penanggung Jawab</p>
            </div>
            <div class="ttd-tulisan">
                <p class="text-center">Suyono</p>
            </div>
        </div> --}}
      </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>
