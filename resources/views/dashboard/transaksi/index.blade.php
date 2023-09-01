@extends('dashboard.layouts.main') @extends('dashboard.layouts.nav')
@section('container')
{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="my-3 text-center">Form Transaksi Toko Jati Atos</h1>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('transaksi.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <h6>Detail Barang</h6>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Jual</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="barang">
                                <tr>
                                    <td>
                                        <select name="barang_id[]" class="form-control barangSelect" required>
                                            <option selected>Pilih Barang</option>
                                            @foreach($daftarBarang as $barang)
                                                <option value="{{ $barang->barang->id }}"
                                                    data-nama="{{ $barang->barang->nama }}"
                                                    data-harga="{{ $barang->barang->harga_jual }}">
                                                    {{ $barang->barang->kode_barang }} | {{ $barang->barang->nama }} | Rp {{ number_format($barang->barang->harga_jual) }} | {{ $barang->total_stok }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control nama-barang" name="nama_barang[]" readonly></td>
                                    <td><input type="number" class="form-control harga-barang" name="harga_jual[]" readonly></td>
                                    <td><input type="number" class="form-control jumlah" name="jumlah[]" required placeholder="Masukan Jumlah"></td>
                                    <td><input type="number" class="form-control total-harga" name="total_harga[]" readonly></td>
                                    <td><a href="#" class="btn btn-info" id="addbarang">Tambah</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <h6>Detail Transaksi</h6>
                        <div class="form-group mb-3">
                            <label class="form-label">Kode Transaksi</label>
                            <input type="text" name="kode_transaksi" required value="{{ $nomer }}" readonly
                            class="form-control bg-light">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Nama Karyawan</label>
                            <input type="text" name="user_id" required value="{{ $namaPengguna }}" class="form-control bg-light" readonly>
                        </div>
                        <hr>
                        <h6>Detail Keuangan</h6>
                        <div class="form-group mb-3">
                            <label class="form-label">Total Keseluruhan</label>
                            <input type="text" id="totalKeseluruhan" class="form-control bg-light" value="Rp 0" readonly name="total_keseluruhan">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Uang Yang Di bayar</label>
                            <input type="text" id="uangDibayar" class="form-control input-currency" name="uang_pembayaran">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Uang Kembalian</label>
                            <input type="text" id="uangKembalian" class="form-control bg-light" value="Rp 0" readonly name="uang_kembalian">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary me-3" id="submitButton">Bayar</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</main>
</div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        // Fungsi untuk mengupdate total keseluruhan
        function updateTotalKeseluruhan() {
            let totalKeseluruhan = 0;

            $('.total-harga').each(function () {
                const totalHarga = parseFloat($(this).val());
                if (!isNaN(totalHarga)) {
                    totalKeseluruhan += totalHarga;
                }
            });

            const formattedTotalKeseluruhan = totalKeseluruhan.toLocaleString('id-ID');
            $('#totalKeseluruhan').val('Rp ' + formattedTotalKeseluruhan);
        }

        function updateUangKembalian() {
            const totalKeseluruhan = parseInt($('#totalKeseluruhan').val().replace(/[Rp.,]/g, '')); // Hapus karakter 'Rp', titik, dan koma
            const uangDibayar = parseInt($('#uangDibayar').val().replace(/[Rp.,]/g, '')); // Hapus karakter 'Rp', titik, dan koma

            const uangKembalian = uangDibayar - totalKeseluruhan;
            const formattedUangKembalian = uangKembalian.toLocaleString('id-ID', { minimumFractionDigits: 2 });

            $('#uangKembalian').val('Rp ' + formattedUangKembalian.replace(/,/g, ','));
        }

        // Hitung dan update uang kembalian saat input uang dibayar berubah
        $('#uangDibayar').on('input', function () {
            const value = $(this).val().replace(/\D/g, ''); // Hapus karakter selain angka
            const formattedValue ="Rp " + parseInt(value).toLocaleString('id-ID');
            $(this).val(formattedValue);

            updateUangKembalian();
        });

        $('#addbarang').on('click', function (e) {
            e.preventDefault();
            addbarang();
        });

        function addbarang() {
            let barang = '<tr><td><select name="barang_id[]" class="form-control barangSelect" required><option selected>Pilih Barang</option>@foreach($daftarBarang as $barang)<option value="{{ $barang->barang->id }}" data-nama="{{ $barang->barang->nama }}" data-harga="{{ $barang->barang->harga_jual }}">{{ $barang->barang->kode_barang }} | {{ $barang->barang->nama }} | Rp {{ number_format($barang->barang->harga_jual) }} | {{ $barang->total_stok }}</option>@endforeach</select></td><td><input type="text" class="form-control nama-barang" name="nama_barang[]" readonly></td><td><input type="number" class="form-control harga-barang" name="harga_jual[]" readonly></td><td><input type="number" class="form-control jumlah" name="jumlah[]" required placeholder="Masukan Jumlah"></td><td><input type="number" class="form-control total-harga" name="total_harga[]" readonly></td><td><a href="#" class="btn btn-danger remove-barang">Remove</a></td></tr>';

            $('.barang').append(barang);
        }

        $(document).on('click', '.remove-barang', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });

        $(document).on('change', '.barangSelect', function () {
            var selectedOption = $(this).find(':selected');
            var nama = selectedOption.data('nama');
            var harga = parseFloat(selectedOption.data('harga'));

            var row = $(this).closest('tr');
            row.find('.nama-barang').val(nama);
            row.find('.harga-barang').val(harga);
        });

        $(document).on('input', '.jumlah', function () {
            var row = $(this).closest('tr');
            var harga = parseFloat(row.find('.harga-barang').val());
            var jumlah = parseFloat($(this).val());
            var total = harga * jumlah;
            row.find('.total-harga').val(total);

            updateTotalKeseluruhan();
        });
        
    });
