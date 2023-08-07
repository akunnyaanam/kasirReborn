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
        
        <form action="{{ url('add-gudang') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="">Kode Gudang</label>
                    <input type="text" name="kode_gudang" required value="{{ $nomer }}" readonly class="form-control bg-light">    
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
        
        <form action="{{ url('update-gudang') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="gudang_id" id="gudang_id">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="">Kode Gudang</label>
                    <input type="text" name="kode_gudang" id="kode_gudang" readonly required class="form-control bg-light">    
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
        
        <form action="{{ url('delete-gudang') }}" method="POST">
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
        <div class="container-fluid px-4">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            <h1 class="my-4 text-center">Data Gudang</h1>
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Tambah Gudang
            </button>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Gudang</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($gudang as $data)
                        <tr>
                            <td>{{ $i++; }}</td>
                            <td>{{ $data->kode_gudang }}</td>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->alamat }}</td>
                            <td>
                                <button type="submit" value="{{ $data->id }}" class="btn btn-primary btn-sm" id="editbtn">Edit</button>
                                <button type="button" value="{{ $data->id }}" class="btn btn-danger btn-sm" id="deletebtn">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; Your Website 2023</div>
                <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready( function() {
            $(document).on('click', '#deletebtn', function () {
                var gudang_id = $(this).val();
                $('#deleteModal').modal('show'); 
                $('#deleting_id').val(gudang_id); 

                $.ajax({
                    type: "GET",
                    url: "/edit-gudang/"+gudang_id,
                    success: function (response){
                        $('#kode_gudang').val(response.gudang.kode_gudang);
                        $('#nama').val(response.gudang.nama);
                        $('#alamat').val(response.gudang.alamat);
                        $('#gudang_id').val(gudang_id);
                    }
                });
            });
            $(document).on('click', '#editbtn', function () {
                var gudang_id = $(this).val();
                $('#editModal').modal('show'); 

                $.ajax({
                    type: "GET",
                    url: "/edit-gudang/"+gudang_id,
                    success: function (response){
                        $('#kode_gudang').val(response.gudang.kode_gudang);
                        $('#nama').val(response.gudang.nama);
                        $('#alamat').val(response.gudang.alamat);
                        $('#gudang_id').val(gudang_id);
                    }
                });
            });
        });
    </script>
@endsection