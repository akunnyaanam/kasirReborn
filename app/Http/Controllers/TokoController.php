<?php

namespace App\Http\Controllers;

use App\Models\Toko;
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
        $cek = Toko::count();
        if($cek == 0){
            $urut = 10001;
            $nomer = 'TK' . $urut;
        }else{
            $ambil = Toko::all()->last();
            $urut = (int)substr($ambil->kode_toko, - 5) + 1;
            $nomer = 'Tk' . $urut;
        }
        return view('toko.index', compact('toko', 'nomer') ,[
            'title' => 'Data Toko'
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
        $toko = new Toko();
        $toko->kode_toko = $request->input('kode_toko');
        $toko->nama = $request->input('nama');
        $toko->alamat = $request->input('alamat');
        $toko->save();
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
        $toko_id = $request->input('toko_id');
        $toko = Toko::find($toko_id);
        $toko->kode_toko = $request->input('kode_toko');
        $toko->nama = $request->input('nama');
        $toko->alamat = $request->input('alamat');
        $toko->update();

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
