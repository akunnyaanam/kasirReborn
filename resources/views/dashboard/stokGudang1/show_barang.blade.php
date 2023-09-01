@extends('dashboard.layouts.main') 
@extends('dashboard.layouts.nav')
@section('container')

{{-- edit --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ url('update-detailstokgudang') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="detailstokgudang_id" id="detailstokgudang_id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="stok">Stok Sekarang</label>
                        <input type="text" name="stok_sekarang" id="stok_sekarang" readonly class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="tambah_stok">Tambahkan Stok</label>
                        <input type="number" name="tambah_stok" id="tambah_stok" required class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- end edit --}}

{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')
  <div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-11 pt-3">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <h1 class="my-3">Form {{ $tableTitle }}</h1>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>{{ $tableTitle }}
                            <a href="/dashboard/stokgudang" class="btn btn-dark" style="float: right;">Kembali</a>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        {{-- <th>Gudang</th> --}}
                                        <th>Jenis Barang</th>
                                        <th>Pemasok</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($totalstokgudang as $data)
                                        <tr>
                                            @if ($data->total_stok == 0)
                                                <td><h6 style="background-color: #dc3545; color: white; padding: 5px;">{{ $data->barang->kode_barang }}</h6></td>
                                                <td><h6 style="background-color: #dc3545; color: white; padding: 5px;">{{ $data->barang->nama }}</h6></td>
                                                <td><h6 style="background-color: #dc3545; color: white; padding: 5px;">{{ $data->barang->jenisBarang->kategori_barang }}</h6></td>
                                                {{-- <td><h6 style="background-color: #dc3545; color: white; padding: 5px;">{{ $data->gudang->nama }}</h6></td> --}}
                                                <td><h6 style="background-color: #dc3545; color: white; padding: 5px;">{{ $data->barang->RRpemasok->nama }}</h6></td>
                                                <td><h6 style="background-color: #dc3545; color: white; padding: 5px;">Rp {{ number_format($data->barang->harga_beli) }}</h6></td>
                                                <td><h6 style="background-color: #dc3545; color: white; padding: 5px;">Rp {{ number_format($data->barang->harga_jual) }}</h6></td>
                                                <td><h6 style="background-color: #dc3545; color: white; padding: 5px;">STOK KOSONG</h6></td>
                                            @else
                                                <td><h6>{{ $data->barang->kode_barang }}</h6></td>
                                                <td><h6>{{ $data->barang->nama }}</h6></td>
                                                {{-- <td><h6>{{ $data->gudang->nama }}</h6></td> --}}
                                                <td><h6>{{ $data->barang->jenisBarang->kategori_barang }}</h6></td>
                                                <td><h6>{{ $data->barang->RRpemasok->nama }}</h6></td>
                                                <td><h6>Rp {{ number_format($data->barang->harga_beli) }}</h6></td>
                                                <td><h6>Rp {{ number_format($data->barang->harga_jual) }}</h6></td>
                                                <td><h6>{{ $data->total_stok }}</h6></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <h1>Daftar Barang di Gudang {{ $gudang->nama }}</h1>
            <table>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                </tr>
                @foreach ($gudang->stokGudangs as $stok)
                    @foreach ($stok->detail as $detil)
                        <tr>
                            <td>{{ $detil->barang->kode_barang }}</td>
                            <td>{{ $detil->barang->nama }}</td>
                            <td>{{ $detil->stok }}</td>
                        </tr>
                    @endforeach
                @endforeach

            </table> --}}
        </div>
    </main>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.editStok').on('click', function () {
            var detailstokgudang_id = $(this).data('id');
            
            $.ajax({
                type: "GET",
                url: "/edit-detailstokgudang/" + detailstokgudang_id,
                success: function (response) {
                    $('#stok_sekarang').val(response.detailstokgudang.stok);
                    $('#detailstokgudang_id').val(detailstokgudang_id);
                    $('#editModal').modal('show');
                }
            });
        });
    });
</script>
@endsection