<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\JenisBarang;
use App\Models\Pemasok;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BarangController extends Controller
{
    public function index(): View
    {
        $barang = Barang::oldest()->paginate();
        $jenisBarang = JenisBarang::all();
        $pemasok = Pemasok::all();
        $gudang = Gudang::all();

        $cek = Barang::count();
        if($cek == 0){
            $urut = 10001;
            $nomer = 'BRG' . $urut;
        }else{
            $ambil = Barang::all()->last();
            $urut = (int)substr($ambil->kode_barang, - 5) + 1;
            $nomer = 'BRG' . $urut;
        }

        return view('barang.index', compact('barang', 'jenisBarang', 'pemasok', 'nomer', 'gudang'), [
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
        $barang = new Barang();
        $barang->kode_barang = $request->input('kode_barang');
        $barang->kode_jenis = $request->input('kode_jenis');
        $barang->nama = $request->input('nama');
        $barang->kode_pemasok = $request->input('kode_pemasok');
        $barang->harga = $request->input('harga');
        $barang->harga_jual = $request->input('harga_jual');
        $barang->kode_gudang = $request->input('kode_gudang');
        $barang->stok = $request->input('stok');
        $barang->save();
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
        $barang = Barang::find($id);
        return response()->json([
            'status'=>200,
            'barang'=>$barang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $barang_id = $request->input('barang_id');
        $barang = Barang::find($barang_id);
        $barang->kode_barang = $request->input('kode_barang');
        $barang->kode_jenis = $request->input('kode_jenis');
        $barang->nama = $request->input('nama');
        $barang->kode_pemasok = $request->input('kode_pemasok');
        $barang->harga = $request->input('harga');
        $barang->update();

        return redirect()->back()->with('status', 'Updated berhasillll');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $barang = $request->input('deleting_id');
        $barang = Barang::find($barang);
        $barang->delete();
        return redirect()->back()->with('status', 'Delete Berhasil');
    }
}
