@section('sidenav')
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Master</div>
                <a class="nav-link" href="/jenisBarang">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Jenis Barang
                </a>
                <a class="nav-link" href="/pemasok">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Pemasok
                </a>
                <a class="nav-link" href="/toko">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Toko
                </a>
                <a class="nav-link" href="/gudang">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Gudang
                </a>
                <a class="nav-link" href="/barang">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Barang
                </a>
                <div class="sb-sidenav-menu-heading">Transaksi</div>
                <a class="nav-link" href="charts.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Pengeluaran
                </a>
                <a class="nav-link" href="tables.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Transaksi Penjualan
                </a>
                <div class="sb-sidenav-menu-heading">Report</div>
                <a class="nav-link" href="charts.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Laporan Keuangan
                </a>
                <div class="sb-sidenav-menu-heading">Setting</div>
                    <form action="/logout" class="nav-link" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ auth()->check() ? auth()->user()->name : 'Guest' }}
            </div>
    </nav>
</div>

@endsection
