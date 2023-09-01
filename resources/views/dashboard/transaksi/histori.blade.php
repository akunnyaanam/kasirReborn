@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp
@extends('dashboard.layouts.main') @extends('dashboard.layouts.nav')
@section('container')
{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12 px-3 py-3">
                    <h1 class="my-3">Histori Transaksi</h1>
                    <div class="card mt-4">
                        <div class="card-header" style="align-items: center">
                            <i class="fas fa-table me-1"></i>
                            Histori Transaksi
                            {{-- <a href="/dashboard/detail/stokgudang" class="btn btn-dark" style="float: right;">Kembali</a> --}}
                        </div>
                        <div>
                            <form id="filterForm" action="{{ route('histori.transaksi') }}" method="POST">
                                @csrf
                                <div class="d-flex ps-3 pt-3 justify-content-center align-items-center" style="width: 40%;">
                                    <label for="tanggal" style="width: 150px;" class="form-label">Pilih Tanggal</label>
                                    <input type="date" class="form-control me-3" id="tanggal" name="tanggal">
                                </div>
                            </form>                            
                        </div>
                        <div class="card-body">   
                            <table id="datatablesSimple">
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
                                        <th>Harga Jual</th>
                                        <th>Jumlah</th>
                                        <th>Total Pembelian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksiData as $index => $transaksi)
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
                                                {{ $detail->barangtoko->barang->kode_barang }} 
                                                    {{ $detail->barangtoko->barang->nama }}<br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($transaksi->detailTransaksis as $detail)
                                                    Rp {{ number_format($detail->barangtoko->barang->harga_jual, 2, ',', '.') }}<br>
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
                        <div class="card-footer d-flex justify-content-end">
                            <form action="{{ route('generate.pdf') }}" method="post" target="_blank">
                                @csrf
                                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                <button type="submit" class="btn btn-primary">Print</button>
                            </form>
                        </div>                                                          
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection

@section('scripts')
<script>
    // Menangkap perubahan pada input tanggal
    document.getElementById('tanggal').addEventListener('change', function () {
        document.getElementById('filterForm').submit(); // Mengirimkan formulir
    });
</script>
@endsection