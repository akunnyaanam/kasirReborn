@section('nav')
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="#">POS Toko Mebel</a>
\    <!-- Navbar-->
    <ul class="navbar-nav d-none d-md-inline-block ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="nav-item">
            <form action="{{ route('logout') }}" class="nav-link" method="POST">
                @csrf
                <button type="submit" class="nav-link">Logout</button>
            </form>
        </div>
    </ul>
</nav>
@endsection