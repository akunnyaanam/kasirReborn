@extends('dashboard.layouts.main') 
@extends('dashboard.layouts.nav')
@section('container')

{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')
  <div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-5 pt-4">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <h1 class="mb-3">Form Mutasi</h1>
                    <form method="POST" action="/dashboard/mutasi/tambah">
                        @csrf
                        <div class="mb-3">
                            <label for="">Kode Mutasi</label>
                            <input type="text" name="kode_mutasi" required value="{{ $nomer }}" readonly class="form-control bg-light">
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Gudang Awal</th>
                                    <th>Stok Awal</th>
                                    <th>Gudang Tujuan</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="barang">
                                <tr>
                                    <td>
                                        <select name="barang_gudang_ids[]" class="form-control barang-select" required>
                                            <option selected>Pilih Barang Yang Akan Di Mutasi</option>
                                            @foreach($totalStokGudang as $data)
                                                <option value="{{ $data->barang_id }}:{{ $data->gudang->nama }}"
                                                        data-gudang="{{ $data->gudang->nama ?? '' }}" 
                                                        data-stok="{{ $data->total_stok ?? '' }}" 
                                                        data-gudang-id="{{ $data->gudang->nama ?? '' }}">
                                                    {{ $data->barang->nama ?? '' }} || {{ $data->total_stok ?? '' }} || {{ $data->gudang->nama ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{-- <select name="barang_id[]" class="form-control barang-select" required>
                                            <option selected>Pilih Barang Yang Akan Di Mutasi</option>
                                            @foreach($totalStokGudang as $data)
                                            <option value="{{ $data->barang_id }}_{{ $data->gudang_id }}" 
                                                        data-gudang="{{ $data->gudang->nama ?? '' }}" 
                                                        data-stok="{{ $data->total_stok ?? '' }}" 
                                                        data-gudang-id="{{ $data->gudang->nama ?? '' }}">
                                                    {{ $data->barang->nama ?? '' }} || {{ $data->total_stok ?? '' }} || {{ $data->gudang->nama ?? '' }}
                                                </option>
                                            @endforeach
                                        </select> --}}
                                    </td>
                                    <td>
                                        {{-- <input type="text" class="form-control gudang-awal bg-light" name="gudang_awal_id[]" readonly> --}}
                                        <input type="hidden" class="form-control gudang-awal-id" name="gudang_awal_id[]">
                                        <input type="text" class="form-control gudang-awal bg-light" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control stok-awal bg-light" name="stok_awal[]" readonly>
                                    </td>
                                    <td>
                                        <select name="gudang_tujuan_id[]" class="form-control gudang-tujuan-select" required>
                                            <option selected>Pilih Gudang Tujuan</option>
                                            @foreach($gudang as $data)
                                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-success gudang-check"></span>
                                        <span class="text-danger gudang-error"></span>
                                    </td>
                                    <td><input type="number" class="form-control" id="jumlah" name="stok[]" required></td>
                                    <td><a href="#" class="btn btn-info" id="addbarang">Tambah Barang</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="/dashboard/mutasi" class="btn btn-dark">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#addbarang').on('click', function (e) {
                e.preventDefault();
                addbarang();
            });

            function addbarang() {
                var newRow = $('.barang tr:last').clone(); // Clone baris terakhir
                newRow.find('input').val(''); // Reset input
                newRow.find('.add-barang').remove(); // Hapus tombol Tambah Barang di baris baru
                newRow.find('.barang-select').removeAttr('required'); // Hapus required dari select

                // Handler ketika memilih barang
                newRow.find('.barang-select').on('change', function () {
                    var selectedOption = $(this).find(':selected');
                    var gudang = selectedOption.data('gudang');
                    var stok = selectedOption.data('stok');
                    newRow.find('.gudang-awal').val(gudang);
                    newRow.find('.stok-awal').val(stok);
                });

                newRow.find('td:last-child').html('<a href="#" class="btn btn-danger remove-barang">Remove</a>'); // Tambah tombol Remove
                newRow.appendTo('.barang');
            }

            $(document).on('click', '.remove-barang', function (e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });

            
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.barang-select').on('change', function () {
                var selectedOption = $(this).find(':selected');
                var gudang = selectedOption.data('gudang');
                var stok = selectedOption.data('stok');
                var gudangId = selectedOption.data('gudang-id');

                var tr = $(this).closest('tr');
                tr.find('.gudang-awal').val(gudang);
                tr.find('.stok-awal').val(stok);
                tr.find('.gudang-awal-id').val(gudangId);
            });
        });
        // $(document).ready(function () {
        //     $('.barang-select').on('change', function () {
        //         var selectedOption = $(this).find(':selected');
        //         var gudang = selectedOption.data('gudang');
        //         var stok = selectedOption.data('stok');
        //         $(this).closest('tr').find('.gudang-awal').val(gudang);
        //         $(this).closest('tr').find('.stok-awal').val(stok);
        //     });
        // });
    </script>
@endsection
