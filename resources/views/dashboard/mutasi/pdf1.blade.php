<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Download Form Mutasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        .container-fluid{
            border-bottom: 2px solid black;
            font-size: 7px;
        }
        .table-bordered {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            font-size: 5px;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid black;
            padding: 2px;
            text-align: left;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid black;
        }
        .alamat {
            text-align: center;
            margin-bottom: 10px;
        }
        .no_telp {
            text-align: center;
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
            margin-top: 40px;
        }
    </style>
  </head>
  <body>

    <div class="container-fluid clearfix">
        <div class="header">
            <div class="judul">
                <h3 class="text-center">Surat Mutasi Barang</h3>
            </div>
            <div class="alamat">
                Jalan 15, weleri. pusat
            </div>
            <div class="no_telp">
                089748484847
            </div>
        </div>
        <table style="font-size: 8px;">
            <tr>
                <td>Kode Mutasi</td>
                <td class="ps-3">:</td>
                <td><b>{{ $mutasis->kode_mutasi }}</b></td>
            </tr>
            <tr>
                <td>Waktu Mutasi</td>
                <td class="ps-3">:</td>
                <td><b>{{ $mutasis->created_at->format('d F Y | H:i') }}</b></td>
            </tr>
        </table>
        <h4 class="mt-3">Detail Mutasi</h2>
        <table class="table table-bordered">
              <thead>
                  <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Pemasok</th>
                    <th>Gudang Asal</th>
                    <th>Gudang Tujuan</th>
                    <th>Jumlah Stok</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($mutasis->detailMutasi as $detailMutasis)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detailMutasis->totalStokGudang->barang->kode_barang }}</td>
                    <td>{{ $detailMutasis->totalStokGudang->barang->nama }}</td>
                    <td>{{ $detailMutasis->totalStokGudang->barang->RRpemasok->nama }}</td>
                    <td>{{ $detailMutasis->gudang_awal }}</td>
                    <td>{{ $detailMutasis->gudangTujuan->nama }}</td>
                    <td>{{ $detailMutasis->jumlah_barang }}</td>
                    <td>{{ $detailMutasis->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                    <td></td>
                  </tr>
                  @endforeach
              </tbody>
          </table>

          <div class="ttd-container clearfix">
            <div class="ttd-nama">
                <p class="text-center">Kepala Gudang</p>
            </div>
            <div class="ttd-tulisan">
                <p class="text-center">Suyono</p>
            </div>
        </div>
      </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>