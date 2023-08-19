@extends('dashboard.layouts.main') 
@extends('dashboard.layouts.nav')
@section('container')

{{-- SideNav --}}
@extends('dashboard.layouts.sidenav')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-11 pt-3">
                    <h1 class="my-3">Detail Stok Gudang</h1>
                    <div class="card mt-4">
                        <div class="card-header" style="align-items: center">
                            <i class="fas fa-table me-1"></i>
                            Detail Histori
                            <a href="/dashboard/detail/stokgudang" class="btn btn-dark" style="float: right;">Kembali</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Gudang</th>
                                        <th scope="col">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $stokgudang->gudang->nama }}</td>
                                        <td>
                                            @foreach ($stokgudang->detail as $item)
                                                <p style="display: inline;">Barang {{ $loop->iteration }}</p> =
                                                <p style="font-size: 18px; text-transform: uppercase; display: inline; font-weight: bold;">{{ $item->barang->nama }}</p> || Stok: <p style="font-size: 18px; display: inline; font-weight: bold;">{{ $item->stok }}</p> <br>
                                            @endforeach
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

  {{-- <div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-11 pt-3">
                    <h1 class="my-3">Detail Stok Gudang</h1>
                    <div class="card mt-4">
                        <div class="card-header" style="align-items: center">
                            <i class="fas fa-table me-1"></i>
                            Detail Histori
                            <a href="/dashboard/detail/stokgudang" class="btn btn-dark" style="float: right;">Kembali</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <tr>
                                            <th scope="col">Gudang</th>
                                            <th scope="col">Keterangan</th>
                                        </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $stokgudang->gudang->nama }}</td>
                                        <td>
                                            @foreach ($stokgudang->detail as $item)
                                                <p style="display: inline;">Barang {{ $loop->iteration }}</p> =
                                                <p style="font-size: 18px; text-transform: uppercase; display: inline; font-weight: bold;">{{ $item->barang->nama }}</p> || Stok: <p style="font-size: 18px; display: inline; font-weight: bold;">{{ $item->stok }}</p> <br>
                                            @endforeach
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div> --}}

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
        <div class="container">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/stokgudang">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/detail/stokgudang">Customer</a>
                </li>
            </ul>
        </div>
        </div>
    </nav>

    <div class="container mt-4">
        @foreach ($stokgudang as $item)
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $item->gudang->nama }}
                <span class="badge bg-primary rounded-pill"><a href="{{ url('/detail/stokgudang/'.$item->id) }}" class="text-white text-decoration-none">detail</a></span>
                </li>
            </ul>
        @endforeach
    </div> --}}


{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script> --}}
{{-- <script type="text/javascript">
    $(document).ready(function () {
        $('#addcustomer').on('click', function (e) {
            e.preventDefault();
            addcustomer();
        });

        function addcustomer() {
            let customer = '<div class="customer-group"> <div class="mb-3"><label for="alamat" class="form-label">Alamat</label><input type="text" class="form-control" name="address[]" required></div><div class="mb-3"><label for="phone" class="form-label">Phone</label><input type="text" class="form-control" name="phone[]" required></div><div class="mb-3"><label for="kode_pos" class="form-label">Kode Pos</label><input type="text" class="form-control" name="kode_pos[]" required></div><a href="#" class="btn btn-danger remove-customer">Remove</a> </div>';

            $('.customer').append(customer);
        }

        $(document).on('click', '.remove-customer', function (e) {
            e.preventDefault();
            $(this).parent().remove();
        });
    });
</script> --}}

    {{-- <script type="text/javascript">
        $('#addcustomer').on('click', function(){
            addcustomer();
        });
        function addcustomer(){
            let customer = '<div> <div class="mb-3"><label for="alamat" class="form-label">Alamat</label><input type="text" class="form-control" id="alamat" name="address" value="{{ old('address') }}" required></div><div class="mb-3"><label for="phone" class="form-label">Phone</label><input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required></div><div class="mb-3"><label for="kode_pos" class="form-label">Kode Pos</label><input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" required></div><a href="" class="btn btn-info" id="remove">Remove</a> </div>';

            $('.customer').append(customer);
        };
        $('#remove').live('click', function(){
            $(this).parent().parent().parent().remove();
        });
    </script> --}}
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html> --}}
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
        <div class="container">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/customer">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/detail/customer">Customer</a>
                </li>
            </ul>
        </div>
        </div>
    </nav>

    <div class="container mt-4">
        <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">Gudang</th>
                <th scope="col">Keterangan Barang Dan Stok</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{ $stokgudang->gudang->nama }}</td>
                <td>
                    @foreach ($stokgudang->detail as $item)
                        <strong>Barang {{ $loop->iteration }}</strong><br>
                        {{ $item->barang->nama }} | {{ $item->stok }} <br>
                    @endforeach

                </td>
              </tr>
            </tbody>
          </table>
    </div> --}}


{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script> --}}
{{-- <script type="text/javascript">
    $(document).ready(function () {
        $('#addcustomer').on('click', function (e) {
            e.preventDefault();
            addcustomer();
        });

        function addcustomer() {
            let customer = '<div class="customer-group"> <div class="mb-3"><label for="alamat" class="form-label">Alamat</label><input type="text" class="form-control" name="address[]" required></div><div class="mb-3"><label for="phone" class="form-label">Phone</label><input type="text" class="form-control" name="phone[]" required></div><div class="mb-3"><label for="kode_pos" class="form-label">Kode Pos</label><input type="text" class="form-control" name="kode_pos[]" required></div><a href="#" class="btn btn-danger remove-customer">Remove</a> </div>';

            $('.customer').append(customer);
        }

        $(document).on('click', '.remove-customer', function (e) {
            e.preventDefault();
            $(this).parent().remove();
        });
    });
</script> --}}

    {{-- <script type="text/javascript">
        $('#addcustomer').on('click', function(){
            addcustomer();
        });
        function addcustomer(){
            let customer = '<div> <div class="mb-3"><label for="alamat" class="form-label">Alamat</label><input type="text" class="form-control" id="alamat" name="address" value="{{ old('address') }}" required></div><div class="mb-3"><label for="phone" class="form-label">Phone</label><input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required></div><div class="mb-3"><label for="kode_pos" class="form-label">Kode Pos</label><input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" required></div><a href="" class="btn btn-info" id="remove">Remove</a> </div>';

            $('.customer').append(customer);
        };
        $('#remove').live('click', function(){
            $(this).parent().parent().parent().remove();
        });
    </script> --}}
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html> --}}