<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailStokGudang;
use App\Models\Gudang;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;


class StokgudangController extends Controller
{
    // public function index(): View
    // {
    //     $stokGudang = StokGudang::oldest()->paginate();
    //     $barang = Barang::all();
    //     $gudang = Gudang::all();

    //     return view('dashboard.stokGudang.index', compact('stokGudang', 'barang', 'gudang'), [
    //         'title' => "Data Stok Gudang",
    //     ]);
    // }
    public function index()
    {
        $gudang = Gudang::all();
        $barang = Barang::all();
        return view('dashboard.stokGudang1.index', compact('gudang', 'barang'), [
            'title' => 'Stok Gudang',
        ]);
    }
    
    public function store(Request $request)
    {      
        $data = $request->all();
    
        // Simpan informasi ke tabel stokgudang
        $stokGudang = new StokGudang();
        $stokGudang->gudang_id = $data['gudang_id'];
        $stokGudang->save();
    
        // Daftar barang yang sudah ada di gudang ini
        $existingBarangIds = DetailStokGudang::whereHas('stokgudang', function ($query) use ($data) {
            $query->where('gudang_id', $data['gudang_id']);
        })->pluck('barang_id')->toArray();
    
        // Loop melalui data barang dan stok
        foreach ($data['barang_id'] as $item => $value) {
            // Cek apakah barang sudah ada di gudang ini
            if (in_array($value, $existingBarangIds)) {
                return redirect()->back()->withInput()
                    ->withErrors(['barang_id' => 'Barang dengan ID ' . $value . ' sudah ada di gudang ini'])
                    ->with('existing_barang_id', $value)
                    ->with('error', 'Gagal menambahkan barang karena barang sudah ada di gudang ini');
            } else {
                // Barang belum ada di gudang ini, maka simpan
                $data2 = array(
                    'stokgudang_id' => $stokGudang->id,
                    'barang_id' => $value,
                    'stok' => $data['stok'][$item]
                );
                DetailStokGudang::create($data2);
            }
        }
    
        return redirect()->back()->with('status', 'Data Berhasil di input');
        
        // Simpan infor
        // $data = $request->all();

        // // Simpan informasi ke tabel stokgudang
        // $stokGudang = new StokGudang();
        // $stokGudang->gudang_id = $data['gudang_id'];
        // $stokGudang->save();

        // // Loop melalui data barang dan stok
        // if (count($data['barang_id']) > 0) {
        //     foreach ($data['barang_id'] as $item => $value) {
        //         $data2 = array(
        //             'stokgudang_id' => $stokGudang->id,
        //             'barang_id' => $value, // Menggunakan $value bukan $data['address'][$item]
        //             'stok' => $data['stok'][$item]
        //         );
        //         DetailStokGudang::create($data2);

        //         $barang = Barang::findOrFail($value);
        //         $barang->id_gudang = $data['gudang_id'];
        //         $barang->save();
        //     }
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

    }

    public function detail()
    {
        $stokgudang = StokGudang::all();
        $gudangs = Gudang::all();
        
        return view('dashboard.stokGudang1.detail', compact('stokgudang', 'gudangs'), [
            'title' => 'Detail Stok Gudang',
        ]);
    }
    
