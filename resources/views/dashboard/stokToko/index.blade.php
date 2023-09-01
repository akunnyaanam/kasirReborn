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
                    <h1 class="mb-3">Form Input Barang ke Toko</h1>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form method="POST" action="/dashboard/stoktoko/tambah">
                        @csrf
                        <div class="mb-3">
                            <h6 id="current-time"></h6>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Kode Surat Jalan</label>
                            <input type="text" name="kode_suratjalan" required value="{{ $nomer }}" readonly class="form-control bg-light">
                        </div>
                        <div class="mb-3">
                            <label for="toko" class="form-label">Toko</label>
                            <select name="toko_id" class="form-control" required>
                                <option selected>Pilih Toko</option>
                                @foreach($toko as $data)
                                    <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Gudang</th>
                                    <th>Stok</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="barang">
                                <tr>
                                    <td>
                                        <select name="barang_id[]" class="form-control barang-select" required>
                                            <option value="" selected>Pilih</option>
                                            @foreach($totalStokGudang as $data)
                                                <option value="{{ $data->id . '-' . $data->barang_id }}" data-gudang="{{ $data->gudang->nama }}" data-stok="{{ $data->total_stok }}" data-gudang-id="{{ $data->gudang_id }}">
                                                    {{ $data->barang->nama }} || Stok: {{ $data->total_stok }} || Gudang: {{ $data->gudang->nama }}
                                                </option>
                                            @endforeach
                                        </select>                                                                             
                                        {{-- <select name="barang_id[]" class="form-control barang-select" required>
                                            <option selected>Pilih</option>
                                            @foreach($totalStokGudang as $data)
                                                <option value="{{ $data->barang_id }}" data-gudang="{{ $data->gudang->nama }}" data-stok="{{ $data->total_stok }}" data-gudang-id="{{ $data->gudang_id }}">
                                                    {{ $data->barang->nama }} || {{ $data->total_stok }} || {{ $data->gudang->nama }}
                                                </option>
                                            @endforeach
                                        </select> --}}
                                    </td>                                    
                                    {{-- <td>
                                        <select name="barang_id[]" class="form-control barang-select" required>
                                            <option selected>Pilih</option>
                                            @foreach($detailstokgudang as $data)
                                                <option value="{{ $data->id }}" data-gudang="{{ $data->stokgudang->gudang->nama }}" data-stok="{{ $data->stok }}" data-gudang-id="{{ $data->stokgudang->gudang_id }}">{{ $data->barang->nama }} || {{ $data->stok }} || {{ $data->stokgudang->gudang->nama }}</option>
                                            @endforeach
                                        </select>
                                    </td> --}}
                                    <td>
                                        <input type="hidden" class="form-control gudang-awal-id" name="gudang_awal_id[]">
                                        <input type="text" class="form-control gudang-awal bg-light" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control stok-awal bg-light" name="stok_awal[]" readonly>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control stok-input" id="jumlah" name="stok[]" required>
                                        <small class="invalid-feedback"></small>
                                    </td>
                                    <td><a href="#" class="btn btn-info" id="addbarang">Tambah Barang</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary" id="mitSubmit">Submit</button>
                        <a href="/dashboard/stoktoko" class="btn btn-dark">Kembali</a>
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

    <script>
        $(document).ready(function () {
            $('#addbarang').on('click', function (e) {
                e.preventDefault();
                addbarang();
            });
    
            function addbarang() {
                var selectedBarangStok = parseInt($('.barang-select option:selected').data('stok'));
    
                if (selectedBarangStok <= 0) {
                    alert('Stok barang habis atau kurang dari 1. Tidak dapat menambahkan barang lagi.');
                    return;
                }
    
                var newRow = $('.barang tr:last').clone(); // Clone baris terakhir
                newRow.find('input').val(''); // Reset input
                newRow.find('.add-barang').remove(); // Hapus tombol Tambah Barang di baris baru
                newRow.find('.barang-select').removeAttr('required'); // Hapus required dari select
    
                // Menghapus opsi barang yang sudah dipilih pada baris sebelumnya
                $('.barang-select').each(function () {
                    var selectedValue = $(this).val();
                    newRow.find(`option[value="${selectedValue}"]`).remove();
                });
    
                // Handler ketika memilih barang
                newRow.find('.barang-select').on('change', function () {
                    var selectedOption = $(this).find(':selected');
                    var gudang = selectedOption.data('gudang');
                    var stok = selectedOption.data('stok');
                    newRow.find('.gudang-awal').val(gudang);
                    newRow.find('.stok-awal').val(stok);
                });
    
                newRow.find('.stok-input').on('input', function () {
                    const stokAwal = parseInt(newRow.find('.stok-awal').val());
                    const stokInput = parseInt($(this).val());
                    const errorElement = $(this).siblings('.invalid-feedback');
    
                    if (stokInput > stokAwal) {
                        $(this).addClass('is-invalid');
                        errorElement.text('Stok melebihi batas, Segera Diganti !!!');
                    } else {
                        $(this).removeClass('is-invalid');
                        errorElement.text('');
                    }
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
    
    
    
    {{-- <script type="text/javascript">
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

                // $('.barang').append(barang);
            }

            $(document).on('click', '.remove-barang', function (e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });

            
        });
    </script> --}}
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

    <script>
        $(document).ready(function () {
            $('#jumlah').on('input', function () {
                var value = $(this).val();
                if (value < 1) {
                    $(this).val('');
                }
            });
        });
    </script>

    <script>
       document.addEventListener('DOMContentLoaded', function () {
            const stokAwalInputs = document.querySelectorAll('.stok-awal');
            const stokInputs = document.querySelectorAll('.stok-input');
            
            stokInputs.forEach((stokInput, index) => {
                stokInput.addEventListener('input', function() {
                    const stokAwal = parseInt(stokAwalInputs[index].value);
                    const stok = parseInt(stokInput.value);
                    const errorElement = stokInput.nextElementSibling;
                    
                    if (stok > stokAwal) {
                        stokInput.classList.add('is-invalid');
                        errorElement.textContent = 'Stok melebihi batas, Segera Diganti !!!';
                    } else {
                        stokInput.classList.remove('is-invalid');
                        errorElement.textContent = '';
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const barangSelects = document.querySelectorAll('.barang-select');
            
            barangSelects.forEach((barangSelect, index) => {
                barangSelect.addEventListener('change', function() {
                    const selectedValue = this.value;
                    
                    // Loop melalui semua elemen select lainnya
                    barangSelects.forEach((otherSelect, otherIndex) => {
                        if (otherIndex !== index) {
                            const optionToRemove = otherSelect.querySelector(`option[value="${selectedValue}"]`);
                            if (optionToRemove) {
                                otherSelect.removeChild(optionToRemove);
                            }
                        }
                    });
                });
            });
        });
    </script>

    <script>
        function updateClock() {
            const currentTime = new Date();
            const hours = currentTime.getHours();
            const minutes = currentTime.getMinutes();
            const seconds = currentTime.getSeconds();

            const dayIndex = currentTime.getDay();
            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const currentDay = days[dayIndex];

            const day = currentTime.getDate();
            const monthIndex = currentTime.getMonth();
            const months = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            const currentMonth = months[monthIndex];
            const year = currentTime.getFullYear();

            const timeString = `${hours}:${minutes}:${seconds}`;
            const dayString = `${currentDay}`;
            const dateString = `${day} ${currentMonth} ${year}`;

            const combinedString = `${timeString}, ${dayString} ${dateString}`;

            document.getElementById("current-time").textContent = combinedString;
        }

        // Update clock every second
        setInterval(updateClock, 1000);

        // Initial update
        updateClock();
    </script>
@endsection
