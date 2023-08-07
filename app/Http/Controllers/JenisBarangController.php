<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JenisBarangController extends Controller
{
    public function index()
    {
        $jenisBarang = JenisBarang::all();
        $cek = JenisBarang::count();
        if($cek == 0){
            $urut = 10001;
            $nomer = 'JNBRG' . $urut;
        }else{
            $ambil = JenisBarang::all()->last();
            $urut = (int)substr($ambil->kode_jenis_barang, - 5) + 1;
            $nomer = 'JNBRG' . $urut;
        }
        return view('jenisBarang.index', compact('jenisBarang', 'nomer') ,[
            'title' => 'Data Jenis Barang'
        ]);
    }

    // public function create(): View
    // {
    //     return view('jenisBarang.create', [
    //         'title' => "Form Tambah Jenis Barang",
    //     ]);
    // }

    public function store(Request $request)
    {
        $jenisBarang = new JenisBarang();
        $jenisBarang->kode_jenis_barang = $request->input('kode_jenis_barang');
        $jenisBarang->kategori_barang = $request->input('kategori_barang');
        $jenisBarang->save();
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
