<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisBarangController extends Controller
{
    public function index()
    {
        // $jenisBarang = JenisBarang::all();
        // $cek = JenisBarang::count();

        // if($cek == 0)
        // {
        //     $urut = 10001;
        //     $nomer = 'JNBRG' . $urut;
        // }
        // else
        // {
        //     $ambil = JenisBarang::all()->last();
        //     $urut = (int)substr($ambil->kode_jenis_barang, - 5) + 1;
        //     $nomer = 'JNBRG' . $urut;
        // }

        $jenisBarang = JenisBarang::all();;
        $urut = JenisBarang::count() == 0 ? 10001 : (int)substr(JenisBarang::all()->last()->kode_jenis_barang, -5) + 1;
        $nomer = 'JNBRG' . $urut;
        
        return view('dashboard.jenisBarang.index', compact('jenisBarang', 'nomer') ,[
            'title' => 'Jenis Barang',
            'desc'=> 'Kategorikan barang',
            'tableTitle' => 'Data Jenis Barang'
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_jenis_barang'=>'required|unique:jenis_barangs',
            'kategori_barang'=>'required|unique:jenis_barangs'
        ], [
            'kode_jenis_barang.unique' => 'Kode jenis barang sudah ada.',
            'kategori_barang.unique' => 'Kategori barang sudah ada.'
        ]);

        JenisBarang::create($validatedData);

        return redirect()->back()->with('status', 'Added Successfully');
    }

    public function edit(string $id)
    {
        $jenisBarang = JenisBarang::find($id);
        return response()->json([
            'status'=>200,
            'jenisBarang'=>$jenisBarang
        ]);
    }

    public function update(Request $request)
    {
        $jenis_barang_id = $request->input('jenis_barang_id');

        $jenisBarang = JenisBarang::find($jenis_barang_id);

        // Request tidak ada 
        if (!$jenisBarang) {
            return redirect()->back()->with('error', 'Data not found');
        }

        // Validasi request
        $validator = Validator::make($request->all(), [
            'kategori_barang' => 'required|unique:jenis_barangs',
        ]);
        
        // Jika validasi request gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Record data
        $jenisBarang->kode_jenis_barang = $request->input('kode_jenis_barang');
        $jenisBarang->kategori_barang = $request->input('kategori_barang');
        $jenisBarang->update();
    
        return redirect()->back()->with('status', 'Updated Successfully');
    }

    public function destroy(Request $request)
    {
        $jenis_barang_id = $request->input('deleting_id');

        $jenisBarang = JenisBarang::find($jenis_barang_id);

        $jenisBarang->delete();

        return redirect()->back()->with('status', 'Deleted Successfully');
    }
}
