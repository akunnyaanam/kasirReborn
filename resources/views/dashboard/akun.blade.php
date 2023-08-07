@extends('dashboard.layouts.main')

@extends('dashboard.layouts.nav')

@section('container')

@extends('dashboard.layouts.sidenav')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">{{ $title }}</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">List semua akun</li>
            </ol>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Akun
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>
                        @forelse($users as $user)
                        <tfoot>
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->UserRule->role }}</td>
                                <td>
                                    <a href={{ route('dashboard.akun.edit', $user->id) }}></a>
                                    <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                                <td>
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('dashboard.akun.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </td>
                                    </form>                
                                </td>
                            </tr>
                        </tfoot>
                        @empty
                            <td>No users found.</td>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </main>
    @extends('dashboard.layouts.footer')

@endsection