</script>
@endsection

{{-- <script>
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
        
        const combinedString = `${dayString} ${dateString} | ${timeString}`;
        
        document.getElementById("current-time").textContent = combinedString;
    }
    
    // Update clock every second
    setInterval(updateClock, 1000);
    
    // Initial update
    updateClock();
</script>

<script>
    document.getElementById("barangSelect").addEventListener("change", function() {
        var selectedOption = this.options[this.selectedIndex];
        var stok = parseInt(selectedOption.getAttribute("data-stok"));
        var harga = parseFloat(selectedOption.getAttribute("data-harga"));
        
        // Nonaktifkan opsi yang telah dipilih
        selectedOption.disabled = true;
        
        // Buat baris baru dalam tabel untuk menampilkan detail barang yang dipilih
        var selectedItemsTable = document.getElementById("selected-items-table");
        var newRow = selectedItemsTable.insertRow();

        var kodeCell = newRow.insertCell(0);
        kodeCell.innerHTML = selectedOption.getAttribute("data-kode");
        
        var namaCell = newRow.insertCell(1);
        namaCell.innerHTML = selectedOption.getAttribute("data-nama");
        
        var hargaCell = newRow.insertCell(2);
        hargaCell.innerHTML = formatCurrency(harga);
        
        var jumlahCell = newRow.insertCell(3);
        var jumlahInput = document.createElement("input");
        jumlahInput.type = "number";
        jumlahInput.name = "jumlah[]";
        jumlahInput.value = "1"; // Set nilai default
        jumlahInput.className = "form-control";
        jumlahCell.appendChild(jumlahInput);
        
        var totalHargaCell = newRow.insertCell(4);
        totalHargaCell.className = "total-harga";
        totalHargaCell.innerHTML = formatCurrency(harga);
        
        // Copy semua isi kontainer "selected-items" ke input tersembunyi di dalam form
        var formHiddenInput = document.createElement("input");
        formHiddenInput.type = "hidden";
        formHiddenInput.name = "selected_items[]";
        
        var selectedData = {
            barang_id: selectedOption.value,
            jumlah: 1 // Atur sesuai nilai default
        };
        formHiddenInput.value = JSON.stringify(selectedData);
        
        selectedItemsTable.appendChild(formHiddenInput);

        jumlahInput.addEventListener("input", function() {
            var jumlah = parseInt(jumlahInput.value);
            
            if (jumlah < 1 || isNaN(jumlah)) {
                jumlah = 0
            }
            if (jumlah > stok) {
                jumlah = stok;
            }
            jumlahInput.value = jumlah;
            const totalHarga = harga * jumlah;
            totalHargaCell.innerHTML = formatCurrency(totalHarga);   
            
            // Perbarui nilai pada objek JSON
            selectedData.jumlah = jumlah;
            formHiddenInput.value = JSON.stringify(selectedData);
            
            const totalHarga = harga * jumlah;
            totalHargaCell.innerHTML = formatCurrency(totalHarga);  
            
            updateTotalKeseluruhan();
        });
    });

    function updateTotalKeseluruhan() {
        var totalKeseluruhan = 0;
        var totalHargaCells = document.getElementsByClassName("total-harga");
        for (var i = 0; i < totalHargaCells.length; i++) {
            var totalHargaText = totalHargaCells[i].innerText.replace("Rp", "").replace(/\s+/g, "").replace(/,/g, "");
            totalKeseluruhan += parseFloat(totalHargaText);
        }

        var totalKeseluruhanInput = document.getElementById("totalKeseluruhan");
        totalKeseluruhanInput.value = formatCurrency(totalKeseluruhan);
    }

    function formatCurrency(amount) {
        var formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        var parts = formatter.formatToParts(amount);
        var decimalPart = parts.find(part => part.type === "decimal");
        var fractionalPart = parts.find(part => part.type === "fraction");

        if (fractionalPart && fractionalPart.value.length < 2) {
            fractionalPart.value = fractionalPart.value.padEnd(2, "0");
        }

        return parts.map(part => part.value).join("");
    }


    
</script> --}}
{{-- <form action="{{ route('transaksi.store') }}" method="POST">
    @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Kode Transaksi</label>
                        <input type="text" name="kode_transaksi" required value="{{ $nomer }}" readonly
                        class="form-control bg-light">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Karyawan</label>
                        <input type="text" name="user_id" required value="{{ $namaPengguna }}" class="form-control bg-light" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Waktu</label>
                        <h6 id="current-time" class="form-control bg-light"></h6>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Daftar Barang di Toko Jati Atos</label>
                        <select class="form-control" id="barangSelect" name="barang_id">
                            <option value="">Pilih Barang</option>
                            @foreach ($daftarBarang as $barang)
                            @php
                            $stok = $barang->total_stok;
                            $disabled = $stok == 0 ? 'disabled' : '';
                            @endphp
                            <option value="{{ $barang->barang->id }}"
                                data-kode="{{ $barang->barang->kode_barang }}"
                                data-nama="{{ $barang->barang->nama }}"
                                data-harga="{{ $barang->barang->harga_jual }}"
                                data-stok="{{ $stok }}" {{ $disabled }}>
                                {{ $barang->barang->kode_barang }} | {{ $barang->barang->nama }} | Rp {{ number_format($barang->barang->harga_jual) }} | Rp {{ number_format($barang->total_stok) }}
                            </option>
                            @endforeach
                        </select>
                    </div> 
                    <div id="selected-items" class="form-group mb-3">
                        <label class="form-label">Detail Barang yang Dipilih</label>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody id="selected-items-table">
                                <!-- Data barang yang dipilih akan ditambahkan di sini -->
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Total Keseluruhan</label>
                        <input type="text" id="totalKeseluruhan" class="form-control bg-light" value="Rp 0" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Uang Yang Di bayar</label>
                        <input type="text" id="uangDibayar" class="form-control input-currency">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Uang Kembalian</label>
                        <input type="text" id="uangKembalian" class="form-control bg-light" value="Rp 0" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form> --}}
                
            
            {{-- @section('scripts')
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
                
                        const combinedString = `${dayString} ${dateString} | ${timeString}`;
                
                        document.getElementById("current-time").textContent = combinedString;
                    }
                
                    // Update clock every second
                    setInterval(updateClock, 1000);
                
                    // Initial update
                    updateClock();
                </script>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const barangSelect = document.getElementById('barangSelect');
                        const selectedBarangTable = document.getElementById('selectedBarangTable');
                        const uangDibayarInput = document.getElementById('uangDibayar');
                        const totalPembelianInput = document.getElementById('totalPembelian');
                        const uangPengembalianInput = document.getElementById('uangPengembalian');
                
                        uangDibayarInput.addEventListener('input', function () {
                            const uangDibayarText = uangDibayarInput.value.replace(/\D/g, ''); // Hapus semua karakter non-digit
                            const uangDibayarAmount = parseFloat(uangDibayarText) / 100; // Konversi ke nilai uang
                
                            uangDibayarInput.value = formatCurrency(uangDibayarAmount); // Format kembali sebagai uang
                            const pengembalian = uangDibayarAmount - parseTotalPembelian();
                
                            if (pengembalian >= 0) {
                                uangPengembalianInput.value = formatCurrency(pengembalian);
                            } else {
                                uangPengembalianInput.value = 'Rp 0';
                            }
                        });
                        
                        barangSelect.addEventListener('change', function () {
                            const selectedOption = barangSelect.options[barangSelect.selectedIndex];
                            const kode = selectedOption.getAttribute('data-kode');
                            const nama = selectedOption.getAttribute('data-nama');
                            const stok = parseInt(selectedOption.getAttribute('data-stok'));
                            const harga = parseFloat(selectedOption.getAttribute('data-harga'));
                
                            const newRow = selectedBarangTable.insertRow();
                            newRow.setAttribute('data-stok', stok);
                            newRow.innerHTML = `
                                <td class="align-middle">${kode}</td>
                                <td class="align-middle">${nama}</td>
                                <td class="align-middle">Rp ${formatNumber(harga)}</td>
                                <td>
                                    <input type="number" class="form-control jumlah-input" data-harga="${harga}" min="1" value="1">
                                </td>
                                <td>
                                    <input type="text" class="form-control total-harga" value="Rp ${formatNumber(harga)}" readonly disabled>
                                </td>
                                <td class="align-middle">
                                    <button class="btn btn-danger btn-sm remove-button">Remove</button>
                                </td>
                            `;
                
                            const jumlahInput = newRow.querySelector('.jumlah-input');
                            const totalHargaInput = newRow.querySelector('.total-harga');
                            const removeButton = newRow.querySelector('.remove-button');
                            
                            const totalPembelianInput = document.getElementById('totalPembelian');
                            const item = {
                                kode: kode,
                                jumlah: 1 // Default jumlah adalah 1, Anda dapat mengubahnya sesuai kebutuhan
                            };
                
                            jumlahInput.addEventListener('input', function () {
                                let jumlah = parseInt(jumlahInput.value);
                                if (isNaN(jumlah) || jumlah < 1) {
                                    jumlah = 0;
                                }
                                if (jumlah > stok) {
                                    jumlah = stok;
                                }
                                jumlahInput.value = jumlah;
                                const totalHarga = harga * jumlah;
                                totalHargaInput.value = `Rp ${formatNumber(totalHarga)}`;
                
                                // Hitung dan tampilkan total pembelian
                                calculateTotalPembelian();
                            });
                
                            removeButton.addEventListener('click', function () {
                                selectedBarangTable.deleteRow(newRow.rowIndex);
                                barangSelect.options[selectedOption.index].disabled = false;
                
                                const selectedItems = getSelectedItemsFromTable();
                                const index = selectedItems.findIndex(item => item.kode === kode);
                                if (index !== -1) {
                                    selectedItems.splice(index, 1);
                                    const selectedItemsInput = document.getElementById('selectedItemsJson');
                                    selectedItemsInput.value = JSON.stringify(selectedItems);
                                }
                            });
                
                            function calculateTotalPembelian() {
                                let totalPembelian = 0;
                                const rows = selectedBarangTable.getElementsByTagName('tr');
                                for (let i = 0; i < rows.length; i++) {
                                    const row = rows[i];
                                    const totalHargaCell = row.querySelector('.total-harga');
                                    if (totalHargaCell) {
                                        const totalHargaText = totalHargaCell.value.replace('Rp', '').replace(/\s+/g, '').replace(/,/g, '');
                                        totalPembelian += parseFloat(totalHargaText);
                                    }
                                }
                                totalPembelianInput.value = formatCurrency(totalPembelian);
                            }
                
                            selectedOption.disabled = true;
                            barangSelect.selectedIndex = 0; // Set select to default option after selection
                
                            const submitButton = document.getElementById('submitButton');
                            submitButton.addEventListener('click', function () {
                                const selectedItems = getSelectedItemsFromTable();
                                const selectedItemsInput = document.getElementById('selectedItemsJson');
                                selectedItemsInput.value = JSON.stringify(selectedItems);
                            });
                
                            function getSelectedItemsFromTable() {
                                const selectedItems = [];
                                const rows = selectedBarangTable.getElementsByTagName('tr');
                                for (let i = 0; i < rows.length; i++) {
                                    const row = rows[i];
                                    const kodeCell = row.cells[0];
                                    const jumlahInput = row.querySelector('.jumlah-input');
                                    if (kodeCell && jumlahInput) {
                                        const kode = kodeCell.textContent;
                                        const jumlah = parseInt(jumlahInput.value);
                                        selectedItems.push({ kode: kode, jumlah: jumlah });
                                    }
                                }
                                return selectedItems;
                            }
                        });
                        function parseTotalPembelian() {
                            const totalPembelianText = totalPembelianInput.value.replace(/\D/g, ''); // Hapus semua karakter non-digit
                            return parseFloat(totalPembelianText) / 100; // Konversi ke nilai uang
                        }
                
                        function formatNumber(number) {
                            return number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                        }
                
                        function formatCurrency(amount) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            }).format(amount);
                        }
                    });
                </script>
                @endsection --}}

                {{-- <form method="POST" action="{{ route('transaksi.store') }}">
                    @csrf
                    <div class="row justify-content-center">
                        @if (session('status'))
                            <div class="alert alert-success p-2 text-center">{{ session('status') }}</div>
                        @endif
                            <h2 class="text-center p-3">Transaksi Penjualan</h2>
                            <div class="col-md-7">
                                <h5 class="text-center mb-4">Daftar Barang</h5>
                                <div class="form-group mb-3">
                                    <label for="">Daftar Barang di Toko Jati Atos</label>
                                    <select class="form-control" id="barangSelect" name="barang_id">
                                        <option value="">Pilih Barang</option>
                                        @foreach ($daftarBarang as $barang)
                                            <option value="{{ $barang->barang->id }}"
                                                    data-kode="{{ $barang->barang->kode_barang }}"
                                                    data-nama="{{ $barang->barang->nama }}"
                                                    data-harga="{{ $barang->barang->harga_jual }}"
                                                    data-stok="{{ $barang->total_stok }}">
                                                    {{ $barang->barang->kode_barang }} | {{ $barang->barang->nama }} | {{ $barang->barang->harga_jual }} | {{ $barang->total_stok }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> 
                                <hr>
                                <h5 class="mb-4">List Pembelian</h5>
                                <table id="selectedBarangTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Harga Jual</th>
                                            <th style="width: 12%;">Jumlah</th>
                                            <th>Total Harga</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-center mb-4">Master Transaksi</h5>
                                <div class="form-group mb-3">
                                    <label for="">Kode Transaksi</label>
                                    <input type="text" name="kode_transaksi" required value="{{ $nomer }}" readonly class="form-control bg-light">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Nama Karyawan</label>
                                    <input type="text" name="user_id" required value="{{ $namaPengguna }}" readonly class="form-control bg-light">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Waktu</label>
                                    <h6 id="current-time" class="form-control bg-light"></h6>
                                </div>
                                <hr>
                                <h5 class="mb-2 text-center">Detail Uang</h5>
                                <table class="table">
                                    <tr>
                                        <td class="align-middle" style="font-size: 150%;">Total Pembelian</td>
                                        <td>
                                            <input type="text" id="totalPembelian" name="harga_total" class="form-control" value="Rp 000.00" readonly style="font-size: 150%;" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle" style="font-size: 100%;">Uang Yang di Bayar</td>
                                        <td>
                                            <input type="text" id="uangDibayar" name="uang_pembayaran" class="form-control input-angka" placeholder="Masukan Uang Untuk Pembayaran" style="font-size: 100%;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle" style="font-size: 100%;">Uang Pengembalian</td>
                                        <td>
                                            <input type="text" id="uangPengembalian" name="uang_kembalian" class="form-control input-angka" placeholder="Rp 0" readonly disabled style="font-size: 100%;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <input type="hidden" name="selected_items_json" id="selectedItemsJson" value="">
                                            <button type="submit" class="btn btn-success" id="submitButton" style="width: 100%; padding: 3%; font-size: 200%;">Bayar</button>
                                        </td>
                                    </tr>
                                </table>                                          
                            </div>
                        </div>
                        <input type="hidden" name="selected_items_json" id="selectedItemsJson" value="">
                    </form> --}}