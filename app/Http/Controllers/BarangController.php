<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Pemasok;
use Illuminate\View\View;
use App\Models\StokGudang;
use App\Models\JenisBarang;
use App\Models\StokToko;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class BarangController extends Controller
{
    public function index(): View
    {
        $barang = Barang::oldest()->paginate();
        $jenisBarang = JenisBarang::all();
        $pemasok = Pemasok::all();
        $gudang = Gudang::all();
        $stokgudang = StokGudang::all();
        $toko = Toko::all();
        $stoktoko = StokToko::all();

        $cek = Barang::count();
        if($cek == 0){
            $urut = 10001;
            $nomer = 'BRG' . $urut;
        }else{
            $ambil = Barang::all()->last();
            $urut = (int)substr($ambil->kode_barang, - 5) + 1;
            $nomer = 'BRG' . $urut;
        }

        return view('dashboard.barang.index', compact('barang', 'jenisBarang', 'pemasok', 'nomer', 'gudang', 'stokgudang', 'toko', 'stoktoko'), [
            'title' => "Barang",
            'desc' => 'Data barang',
            'tableTitle' => 'Data Barang'
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
        $barang->id_jenis_barang = $request->input('id_jenis_barang');
        $barang->id_gudang = $request->input('id_gudang');
        $barang->id_pemasok = $request->input('id_pemasok');
        $barang->nama = $request->input('nama');
        $barang->harga_beli = $request->input('harga_beli');
        $barang->harga_jual = $request->input('harga_jual');
        
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
        $barang->id_jenis_barang = $request->input('id_jenis_barang');
        $barang->id_pemasok = $request->input('id_pemasok');
        $barang->id_gudang = $request->input('id_gudang');
        $barang->nama = $request->input('nama');
        $barang->harga_beli = $request->input('harga_beli');
        $barang->harga_jual = $request->input('harga_jual');
        
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
