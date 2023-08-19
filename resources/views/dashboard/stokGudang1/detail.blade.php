@php use Carbon\Carbon; @endphp
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
                    <h1>Daftar Stok Gudang</h1>
                    <a href="/dashboard/stokgudang" class="btn btn-info mb-3">Masukan Barang ke Gudang</a><br>
                    @foreach ($gudangs as $gudang)
                        <a href="{{ route('gudang.barang', ['gudang_id' => $gudang->id]) }}" class="btn btn-success mb-3">Daftar Barang di {{ $gudang->nama }}</a>
                    @endforeach

                    <h6>Histori</h6>
                    @foreach ($stokgudang as $item)
                        @if ($item->detail->isNotEmpty())
                            <ul class="list-group">
                                <a href="{{ url('/dashboard/detail/stokgudang/'.$item->id) }}" style="text-decoration: none;">
                                    <li class="list-group-item d-flex justify-content-between align-items-center hover">
                                        <table class="table">
                                            <tr>
                                                <td>{{ $item->gudang->nama }}</td>                            
                                                <td>{{ Carbon::parse($item->gudang->created_at)->translatedFormat('l, d F Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </li>
                                </a>
                            </ul>
                        @endif
                    @endforeach

                    {{-- @foreach ($stokgudang as $item)
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $item->gudang->nama }}
                            <span class="badge bg-primary rounded-pill"><a href="{{ url('/dashboard/detail/stokgudang/'.$item->id) }}" class="text-white text-decoration-none">detail</a></span>
                            </li>
                        </ul>
                    @endforeach --}}
                </div>
            </div>
        </div>
    </main>
</div>

@endsection