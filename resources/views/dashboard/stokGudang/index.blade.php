@extends('dashboard.layouts.main') 
@extends('dashboard.layouts.nav')
@section('container')

{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')
  <div id="layoutSidenav_content">
    <main>
        <div class="container-fluid pt-3">
            <button type="button" class="btn btn-info btn-sm tomboltambahbanyak">
                <i class="fa fa-plus-circle"></i> tambah data banyak    
            </button>        
            <div>
                <p class="card-text viewdata"></p>
            </div>
            <div class="viewmodal">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Gudang</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($stokGudang as $data)
                            <tr>
                                <td>{{ $i++; }}</td>
                                <td>{{ $data->RRstokbarang->nama }}</td>
                                <td>{{ $data->RRstokgudang->nama }}</td>
                                <td>{{ $data->stok_gudang }}</td>
                                <td>
                                    <button type="submit" value="{{ $data->id }}" class="btn btn-primary btn-sm" id="editbtn">Edit</button>
                                    <button type="button" value="{{ $data->id }}" class="btn btn-danger btn-sm" id="deletebtn">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // function dataStokGudang(){
    //     $.ajax({
    //         url: "{{ url('dashboard/stokGudang') }}", // Menggunakan sintaks Blade untuk menghasilkan URL Laravel
    //         dataType: "json",
    //         success: function(response){
    //             $('.viewdata').html(response.data);
    //         },
    //         error: function(xhr, ajaxOptions, thrownError){
    //             alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    //         }
    //     });
    // }
    $(document).ready(function(){
        $('.tomboltambahbanyak').click(function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ url('dashboard/stokGudang/formtambahbanyak') }}", // Menggunakan sintaks Blade untuk menghasilkan URL Laravel
                dataType: "json",
                beforeSend: function(){
                    $('.viewdata').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                success: function(response){
                    $('.viewdata').html(response.data).show();
                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });
    });
    </script>
    
@endsection