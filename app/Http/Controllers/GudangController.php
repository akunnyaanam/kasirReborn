<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GudangController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gudang = Gudang::all();
        $cek = Gudang::count();
        if($cek == 0){
            $urut = 10001;
            $nomer = 'GDNG' . $urut;
        }else{
            $ambil = Gudang::all()->last();
            $urut = (int)substr($ambil->kode_gudang, - 5) + 1;
            $nomer = 'GDNG' . $urut;
        }
        return view('gudang.index', compact('gudang', 'nomer') ,[
            'title' => 'Data Gudang'
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
        $gudang = new Gudang();
        $gudang->kode_gudang = $request->input('kode_gudang');
        $gudang->nama = $request->input('nama');
        $gudang->alamat = $request->input('alamat');
        $gudang->save();
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
        $gudang_id = $request->input('gudang_id');
        $gudang = Gudang::find($gudang_id);
        $gudang->kode_gudang = $request->input('kode_gudang');
        $gudang->nama = $request->input('nama');
        $gudang->alamat = $request->input('alamat');
        $gudang->update();

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
}
