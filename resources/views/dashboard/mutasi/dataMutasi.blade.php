@extends('dashboard.layouts.main') 
@extends('dashboard.layouts.nav')
@section('container')

{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')
  <div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-5 pt-4">
            <h1>Data Mutasi</h1>
            <a href="/dashboard/mutasi/tambah" class="btn btn-primary">Mutasi</a>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr class="text-center">
                            <th>Kode Mutasi</th>
                            <th>Detail Mutasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mutasis as $mutasi)
                            <tr>
                                <td class="text-center align-middle">{{ $mutasi->kode_mutasi }}</td>
                                <td>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Gudang Asal</th>
                                                <th>Gudang Tujuan</th>
                                                <th>Waktu</th>
                                                <th>Jumlah Stok</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mutasi->detailMutasi as $detailMutasis)
                                                <tr>
                                                    <td>{{ $detailMutasis->totalStokGudang->barang->kode_barang ?? '-' }}</td>
                                                    <td>{{ $detailMutasis->totalStokGudang->barang->nama ?? '-' }}</td>
                                                    <td>{{ $detailMutasis->gudang_awal }}</td>
                                                    <td>{{ $detailMutasis->gudangTujuan ? $detailMutasis->gudangTujuan->nama : '-' }}</td>
                                                    <td>{{ $detailMutasis->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}
                                                    </td>
                                                    <td>{{ $detailMutasis->jumlah_barang ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('mutasi.cetakPdf', ['id' => $mutasi->id, 'ukuran' => 'a4']) }}" target="_blank" class="btn btn-primary">Cetak A4</a>
                                    <a href="{{ route('mutasi.cetakPdf', ['id' => $mutasi->id, 'ukuran' => 'a6']) }}" target="_blank" class="btn btn-primary">Cetak A6</a>

                                    {{-- <a href="{{ route('mutasi.cetakPdf', ['id' => $mutasi->id]) }}" class="btn btn-warning">Cetak</a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </main>
</div>

@endsection