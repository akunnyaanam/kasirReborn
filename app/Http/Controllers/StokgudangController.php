<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;


class StokgudangController extends Controller
{
    public function index(): View
    {
        $stokGudang = StokGudang::oldest()->paginate();
        $barang = Barang::all();
        $gudang = Gudang::all();

        return view('stokGudang.index', compact('barang', 'gudang', 'stokGudang'), [
            'title' => "Data Barang",
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input yang diterima dari pengguna dengan pesan kustom
        $validator = Validator::make($request->all(), [
            'stok' => 'required|integer|min:0', // Memastikan quantity tidak bernilai minus
        ]);

        // Jika validasi gagal, kembalikan pengguna ke halaman formulir dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->with('gagal', 'Gagal Menambahkan Karena Input Tidak Cocok');
        }
        $stokGudang = new StokGudang();
        $stokGudang->id_barang = $request->input('kode_barang');
        $stokGudang->id_gudang = $request->input('kode_gudang');
        $stokGudang->stok = $request->input('stok');
        $stokGudang->save();
        return redirect()->back()->with('status', 'Status berhasillll');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stokGudang = StokGudang::find($id);
        return response()->json([
            'status'=>200,
            'stokGudang'=>$stokGudang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $stokGudang_id = $request->input('stokGudang_id');
        $stokGudang = StokGudang::find($stokGudang_id);
        $stokGudang->id_barang = $request->input('kode_barang');
        $stokGudang->id_gudang = $request->input('kode_gudang');
        $stokGudang->stok = $request->input('stok');
        $stokGudang->update();

        return redirect()->back()->with('status', 'Updated berhasillll');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $stokGudang = $request->input('deleting_id');
        $stokGudang = StokGudang::find($stokGudang);
        $stokGudang->delete();
        return redirect()->back()->with('status', 'Delete Berhasil');
    }
}
