<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailStokGudang;
use App\Models\DetailStokToko;
use App\Models\StokToko;
use App\Models\Toko;
use App\Models\TotalStokToko;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TokoController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $toko = Toko::all();
        
        $urut = (Toko::count() == 0)? 10001 : (int)substr(Toko::all()->last()->kode_toko, - 5) + 1;
        $nomer = 'TK' . $urut;
        
        return view('dashboard.toko.index', compact('toko', 'nomer') ,[
            'title' => 'Toko',
            'desc'=> 'Data toko',
            'tableTitle' => 'Data Toko'
        ]);
    }

    public function showTokoBarang($toko_id)
    {
        $toko = Toko::findOrFail($toko_id);
        // $totalStokToko = TotalStokToko::with(['detailStokGudang.barang'])->where('toko_id', $toko_id)->get();
        // $totalStokToko = TotalStokToko::with(['detailStokGudang'])->where('toko_id', $toko_id)->get();
        $totalStokToko = TotalStokToko::with(['detailStokGudang.barang.jenisBarang'])
            ->where('toko_id', $toko_id)
            ->get();

        return view('dashboard.stoktoko.show_barang', compact('toko', 'totalStokToko'), [
            'title' => 'Detail Toko',
            'tableTitle' => 'Data ' . $toko->nama
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $toko = new Toko();
        // $toko->kode_toko = $request->input('kode_toko');
        // $toko->nama = $request->input('nama');
        // $toko->alamat = $request->input('alamat');
        // $toko->save();

        $validatedData = $request->validate([
            'kode_toko' => 'required',
            'nama' => 'required',
            'alamat' => 'required'
        ], [
            'kode_toko.required' => 'Kode toko harus diisi.',
            'nama.required' => 'Nama toko harus diisi.',
            'alamat.required' => 'Alamat toko harus diisi.'
        ]);
        
        Toko::create($validatedData);

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
        $toko = Toko::find($id);
        return response()->json([
            'status'=>200,
            'toko'=>$toko
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'kode_toko' => 'required',
            'nama' => 'required',
            'alamat' => 'required'
        ], [
            'kode_toko.required' => 'Kode toko harus diisi.',
            'nama.required' => 'Nama toko harus diisi.',
            'alamat.required' => 'Alamat toko harus diisi.'
        ]);

        Toko::where('id', $request->input('toko_id'))->update($validatedData);

        return redirect()->back()->with('status', 'Updated berhasillll');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $toko = $request->input('deleting_id');
        $toko = Toko::find($toko);
        $toko->delete();
        return redirect()->back()->with('status', 'Delete Berhasil');
    }
}

// $detailStokTokoData = DetailStokToko::whereHas('stokToko', function ($query) use ($toko_id) {
        //     $query->where('toko_id', $toko_id);
        // })
        // ->with('barang')
        // ->get();
        // $detailStokTokoData = TotalStokToko::whereHas('stokToko', function ($query) use ($toko_id) {
        //     $query->where('toko_id', $toko_id);
        // })
        // ->with(['stokToko.detailStokTokos.barang']) // Mengambil relasi barang dari detailStokTokos
        // ->get();

        // $detailStokTokoData = TotalStokToko::whereHas('stokToko', function ($query) use ($toko_id) {
        //     $query->where('toko_id', $toko_id);
        // })
        // ->with(['stokToko.detailStokTokos.barang', 'stokTokos' => function ($query) {
        //     $query->select('stoktoko_id', 'barang_id', 'total_stok'); // Menambahkan total_stok ke dalam query
        // }])
        // ->get();