@extends('dashboard.layouts.main') @extends('dashboard.layouts.nav')
@section('container')
{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ url('add-toko') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="">Kode Toko</label>
                        <input type="text" name="kode_toko" required value="{{ $nomer }}" readonly
                            class="form-control bg-light">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Nama</label>
                        <input type="text" name="nama" required class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Alamat</label>
                        <input type="text" name="alamat" required class="form-control">
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

            <form action="{{ url('update-toko') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="toko_id" id="toko_id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="">Kode Toko</label>
                        <input type="text" name="kode_toko" id="kode_toko" readonly required
                            class="form-control bg-light">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Nama</label>
                        <input type="text" name="nama" id="nama" required class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Alamat</label>
                        <input type="text" name="alamat" id="alamat" required class="form-control">
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

            <form action="{{ url('delete-toko') }}" method="POST">
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
        <div class="container-fluid">
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
                            <button type="button" class="btn btn-primary btn-sm " data-bs-toggle="modal" data-bs-target="#exampleModal" style="float: right;">
                                <i class="fa-regular fa-square-plus"></i> Tambah Toko
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pemasok</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($toko as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->kode_toko }}</td>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->alamat }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group"
                                                aria-label="Small button group">
                                                <button type="submit" value="{{ $data->id }}"
                                                    class="btn btn-warning btn-sm me-2" id="editbtn"><i
                                                        class="fa-solid fa-pencil "></i></button>
                                                <button type="button" value="{{ $data->id }}"
                                                    class="btn btn-danger btn-sm" id="deletebtn"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="card-footer d-flex justify-content-end">
                            <button type="button" class="btn btn-primary  btn-sm " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="fa-regular fa-square-plus"></i> Tambah Toko
                            </button>
                        </div> --}}
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
            var toko_id = $(this).val();
            $('#deleteModal').modal('show');
            $('#deleting_id').val(toko_id);

            $.ajax({
                type: "GET",
                url: "/edit-toko/" + toko_id,
                success: function (response) {
                    $('#kode_toko').val(response.toko.kode_toko);
                    $('#nama').val(response.toko.nama);
                    $('#alamat').val(response.toko.alamat);
                    $('#toko_id').val(toko_id);
                }
            });
        });
        $(document).on('click', '#editbtn', function () {
            var toko_id = $(this).val();
            $('#editModal').modal('show');

            $.ajax({
                type: "GET",
                url: "/edit-toko/" + toko_id,
                success: function (response) {
                    $('#kode_toko').val(response.toko.kode_toko);
                    $('#nama').val(response.toko.nama);
                    $('#alamat').val(response.toko.alamat);
                    $('#toko_id').val(toko_id);
                }
            });
        });
    });

</script>
@endsection
