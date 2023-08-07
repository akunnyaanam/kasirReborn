<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasok;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PemasokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pemasok = Pemasok::all();
        $cek = Pemasok::count();
        if($cek == 0){
            $urut = 10001;
            $nomer = 'PMSK' . $urut;
        }else{
            $ambil = Pemasok::all()->last();
            $urut = (int)substr($ambil->kode_pemasok, - 5) + 1;
            $nomer = 'PMSK' . $urut;
        }
        return view('pemasok.index', compact('pemasok', 'nomer') ,[
            'title' => 'Data Pemasok'
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
        $pemasok = new Pemasok();
        $pemasok->kode_pemasok = $request->input('kode_pemasok');
        $pemasok->nama = $request->input('nama');
        $pemasok->alamat = $request->input('alamat');
        $pemasok->no_telp = $request->input('no_telp');
        $pemasok->save();
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
        $pemasok = Pemasok::find($id);
        return response()->json([
            'status'=>200,
            'pemasok'=>$pemasok
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $pemasok_id = $request->input('pemasok_id');
        $pemasok = Pemasok::find($pemasok_id);
        $pemasok->kode_pemasok = $request->input('kode_pemasok');
        $pemasok->nama = $request->input('nama');
        $pemasok->alamat = $request->input('alamat');
        $pemasok->no_telp = $request->input('no_telp');
        $pemasok->update();

        return redirect()->back()->with('status', 'Updated berhasillll');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $pemasok_id = $request->input('deleting_id');
        $pemasok = Pemasok::find($pemasok_id);
        $pemasok->delete();
        return redirect()->back()->with('status', 'Delete Berhasil');
    }
}
