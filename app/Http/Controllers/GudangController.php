<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gudang;
use Illuminate\View\View;
use App\Models\StokGudang;
use App\Models\TotalStokGudang;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class GudangController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gudang = Gudang::all();

        $urut = (Gudang::count() == 0)? 10001 : (int)substr(Gudang::all()->last()->kode_gudang, - 5) + 1 ;
        $nomer = 'GDNG' . $urut;

        return view('dashboard.gudang.index', compact('gudang', 'nomer') ,[
            'title' => 'Gudang',
            'desc'=> 'Data gudang',
            'tableTitle' => 'Data Gudang'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function showBarang($gudang_id)
    {
        $gudang = Gudang::findOrFail($gudang_id);
        $totalstokgudang = TotalStokGudang::where('gudang_id', $gudang_id)->get();

        return view('dashboard.stokGudang1.show_barang', compact('gudang', 'totalstokgudang'), [
            'title' => 'Detail Gudang',
            'tableTitle' => 'Data ' . $gudang->nama
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_gudang' => 'required',
            'nama' => 'required',
            'alamat' => 'required'
        ], [
            'kode_gudang.required' => 'Kode gudang harus diisi.',
            'nama.required' => 'Nama gudang harus diisi.',
            'alamat.required' => 'Alamat gudang harus diisi.'
        ]);

        Gudang::create($validatedData);

        return redirect()->back()->with('status', 'Status berhasillll');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gudang = Gudang::find($id);
        return response()->json([
            'status'=>200,
            'gudang'=>$gudang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'kode_gudang' => 'required',
            'nama' => 'required',
            'alamat' => 'required'
        ], [
            'kode_gudang.required' => 'Kode gudang harus diisi.',
            'nama.required' => 'Nama gudang harus diisi.',
            'alamat.required' => 'Alamat gudang harus diisi.'
        ]);

        Gudang::where('id', $request->input('gudang_id'))->update($validatedData);

        return redirect()->back()->with('status', 'Updated berhasillll');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $gudang = $request->input('deleting_id');
        $gudang = Gudang::find($gudang);
        $gudang->delete();
        return redirect()->back()->with('status', 'Delete Berhasil');
    }

    public function gudangBarang(Request $request, $id)
    {
        $gudang = Gudang::all();
        $stok = StokGudang::all();
        $barang = Barang::findOrFail($id);
        $barangsInGudang = $barang->RRstokgudang; // Mengambil stok barang di dalam gudang

        // $urut = (Gudang::count() == 0)? 10001 : (int)substr(Gudang::all()->last()->kode_gudang, - 5) + 1 ;
        // $nomer = 'GDNG' . $urut;

        return view('dashboard.gudang.barang', compact('barangsInGudang','barang', 'stok') ,[
            'title' => 'Barang Gudang ' . $id,
            'desc'=> 'Data barang gudang',
            'tableTitle' => 'Data barang gudang'
        ]);

    }
}
