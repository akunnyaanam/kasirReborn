@section('sidenav')
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Master</div>
                <a class="nav-link" href="/dashboard/jenisBarang">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-inbox"></i></div>
                    Jenis Barang
                </a>
                <a class="nav-link" href="/dashboard/pemasok">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-user"></i></div>
                    Pemasok
                </a>
                <a class="nav-link" href="/dashboard/gudang">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-warehouse"></i></div>
                    Gudang
                </a>
                <a class="nav-link" href="/dashboard/toko">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-shop"></i></i></div>
                    Toko
                </a>
                <a class="nav-link" href="/dashboard/barang">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-chair"></i></div>
                    Barang
                </a>                    
                <a class="nav-link" href="/dashboard/detail/stokgudang">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-chair"></i></div>
                    Barang Ke Gudang
                </a>                    
                <a class="nav-link" href="/dashboard/mutasi">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-chair"></i></div>
                    Mutasi
                </a>                    
                <a class="nav-link" href="/dashboard/stoktoko">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-chair"></i></div>
                    Stok Barang ke Toko
                </a>       
                <div class="sb-sidenav-menu-heading">Transaksi</div>
                <a class="nav-link" href="/dashboard/pengeluaran">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Pengeluaran
                </a>
                <a class="nav-link" href="/dashboard/pengeluaran/detail">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Detail Pengeluaran
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
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ auth()->check() ? auth()->user()->name : 'Guest' }}
            </div>
    </nav>
</div>

@endsection
