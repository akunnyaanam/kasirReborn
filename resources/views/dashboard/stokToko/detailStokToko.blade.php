@extends('dashboard.layouts.main') 
@extends('dashboard.layouts.nav')
@section('container')

{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')
  <div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-5 pt-4">
            <h1>Data Surat Jalan Barang</h1>
            <a href="/dashboard/stoktoko/tambah" class="btn btn-primary">Kirim Barang ke Toko</a><br>
            @foreach ($toko as $data)
                <a href="{{ route('toko.barang', ['toko_id' => $data->id]) }}" class="btn btn-success mt-2">
                    Daftar Barang di Toko {{ $data->nama }}
                </a>
            @endforeach  
            <table class="table table-bordered mt-3">
                <thead>
                    <tr class="text-center">
                        <th>Kode Surat Jalan</th>
                        <th>Toko Tujuan</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Gudang</th>
                        <th>Stok Kirim</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stoktoko as $dataa)
                        <tr>
                            @if ($dataa->detailStokTokos)
                                <td rowspan="{{ count($dataa->detailStokTokos) }}" class="text-center align-middle">{{ $dataa->kode_suratjalan }}</td>
                                <td rowspan="{{ count($dataa->detailStokTokos) }}" class="text-center align-middle">{{ $dataa->toko->nama }}</td>
                                @foreach ($dataa->detailStokTokos as $index => $detailStok)
                                    @if ($index !== 0)
                                        <tr>
                                    @endif
                                    <td>{{ $detailStok->barang->barang->kode_barang }}</td>
                                    <td>{{ $detailStok->barang->barang->nama }}</td>
                                    <td>{{ $detailStok->barang->stokgudang->gudang->nama }}</td>
                                    <td>{{ $detailStok->stok }}</td>
                                    @if ($index === 0)
                                        <td rowspan="{{ count($dataa->detailStokTokos) }}" class="text-center align-middle">
                                            <a href="{{ route('cetak.pdf', ['id' => $dataa->id, 'ukuran' => 'a4']) }}" class="btn btn-primary" target="_blank">Cetak A4</a>
                                            <a href="{{ route('cetak.pdf', ['id' => $dataa->id, 'ukuran' => 'a6']) }}" class="btn btn-primary" target="_blank">Cetak A6</a>
                                        </td>
                                    @endif
                                    @if ($index !== 0)
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>                                   
            
             {{-- <table class="table table-bordered mt-3">
                <thead>
                    <tr class="text-center">
                        <th>Kode Surat Jalan</th>
                        <th>Toko Tujuan</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Gudang</th>
                        <th>Stok Kirim</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stoktoko as $dataa)
                        <tr>
                            <td class="text-center align-middle">{{ $dataa->kode_suratjalan }}</td>
                            <td class="text-center align-middle">{{ $dataa->toko->nama }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @foreach ($dataa->detailStokToko as $detailStok)
                            @foreach ($detailStok->historiDetailStokToko as $histori)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $detailStok->barang->barang->kode_barang }}</td>
                                    <td>{{ $detailStok->barang->barang->nama }}</td>
                                    <td>{{ $detailStok->barang->stokgudang->gudang->nama }}</td>
                                    <td>{{ $histori->stok }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach

                </tbody>
                    
            </table> --}}
        </div>
    </main>
</div>

@endsection

{{-- @foreach ($stoktoko as $dataa)
                        <tr>
                            <td class="text-center align-middle">{{ $dataa->kode_suratjalan }}</td>
                            <td class="text-center align-middle">{{ $dataa->toko->nama }}</td>
                            <td>
                                @foreach ($dataa->detailStokToko as $detailStok)
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Gudang</th>
                                                <th>Stok Kirim</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailStok->historiDetailStokToko as $histori)
                                                <tr>
                                                    <td>{{ $histori->detailStokToko->barang->barang->kode_barang }}</td>
                                                    <td>{{ $histori->detailStokToko->barang->barang->nama }}</td>
                                                    <td>{{ $histori->detailStokToko->barang->stokgudang->gudang->nama }}</td>
                                                    <td>{{ $histori->stok }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach --}}

                    {{-- @foreach ($stoktoko as $dataa)
                        <tr>
                            <td class="text-center align-middle">{{ $dataa->kode_suratjalan }}</td>
                            <td class="text-center align-middle">{{ $dataa->toko->nama }}</td>
                            <td>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Gudang</th>
                                            <th>Stok Kirim</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataa->detailStokToko as $detailStok)
                                            @foreach ($detailStok->historiDetailStokToko as $histori)
                                            <tr>
                                                <td>{{ $histori->detailStokToko->barang->barang->kode_barang }}</td>
                                                <td>{{ $histori->detailStokToko->barang->barang->nama }}</td>
                                                <td>{{ $histori->detailStokToko->barang->stokgudang->gudang->nama }}</td>
                                                <td>{{ $histori->stok }}</td>
                                            </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach --}}
                    {{-- @foreach ($stoktoko as $dataa)
                        <tr>
                            <td class="text-center align-middle">{{ $dataa->kode_suratjalan }}</td>
                            <td class="text-center align-middle">{{ $dataa->toko->nama }}</td>
                            <td>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Gudang</th>
                                            <th>Stok Kirim</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataa->detailStokToko as $data)
                                            @foreach ($data->historiDetailStokTokos as $histori)
                                                <tr>
                                                    <td>{{ $data->barang->barang->kode_barang }}</td>
                                                    <td>{{ $data->barang->barang->nama }}</td>
                                                    <td>{{ $data->barang->stokgudang->gudang->nama }}</td>
                                                    <td>{{ $histori->stok }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td>{{ $data->barang->barang->kode_barang }}</td>
                                                <td>{{ $data->barang->barang->nama }}</td>
                                                <td>{{ $data->barang->stokgudang->gudang->nama }}</td>
                                                <td>{{ $data->stok }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('stoktoko.cetakPdf', ['id' => $stoktoko->id, 'ukuran' => 'a4']) }}" target="_blank" class="btn btn-primary">Cetak A4</a>
                                <a href="{{ route('stoktoko.cetakPdf', ['id' => $stoktoko->id, 'ukuran' => 'a6']) }}" target="_blank" class="btn btn-primary">Cetak A6</a>

                                <a href="{{ route('mutasi.cetakPdf', ['id' => $mutasi->id]) }}" class="btn btn-warning">Cetak</a>
                            </td>
                        </tr>
                    @endforeach --}}