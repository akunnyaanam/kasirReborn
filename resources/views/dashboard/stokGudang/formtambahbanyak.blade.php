{{-- {{-- <form action="{{ url('/dashboard/stokGudang/simpandatabanyak') }}" method="POST" class="formsimpanbanyak">
    @csrf
    <a href="/dashboard/stokGudang" class="btn btn-warning">
        Kembali
    </a>
    <button type="submit" class="btn btn-primary btnsimpanbanyak">
        Simpan Data
    </button>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Barang</th>
                <th>Gudang</th>
                <th>Stok</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody class="formtambah">
            <tr>
                <td>
                    <select name="barang[]" class="form-control">
                        <option selected>Pilih Barang</option>
                        @foreach($barang as $data)
                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="gudang[]" class="form-control"></td>
                <td><input type="text" name="stok[]" class="form-control"></td>
                <td>
                    <button type="button" class="btn btn-primary btnaddform">
                        <i class="fa fa-plus"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</form> --}}
{{-- <form id="stok-form" method="post">
    <table id="data-table">
        <thead>
            <tr>
                <th>Barang</th>
                <th>Gudang</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr class="data-row">
                <td>
                    <select name="id_barang[]" class="form-control">
                        <option selected>Pilih Barang</option>
                        @foreach($barang as $data)
                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="id_gudang[]" class="form-control">
                        <option selected>Pilih Gudang</option>
                        @foreach($gudang as $data)
                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="stok[]" /></td>
                <td><button class="btn-hapus">Hapus</button></td>
            </tr>
        </tbody>
    </table>
    <button id="btn-tambah">Tambah Baris</button>
    <button type="submit" id="btn-simpan">Simpan</button>
</form> --}}

<form id="stok-form">
    <div class="form-group">
        <label for="id_gudang">Gudang</label>
        <select name="id_gudang[]" class="form-control">
            @foreach ($gudang as $g)
                <option value="{{ $g->id }}">{{ $g->nama }}</option>
            @endforeach
        </select>
    </div>
    <table id="data-table">
        <thead>
            <tr>
                <th>Barang</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr class="data-row">
                <td>
                    <select name="id_barang[]" class="form-control">
                        @foreach ($barang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="stok[]" class="form-control" /></td>
                <td><button class="btn-hapus">Hapus</button></td>
            </tr>
        </tbody>
    </table>
    <button id="btn-tambah">Tambah Baris</button>
    <button type="submit" id="btn-simpan">Simpan</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        // Tambah baris baru saat tombol "Tambah Baris" diklik
        $('#btn-tambah').click(function(){
            var newRow = `
                <tr class="data-row">
                    <td>
                        <select name="id_barang[]" class="form-control">
                            @foreach ($barang as $b)
                                <option value="{{ $b->id }}">{{ $b->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="stok[]" class="form-control" /></td>
                    <td><button class="btn-hapus">Hapus</button></td>
                </tr>`;
            $('#data-table tbody').append(newRow);
        });

        // Menghapus baris saat tombol "Hapus" di dalam baris diklik
        $(document).on('click', '.btn-hapus', function(){
            $(this).closest('.data-row').remove();
        });

        // Simpan data menggunakan AJAX
        $('#stok-form').submit(function(e){
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "{{ url('/simpan-stok') }}",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function(){
                    $('#btn-simpan').attr('disabled', 'disabled');
                    $('#btn-simpan').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete: function(){
                    $('#btn-simpan').removeAttr('disabled');
                    $('#btn-simpan').html('Simpan');
                },
                success: function(response){
                    if(response.sukses){
                        alert('Data berhasil disimpan');
                        // Lakukan tindakan lain setelah data disimpan
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });
    });
//     $(document).ready(function(){
//     // Tambah baris baru saat tombol "Tambah Baris" diklik
//     $('#btn-tambah').click(function(){
//         var newRow = `
//         <tr class="data-row">
//             <td>
//                 <select name="barang[]" class="form-control">
//                     <option selected>Pilih Barang</option>
//                     @foreach($barang as $data)
//                         <option value="{{ $data->id }}">{{ $data->nama }}</option>
//                     @endforeach
//                 </select>
//             </td>
//             <td>
//                 <select name="gudang[]" class="form-control">
//                     <option selected>Pilih Gudang</option>
//                     @foreach($gudang as $data)
//                         <option value="{{ $data->id }}">{{ $data->nama }}</option>
//                     @endforeach
//                 </select>
//             </td>
//             <td><input type="text" name="stok[]" /></td>
//             <td><button class="btn-hapus">Hapus</button></td>
//         </tr>`;
//         $('#data-table tbody').append(newRow);
//     });

//     // Menghapus baris saat tombol "Hapus" di dalam baris diklik
//     $(document).on('click', '.btn-hapus', function(){
//         $(this).closest('.data-row').remove();
//     });

//     // ...

//     // Anda juga dapat menambahkan kode AJAX di sini untuk menyimpan data tabel
//     $('#stok-form').submit(function(e){
//         e.preventDefault();
//         $.ajax({
//             type: "post",
//             url: "{{ url('/dashboard/stokGudang/simpandatabanyak') }}",
//             data: $(this).serialize(),
//             dataType: "json",
//             beforeSend: function(){
//                 $('#btn-simpan').attr('disabled', 'disabled');
//                 $('#btn-simpan').html('<i class="fa fa-spin fa-spinner"></i>');
//             },
//             complete: function(){
//                 $('#btn-simpan').removeAttr('disabled');
//                 $('#btn-simpan').html('Simpan');
//             },
//             success: function(response){
//                 if(response.sukses){
//                     alert('Data berhasil disimpan');
//                     // Lakukan tindakan lain setelah data disimpan
//                 }
//             },
//             error: function(xhr, ajaxOptions, thrownError){
//                 alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
//             }
//         });
//     });
// });


    // $(document).ready(function(e) {
    //     $('.btnaddform').click(function(e){
    //         e.preventDefault();
    //         $('.formtambah').append(`
    //         <tr>
    //             <td><input type="text" name="barang[]" class="form-control"></td>
    //             <td><input type="text" name="gudang[]" class="form-control"></td>
    //             <td><input type="text" name="stok[]" class="form-control"></td>
    //             <td>
    //                 <button type="button" class="btn btn-danger btnhapusform">
    //                     <i class="fa fa-trash"></i>
    //                 </button>
    //             </td>
    //         </tr>
    //         `);
    //     });
    // });

    // $('.btnsimpanbanyak').submit(function(e){
    //     e.preventDefault();
    //     $.ajax({
    //         type: "post",
    //         url: "{{ url('dashboard/stokGudang/simpandatabanyak') }}", // Ganti sesuai nama route Anda
    //         data: $(this).serialize(),
    //         dataType: "json",
    //         beforeSend: function(){
    //             $('.btnsimpanbanyak').attr('disabled', 'disabled'); // Perbaikan typo di sini
    //             $('.btnsimpanbanyak').html('<i class="fa fa-spin fa-spinner"></i>');
    //         },
    //         complete: function(){
    //             $('.btnsimpanbanyak').removeAttr('disabled'); // Perbaikan typo di sini
    //             $('.btnsimpanbanyak').html('Simpan');
    //         },
    //         success: function(response){
    //             if(response.sukses){
    //                 window.location.href = "{{ url('dashboard/stokGudang') }}"; // Perbaikan di sini
    //             }
    //         },
    //         error: function(xhr, ajaxOptions, thrownError){
    //             alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    //         }
    //     });
    // });

    // $(document).on('click', '.btnhapusform', function(e){
    //     e.preventDefault();

    //     $(this).parents('tr').remove();
    // });
</script> --}}