<!-- resources/views/tambah_stok.blade.php -->

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
                url: "{{ route('simpan.stok') }}",
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
</script>
