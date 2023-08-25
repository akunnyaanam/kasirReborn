@extends('dashboard.layouts.main') 
@extends('dashboard.layouts.nav')
@section('container')

{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-11 pt-3">
                    <h1 class="my-3">Detail Pengeluaran</h1>
                    <div class="card mt-4">
                        <div class="card-header" style="align-items: center">
                            <i class="fas fa-table me-1"></i>
                            Detail Pengeluaran
                            {{-- <a href="/dashboard/detail/stokgudang" class="btn btn-dark" style="float: right;">Kembali</a> --}}
                        </div>
                        <div>
                            <form action="{{ route('filter.pengeluaran') }}" method="POST">
                                @csrf
                                <div class="d-flex ps-3 pt-3 justify-content-center align-items-center" style="width: 40%;">
                                    <label for="tanggal" style="width: 150px;" class="form-label">Pilih Tanggal</label>
                                    <input type="date" class="form-control me-3" id="tanggal" name="tanggal" required>
                                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">   
                            <table id="datatablesSimple">
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
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <form action="{{ route('generate.pdf') }}" method="post" target="_blank">
                                @csrf
                                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                <button type="submit" class="btn btn-primary">Generate PDF</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection