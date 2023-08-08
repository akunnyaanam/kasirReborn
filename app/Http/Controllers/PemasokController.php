<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;

class PemasokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pemasok = Pemasok::all();

        $urut = (Pemasok::count() == 0)? 10001 : (int)substr(Pemasok::all()->last()->kode_pemasok, - 5) + 1;
        $nomer = 'PMSK' . $urut;

        return view('dashboard.pemasok.index', compact('pemasok', 'nomer') ,[
            'title' => 'Pemasok',
            'desc' => 'Data-data pemasok',
            'tableTitle' => 'Data pemasok'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_pemasok' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required|unique:pemasoks|digits_between:10,13'
        ], [
            'kode_pemasok.required' => 'Kode pemasok harus diisi.',
            'nama.required' => 'Nama pemasok harus diisi.',
            'alamat.required' => 'Alamat pemasok harus diisi.',
            'no_telp.required' => 'Nomor telepon pemasok harus diisi.',
            'no_telp.unique' => 'Nomor telepon pemasok sudah ada dalam database.',
            'no_telp.digits_between' => 'Nomor telepon pemasok harus memiliki panjang antara 10 dan 13 digit.'
        ]);

        Pemasok::create($validatedData);

        return redirect()->back()->with('status', 'Data berhasil ditambahkan.');
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
        $validatedData = $request->validate([
            'kode_pemasok' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'no_telp' => [
                'required',
                Rule::unique('pemasoks')->ignore($request->input('pemasok_id')),
                'digits_between:10,13'
            ]
        ], [
            'kode_pemasok.required' => 'Kode pemasok harus diisi.',
            'nama.required' => 'Nama pemasok harus diisi.',
            'alamat.required' => 'Alamat pemasok harus diisi.',
            'no_telp.required' => 'Nomor telepon pemasok harus diisi.',
            'no_telp.unique' => 'Nomor telepon pemasok sudah ada dalam database.',
            'no_telp.digits_between' => 'Nomor telepon pemasok harus memiliki panjang antara 10 dan 13 digit.'
        ]);

        Pemasok::where('id', $request->input('pemasok_id'))->update($validatedData);

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
