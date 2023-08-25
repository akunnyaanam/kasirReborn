<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Download Form SUrat Jalan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        .container-fluid{
            border-bottom: 2px solid black;
        }
        .table-bordered {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
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
            margin-top: 70px;
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
                <h3 class="text-center">Surat Jalan Barang Toko</h3>
            </div>
            <div class="alamat">
                Jalan 15, weleri. pusat
            </div>
            <div class="no_telp">
                089748484847
            </div>
        </div>
        <table>
            <tr>
                <td><p>Kode Surat Jalan</p></td>
                <td><p class="ms-3">:</p></td>
                <td><p>{{ $stoktoko->kode_suratjalan }}</p></td>
            </tr>
            <tr>
                <td><p>Toko Tujuan</p></td>
                <td><p class="ms-3">:</p></td>
                <td><p>{{ $stoktoko->toko->nama }}</p></td>
            </tr>
            <tr>
                <td><p>Waktu Input</p></td>
                <td><p class="ms-3">:</p></td>
                <td><p>{{ $stoktoko->created_at->format('d F Y | H:i') }}</p></td>
            </tr>
        </table>
        <h4 class="mt-3">Detail Surat Jalan</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Gudang</th>
                    <th>Stok Kirim</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stoktoko->detailStokTokos as $dataa)
                    <tr>    
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dataa->barang->barang->kode_barang }}</td>
                        <td>{{ $dataa->barang->barang->nama }}</td>
                        <td>{{ $dataa->barang->stokgudang->gudang->nama }}</td>
                        <td>{{ $dataa->stok }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        

          <div class="ttd-container clearfix">
            <div class="ttd-nama">
                <p class="text-center">Penanggung Jawab</p>
            </div>
            <div class="ttd-tulisan">
                <p class="text-center">Suyono</p>
            </div>
        </div>
      </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>