    public function detailStokgudang($id)
    {
        $stokgudang = StokGudang::with('detail')->where('id', $id)->first();

        return view('dashboard.stokGudang1.detail_stokgudang', compact('stokgudang'), [
            'title' => 'Detail Data Stok Gudang',
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
        // // Validasi input yang diterima dari pengguna dengan pesan kustom
        // $validator = Validator::make($request->all(), [
        //     'stok' => 'required|integer|min:0', // Memastikan quantity tidak bernilai minus
        // ]);

        // // Jika validasi gagal, kembalikan pengguna ke halaman formulir dengan pesan kesalahan
        // if ($validator->fails()) {
        //     return redirect()->back()->with('gagal', 'Gagal Menambahkan Karena Input Tidak Cocok');
        // }
        // $stokGudang = new StokGudang();
        // $stokGudang->id_barang = $request->input('kode_barang');
        // $stokGudang->id_gudang = $request->input('kode_gudang');
        // $stokGudang->stok = $request->input('stok');
        // $stokGudang->save();
        // return redirect()->back()->with('status', 'Status berhasillll');
    // }

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
    public function edit($id)
    {
        $detailStokGudang = DetailStokGudang::findOrFail($id);

        return response()->json(['detailstokgudang' => $detailStokGudang]);
    }

    public function update(Request $request)
    {
        $detailstokgudang_id = $request->input('detailstokgudang_id');
        $tambah_stok = $request->input('tambah_stok');
        $kurangi_stok = $request->input('kurangi_stok');

        // Mendapatkan data detail stok dari database berdasarkan ID
        $detailStokGudang = DetailStokGudang::find($detailstokgudang_id);

        if (!$detailStokGudang) {
            return redirect()->back()->with('error', 'Data detail stok tidak ditemukan.');
        }

        // Menghitung stok baru setelah operasi penambahan atau pengurangan
        $stok_sekarang = $detailStokGudang->stok;
        $stok_baru = ($stok_sekarang + $tambah_stok) - $kurangi_stok;

        if ($stok_baru < 0) {
            return redirect()->back()->with('error', 'Stok tidak cukup untuk melakukan pengurangan.');
        }

        if ($tambah_stok == 0) {
            return redirect()->back()->with('error', 'Tidak terjadi update karena bernilai 0.');
        }

        // Update stok baru ke dalam database
        $detailStokGudang->update(['stok' => $stok_baru]);

        return redirect()->back()->with('status', 'Stok berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // $stokGudang = $request->input('deleting_id');
        // $stokGudang = StokGudang::find($stokGudang);
        // $stokGudang->delete();
        // return redirect()->back()->with('status', 'Delete Berhasil');
    }

    // public function tambahStok()
    // {
    //     $gudang = Gudang::all();
    //     $barang = Barang::all();
    //     return view('dashboard.stokGudang.tambah_stok', compact('gudang', 'barang'));
    // }

    // public function simpanStok(Request $request)
    // {
    //     foreach ($request->id_barang as $key => $id_barang) {
    //         $gudang = Gudang::find($request->id_gudang[$key]);
    //         $barang = Barang::find($id_barang);
            
    //         if ($gudang && $barang) {
    //             $stokGudang = new StokGudang([
    //                 'stok' => $request->stok[$key],
    //             ]);
                
    //             $gudang->stok()->save($stokGudang, [
    //                 'barang_id' => $barang->id,
    //             ]);
    //         }
    //     }

    //     return response()->json(['sukses' => true]);
    // }
    
    // public function formtambahbanyak()
    // {
    //     if(request()->ajax()){
    //         $barang = Barang::all();
    //         $gudang = Gudang::all();
            
    //         $data = [
    //             'data' => view('dashboard.stokGudang.formtambahbanyak', compact('barang', 'gudang'))->render()
    //         ];
            
    //         return response()->json($data);
    //     }
    // }
    
    // public function simpandatabanyak()
    // {
    //     if(request()->ajax()){
    //         $barang = request('barang');
    //         $gudang = request('gudang');
    //         $stok = request('stok');
    
    //         $jumlahdata = count($barang);
    
    //         for($i = 0; $i < $jumlahdata; $i++){
    //             StokGudang::create([
    //                 'barang' => $barang[$i],
    //                 'gudang' => $gudang[$i],
    //                 'stok' => $stok[$i]
    //             ]);
    //         }
    
    //         $msg = [
    //             'sukses' => $jumlahdata . " data stok Gudang berhasil di tambahkan"
    //         ];
    
    //         return response()->json($msg);
    //     }
    // }

    // public function simpanStok(Request $request)
    // {
    //     foreach ($request->id_barang as $key => $id_barang) {
    //         StokGudang::create([
    //             'id_gudang' => $request->id_gudang,
    //             'id_barang' => $id_barang,
    //             'stok' => $request->stok[$key],
    //         ]);
    //     }

    //     return response()->json(['sukses' => true]);
    // }
}
