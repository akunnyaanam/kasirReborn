@extends('dashboard.layouts.main') @extends('dashboard.layouts.nav')
@section('container')
{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Form Tambah Barang</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <form action="{{ url('add-barang') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="">Kode Barang</label>
                    <input type="text" name="kode_barang" readonly required class="form-control bg-light" value="{{ $nomer; }}">    
                </div>    
                <div class="form-group mb-3">
                    <label for="">Nama</label>
                    <input type="text" name="nama" required class="form-control">    
                </div>    
                <div class="form-group mb-3">
                    <label for="">Jenis Barang</label>
                    <select name="kode_jenis" class="form-control">
                        <option selected>Pilih Jenis Barang</option>
                        @foreach($jenisBarang as $data)
                            <option value="{{ $data->id }}">{{ $data->kategori_barang }}</option>
                        @endforeach
                    </select>
                </div>    
                <div class="form-group mb-3">
                    <label for="">Pemasok</label>
                    <select name="kode_pemasok" class="form-control">
                        <option selected>Pilih Pemasok</option>
                        @foreach($pemasok as $data)
                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                        @endforeach
                    </select>
                </div>    
                <div class="form-group mb-3">
                    <label for="">Harga Pemasok</label>
                    <input type="text" name="harga" required class="form-control">    
                </div>
                <div class="form-group mb-3">
                    <label for="">Harga Jual</label>
                    <input type="text" name="harga_jual" required class="form-control">    
                </div>
                <div class="form-group mb-3">
                    <label for="">Gudang</label>
                    <select name="kode_gudang" class="form-control">
                        <option selected>Pilih Gudang</option>
                        @foreach($gudang as $data)
                        <option value="{{ $data->id }}">{{ $data->nama }}</option>
                        @endforeach
                    </select>
                </div>    
                <div class="form-group mb-3">
                    <label for="">Stok</label>
                    <input type="text" name="stok" required class="form-control">    
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
          <h1 class="modal-title fs-5" id="exampleModalLabel">Form Edit Data</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <form action="{{ url('update-barang') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="barang_id" id="barang_id">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="">Kode Barang</label>
                    <input type="text" name="kode_barang" id="kode_barang" readonly required class="form-control bg-light">    
                </div>    
                <div class="form-group mb-3">
                    <label for="">Nama</label>
                    <input type="text" name="nama" id="nama" required class="form-control">    
                </div>  
                <div class="form-group mb-3">
                    <label for="">Jenis Barang</label>
                    <select name="kode_jenis" id="kode_jenis" class="form-control">
                        @foreach($jenisBarang as $data)
                        <option value="{{ $data->id }}">{{ $data->kategori_barang }}</option>
                        @endforeach
                    </select>
                </div>      
                <div class="form-group mb-3">
                    <label for="">Pemasok</label>
                    <select name="kode_pemasok" id="kode_pemasok" class="form-control">
                        @foreach($pemasok as $data)
                        <option value="{{ $data->id }}">{{ $data->nama }}</option>
                        @endforeach
                    </select>    
                </div>    
                <div class="form-group mb-3">
                    <label for="">Harga</label>
                    <input type="text" name="harga" id="harga" required class="form-control">    
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
          <h1 class="modal-title fs-5" id="exampleModalLabel">Form Hapus Data</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <form action="{{ url('delete-barang') }}" method="POST">
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
            <h1 class="my-4 text-center">Data Barang</h1>
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah Barang</button>
            <a href="/stokGudang" class="btn btn-warning mb-3">Barang ke Gudang</a>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jenis Barang</th>
                        <th>Pemasok</th>
                        <th>Harga Pemasok</th>
                        <th>Harga Jual</th>
                        <th>Gudang</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($barang as $data)
                        <tr>
                            <td>{{ $i++; }}</td>
                            <td>{{ $data->kode_barang }}</td>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->RjenisBarang->kategori_barang }}</td>
                            <td>{{ $data->RRpemasok->nama }}</td>
                            <td>{{ $data->harga }}</td>
                            <td>{{ $data->harga_jual }}</td>
                            <td>{{ $data->Rgudang->nama }}</td>
                            <td>{{ $data->stok }}</td>
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
                var barang_id = $(this).val();
                $('#deleteModal').modal('show'); 
                $('#deleting_id').val(barang_id); 

                $.ajax({
                    type: "GET",
                    url: "/edit-barang/"+barang_id,
                    success: function (response){
                        $('#kode_barang').val(response.barang.kode_barang);
                        $('#kode_jenis_barang').val(response.barang.kode_jenis_barang);
                        $('#nama').val(response.barang.nama);
                        $('#kode_pemasok').val(response.barang.kode_pemasok);
                        $('#harga').val(response.barang.harga);
                        $('#barang_id').val(barang_id);
                    }
                });
            });
            $(document).on('click', '#editbtn', function () {
                var barang_id = $(this).val();
                $('#editModal').modal('show'); 

                $.ajax({
                    type: "GET",
                    url: "/edit-barang/"+barang_id,
                    success: function (response){
                        $('#kode_barang').val(response.barang.kode_barang);
                        $('#kode_jenis_barang').val(response.barang.kode_jenis_barang);
                        $('#nama').val(response.barang.nama);
                        $('#kode_pemasok').val(response.barang.kode_pemasok);
                        $('#harga').val(response.barang.harga);
                        $('#barang_id').val(barang_id);
                    }
                });
            });
        });
    </script>
@endsection