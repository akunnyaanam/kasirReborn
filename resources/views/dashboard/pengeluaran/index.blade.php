@extends('dashboard.layouts.main')
@extends('dashboard.layouts.nav')
@section('container')

{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Pengeluaran</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ url('add-pengeluaran') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="">Kode Pengeluaran</label>
                        <input type="text" name="kode_pengeluaran" required value="{{ $nomer }}" readonly
                            class="form-control bg-light">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Deskripsi</label>
                        <input type="text" name="deskripsi" required class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Jumlah Pengeluaran</label>
                        <input type="text" name="jumlah" required class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- edit --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ url('update-pengeluaran') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="pengeluaran_id" id="pengeluaran_id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="">Kode Pengeluaran</label>
                        <input type="text" name="kode_pengeluaran" id="kode_pengeluaran" readonly required
                            class="form-control bg-light">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Deskripsi</label>
                        <input type="text" name="deskripsi" id="deskripsi" required class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Jumlah Pengeluaran</label>
                        <input type="text" name="jumlah" id="jumlah" required class="form-control">
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

{{-- delete --}}
<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ url('delete-pengeluaran') }}" method="POST">
                @csrf
                @method('DELETE')
                <h5 class="my-3 ms-3">Yakin Ingin Dihapus?</h5>
                <input type="hidden" id="deleting_id" name="deleting_id">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- enddelete --}}


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid pt-3">
            <div class="row justify-content-center">
                <div class="col-lg-11 pt-3">
                    <div class="row justify-content-between">
                        <div class="col-lg-5">
                            <h1 class="mt-4">{{ $title }}</h1>
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item active">{{ $desc }}</li>
                            </ol>
                            <br>
                        </div>
                        <div class="col-lg-5">
                            @if (session('status'))
                            <div class="alert alert-success p-2 text-center">{{ session('status') }}</div>
                            @endif
                            @if($errors->any())
                            <div class="alert alert-danger p-2 text-center">
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            {{ $tableTitle }}
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" style="float: right;">
                                <i class="fa-regular fa-square-plus"></i> Tambah Pengeluaran
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pengeluaran</th>
                                        <th>Deskripsi</th>
                                        <th>Jumlah Pengeluaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengeluaran as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->kode_pengeluaran }}</td>
                                        <td>{{ $data->deskripsi }}</td>
                                        <td>Rp {{ number_format($data->jumlah, 2, ',', '.') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                @if ($data->detailPengeluaran && $data->detailPengeluaran->status == 'terverifikasi')
                                                    <!-- Tampilkan tombol jika status terverifikasi -->
                                                    <button type="button" class="btn btn-warning btn-sm me-2 edit-btn" id="editbtn" disabled><i class="fa-solid fa-pencil"></i></button>
                                                    <button type="button" class="btn btn-danger btn-sm me-2 delete-btn" id="deletebtn" disabled><i class="fa-solid fa-trash"></i></button>
                                                    <button type="submit" class="btn btn-success-border-subtle btn-sm checklist-btn" disabled><i class="fa-solid fa-check"></i> sudah Terverifikasi</button>
                                                @else
                                                    <!-- Tampilkan tombol jika status belum ada -->
                                                    <button type="button" value="{{ $data->id }}" class="btn btn-warning btn-sm me-2 edit-btn" id="editbtn"><i class="fa-solid fa-pencil"></i></button>
                                                    <button type="button" value="{{ $data->id }}" class="btn btn-danger btn-sm me-2 delete-btn" id="deletebtn"><i class="fa-solid fa-trash"></i></button>
                                                    <form action="{{ route('verifikasi.pengeluaran', ['id' => $data->id]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm checklist-btn">Verifikasi</button>
                                                    </form>
                                                @endif
                                            </div>                         
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    $(document).ready(function () {
        $(document).on('click', '#deletebtn', function () {
            var pengeluaran_id = $(this).val();
            $('#deleteModal').modal('show');
            $('#deleting_id').val(pengeluaran_id);

            $.ajax({
                type: "GET",
                url: "/edit-pengeluaran/" + pengeluaran_id,
                success: function (response) {
                    $('#kode_pengeluaran').val(response.pengeluaran.kode_pengeluaran);
                    $('#deskripsi').val(response.pengeluaran.deskripsi);
                    $('#jumlah').val(response.pengeluaran.jumlah);
                    $('#pengeluaran_id').val(pengeluaran_id);
                }
            });
        });
        $(document).on('click', '#editbtn', function () {
            var pengeluaran_id = $(this).val();
            $('#editModal').modal('show');

            $.ajax({
                type: "GET",
                url: "/edit-pengeluaran/" + pengeluaran_id,
                success: function (response) {
                    $('#kode_pengeluaran').val(response.pengeluaran.kode_pengeluaran);
                    $('#deskripsi').val(response.pengeluaran.deskripsi);
                    $('#jumlah').val(response.pengeluaran.jumlah);
                    $('#pengeluaran_id').val(pengeluaran_id);
                }
            });
        });
    });

</script>


@endsection
