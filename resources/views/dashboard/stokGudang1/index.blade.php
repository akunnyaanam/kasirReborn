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
                    <h1 class="mb-3">Form Input Barang ke Gudang</h1>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form method="POST" action="/dashboard/stokgudang/tambah">
                        @csrf
                        <div class="mb-3">
                            <label for="gudang" class="form-label">Gudang</label>
                            <select name="gudang_id" class="form-control">
                                <option selected>Pilih Gudang</option>
                                @foreach($gudang as $data)
                                    <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="barang">
                                <tr>
                                    <td>
                                        <select name="barang_id[]" id="barangSelect" class="form-control" required>
                                            <option selected>Pilih Barang</option>
                                            @foreach($barang as $data)
                                                <option value="{{ $data->id }}">
                                                    {{ $data->kode_barang }} || {{ $data->nama }} || {{ $data->RRpemasok->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control" id="stok" name="stok[]" required placeholder="Masukan Stok"></td>
                                    <td><a href="#" class="btn btn-info" id="addbarang">Tambah Barang</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="/dashboard/stokgudang" class="btn btn-dark">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#addbarang').on('click', function (e) {
                e.preventDefault();
                addbarang();
            });

            function addbarang() {
                let barang = '<tr><td><select name="barang_id[]" class="form-control"><option selected>Pilih Barang</option>@foreach($barang as $data)<option value="{{ $data->id }}">{{ $data->kode_barang }} || {{ $data->nama }} || {{ $data->RRpemasok->nama }}</option>@endforeach</select></td><td><input type="text" class="form-control" id="stok" name="stok[]" required placeholder="Masukan Stok"></td><td><a href="#" class="btn btn-danger remove-barang">Remove</a></td></tr>';

                $('.barang').append(barang);
            }

            $(document).on('click', '.remove-barang', function (e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const stokInputs = document.querySelectorAll("input[name='stok[]']");

            stokInputs.forEach(input => {
                input.addEventListener("input", function () {
                    const value = parseFloat(input.value);

                    if (isNaN(value) || value <= 0) {
                        input.value = "";
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const barangSelect = document.getElementById("barangSelect");
            const kodeBarang = document.getElementById("kodeBarang");
            const namaBarang = document.getElementById("namaBarang");
            const namaPemasok = document.getElementById("namaPemasok");

            barangSelect.addEventListener("change", function () {
                const selectedOption = barangSelect.options[barangSelect.selectedIndex];
                kodeBarang.textContent = selectedOption.getAttribute("data-kode");
                namaBarang.textContent = selectedOption.getAttribute("data-nama");
                namaPemasok.textContent = selectedOption.getAttribute("data-pemasok");
            });
        });
    </script>
@endsection

{{-- <!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <!-- Navbar content -->
    </nav>

    <div class="container mt-4">
        <h1>Form Input Stok Gudang</h1>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="/stokgudang">
            @csrf
            <div class="mb-3">
                <label for="gudang" class="form-label">Gudang</label>
                <select name="gudang_id" class="form-control">
                    <option selected>Pilih Gudang</option>
                    @foreach($gudang as $data)
                        <option value="{{ $data->id }}">{{ $data->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="barang" class="form-label">Barang</label>
                <select name="barang_id[]" class="form-control">
                    <option selected>Pilih Barang</option>
                    @foreach($barang as $data)
                        <option value="{{ $data->id }}">{{ $data->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="text" class="form-control" id="stok" name="stok[]" value="{{ old('stok') }}" required>
            </div>
            <a href="#" class="btn btn-info" id="addbarang">Tambah Barang</a>
            <div class="barang"></div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div> --}}

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></sc>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#addbarang').on('click', function (e) {
                e.preventDefault();
                addbarang();
            });

            function addbarang() {
                let barang = '<div class="barang-group"><div class="mb-3"><label for="barang" class="form-label">Barang</label><select name="barang_id[]" class="form-control"><option selected>Pilih Barang</option>@foreach($barang as $data)<option value="{{ $data->id }}">{{ $data->nama }}</option>@endforeach</select></div><div class="mb-3"><label for="stok" class="form-label">Stok</label><input type="text" class="form-control" id="stok" name="stok[]" value="{{ old('stok') }}" required></div><a href="#" class="btn btn-danger remove-barang">Remove</a> </div>';

                $('.barang').append(barang);
            }

            $(document).on('click', '.remove-barang', function (e) {
                e.preventDefault();
                $(this).parent().remove();
            });
        });
    </script> --}}

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html> --}}
