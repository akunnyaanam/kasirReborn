<?php

namespace App\Http\Controllers;

use App\Events\StokTokoUpdated;
use App\Models\DetailStokGudang;
use App\Models\DetailStokToko;
use App\Models\HistoriDetailStokToko;
use App\Models\StokToko;
use App\Models\Toko;
use App\Models\TotalStokGudang;
use App\Models\TotalStokToko;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\DB;

class StoktokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detailstoktoko = DetailStokToko::all();
        $totalStokGudang = TotalStokGudang::all();
        $stoktoko = StokToko::all();
        $toko = Toko::all();

        return view('dashboard.stokToko.detailStokToko', compact('detailstoktoko', 'stoktoko', 'toko', 'totalStokGudang'), [
            'title' => 'Data Stok Toko',
        ]);
    }

    public function pembantu()
    {
        $toko = Toko::all();
        $totalStokGudang = TotalStokGudang::all();
        $urut = (StokToko::count() == 0)? 10001 : (int)substr(StokToko::all()->last()->kode_suratjalan, - 5) + 1 ;
        $nomer = 'SRJLN' . $urut;

        return view('dashboard.stoktoko.index', compact('toko', 'nomer', 'totalStokGudang') ,[
            'title' => 'Form Stok Toko'
        ]);
    }

    // public function cetakPdf($id, $ukuran)
    // {
    //     $stoktoko = StokToko::with('detailMutasi')->find($id);

        
    //     if ($ukuran === 'a4') {
    //         $pdf = FacadePdf::loadView('dashboard.stoktoko.pdf', compact('stoktoko', 'ukuran'));
    //         $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas A4
    //     } elseif ($ukuran === 'a6') {
    //         $pdf = FacadePdf::loadView('dashboard.stoktoko.pdf1', compact('stoktoko', 'ukuran'));
    //         $pdf->setPaper('a6', 'portrait'); // Set ukuran kertas A6
    //     }

    //     return $pdf->stream('surat_mutasi.pdf');
    // }

    public function cetakPdf($id, $ukuran)
    {
        $stoktoko = StokToko::with(['detailStokTokos' => function ($query) {
            $query->with(['barang.barang', 'barang.stokgudang.gudang']);
        }])->where('id', $id)->first();        

        if ($ukuran === 'a4') {
            $pdf = FacadePdf::loadView('dashboard.stoktoko.pdf', compact('stoktoko', 'ukuran'));
            $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas A4
        } elseif ($ukuran === 'a6') {
            $pdf = FacadePdf::loadView('dashboard.stoktoko.pdf1', compact('stoktoko', 'ukuran'));
            $pdf->setPaper('a6', 'portrait'); // Set ukuran kertas A6
        }

        return $pdf->stream('suratJalan.pdf');
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
        $data = $request->all();

        // Simpan informasi ke tabel StokToko
        $stoktoko = new StokToko();
        $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        $stoktoko->toko_id = $data['toko_id'];
        $stoktoko->save();

        // Loop melalui data barang dan stok
        foreach ($data['barang_id'] as $index => $value) {
            // Pecah nilai value menjadi id dan barang_id
            list($totalStokGudangId, $barang_id) = explode('-', $value);
            // $gudang_id = $data['gudang_awal_id'][$index];
            $stok_input = $data['stok'][$index];

            // Cari total stok di gudang berdasarkan id totalStokGudang
            $totalStokGudang = TotalStokGudang::findOrFail($totalStokGudangId);

            if ($totalStokGudang->total_stok >= $stok_input) {
                // Kurangi stok di gudang
                $totalStokGudang->total_stok -= $stok_input;
                $totalStokGudang->save();

                // Cari atau buat total stok untuk barang_id dan toko_id yang sama di TotalStokToko
                $totalStokToko = TotalStokToko::updateOrCreate(
                    ['toko_id' => $stoktoko->toko_id, 'barang_id' => $barang_id],
                    ['total_stok' => DB::raw("total_stok + $stok_input")]
                );

                // Buat entri baru di DetailStokToko
                $newDetailStokToko = new DetailStokToko();
                $newDetailStokToko->stoktoko_id = $stoktoko->id;
                $newDetailStokToko->barang_id = $barang_id;
                $newDetailStokToko->stok = $stok_input;
                // $newDetailStokToko->gudang_asal = $gudang_id; // Tambahkan gudang_asal
                $newDetailStokToko->save();
            } else {
                // Stok di gudang tidak mencukupi
                return redirect()->back()->with('error', 'Stok tidak mencukupi di gudang.');
            }
        }

        return redirect()->back()->with('status', 'Data Berhasil di input');
        // $data = $request->all();

        // // Simpan informasi ke tabel StokToko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $index => $barang_id_gudang_id) {
        //     $stok_input = $data['stok'][$index];

        //     // Extract id barang dan id gudang dari value dropdown
        //     list($barang_id, $gudang_id) = explode('-', $barang_id_gudang_id);

        //     // Cari total stok di gudang berdasarkan barang_id
        //     $totalStokGudang = TotalStokGudang::findOrFail($barang_id);

        //     if ($totalStokGudang->total_stok >= $stok_input) {
        //         // Kurangi stok di gudang
        //         $totalStokGudang->total_stok -= $stok_input;
        //         $totalStokGudang->save();

        //         // Cari atau buat total stok untuk barang_id dan toko_id yang sama di TotalStokToko
        //         $totalStokToko = TotalStokToko::updateOrCreate(
        //             ['toko_id' => $stoktoko->toko_id, 'barang_id' => $barang_id],
        //             ['total_stok' => DB::raw("total_stok + $stok_input"), 'gudang_id_asal' => $gudang_id]
        //         );

        //         // Buat entri baru di DetailStokToko
        //         $newDetailStokToko = new DetailStokToko();
        //         $newDetailStokToko->stoktoko_id = $stoktoko->id;
        //         $newDetailStokToko->barang_id = $barang_id;
        //         $newDetailStokToko->stok = $stok_input;
        //         // $newDetailStokToko->gudang_awal_id = $gudang_id; // Tambahkan gudang_id
        //         $newDetailStokToko->save();
        //     } else {
        //         // Stok di gudang tidak mencukupi
        //         return redirect()->back()->with('error', 'Stok tidak mencukupi di gudang.');
        //     }
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // // Simpan informasi ke tabel StokToko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $index => $barang_id) {
        //     $stok_input = $data['stok'][$index];

        //     // Cari total stok di gudang berdasarkan barang_id
        //     $totalStokGudang = TotalStokGudang::findOrFail($barang_id);

        //     if ($totalStokGudang->total_stok >= $stok_input) {
        //         // Kurangi stok di gudang
        //         $totalStokGudang->total_stok -= $stok_input;
        //         $totalStokGudang->save();

        //         // Cari atau buat total stok untuk barang_id dan toko_id yang sama di TotalStokToko
        //         $totalStokToko = TotalStokToko::updateOrCreate(
        //             ['toko_id' => $stoktoko->toko_id, 'barang_id' => $barang_id],
        //             ['total_stok' => DB::raw("total_stok + $stok_input")]
        //         );

        //         // Buat entri baru di DetailStokToko
        //         $newDetailStokToko = new DetailStokToko();
        //         $newDetailStokToko->stoktoko_id = $stoktoko->id;
        //         $newDetailStokToko->barang_id = $barang_id;
        //         $newDetailStokToko->stok = $stok_input;
        //         $newDetailStokToko->save();
        //     } else {
        //         // Stok di gudang tidak mencukupi
        //         return redirect()->back()->with('error', 'Stok tidak mencukupi di gudang.');
        //     }
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');
        // $data = $request->all();

        // // Simpan informasi ke tabel StokToko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $index => $barang_id) {
        //     $stok_input = $data['stok'][$index];

        //     // Cari total stok di gudang berdasarkan barang_id dan gudang_id
        //     $totalStokGudang = TotalStokGudang::findOrFail($barang_id);

        //     if ($totalStokGudang->total_stok >= $stok_input) {
        //         // Kurangi stok di gudang
        //         $totalStokGudang->total_stok -= $stok_input;
        //         $totalStokGudang->save();

        //         // Cari atau buat total stok untuk barang_id dan toko_id yang sama di TotalStokToko
        //         $totalStokToko = TotalStokToko::updateOrCreate(
        //             ['toko_id' => $stoktoko->toko_id, 'barang_id' => $barang_id],
        //             ['total_stok' => DB::raw("total_stok + $stok_input")]
        //         );

        //         // Buat entri baru di DetailStokToko
        //         $newDetailStokToko = new DetailStokToko();
        //         $newDetailStokToko->stoktoko_id = $stoktoko->id;
        //         $newDetailStokToko->barang_id = $barang_id;
        //         $newDetailStokToko->stok = $stok_input;
        //         $newDetailStokToko->save();
        //     } else {
        //         // Stok di gudang tidak mencukupi
        //         return redirect()->back()->with('error', 'Stok tidak mencukupi di gudang.');
        //     }
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // // Simpan informasi ke tabel StokToko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $index => $barang_id) {
        //     $stok_input = $data['stok'][$index];

        //     // Cari total stok di gudang berdasarkan barang_id
        //     $totalStokGudang = TotalStokGudang::findOrFail($barang_id);

        //     if ($totalStokGudang->total_stok >= $stok_input) {
        //         // Kurangi stok di gudang
        //         $totalStokGudang->total_stok -= $stok_input;
        //         $totalStokGudang->save();

        //         // Cari atau buat total stok untuk barang_id dan toko_id yang sama di TotalStokToko
        //         $totalStokToko = TotalStokToko::updateOrCreate(
        //             ['toko_id' => $stoktoko->toko_id, 'barang_id' => $barang_id],
        //             ['total_stok' => DB::raw("total_stok + $stok_input")]
        //         );

        //         // Buat entri baru di DetailStokToko
        //         $newDetailStokToko = new DetailStokToko();
        //         $newDetailStokToko->stoktoko_id = $stoktoko->id;
        //         $newDetailStokToko->barang_id = $barang_id;
        //         $newDetailStokToko->stok = $stok_input;
        //         $newDetailStokToko->save();
        //     } else {
        //         // Stok di gudang tidak mencukupi
        //         return redirect()->back()->with('error', 'Stok tidak mencukupi di gudang.');
        //     }
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // // Simpan informasi ke tabel StokToko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $index => $barang_id) {
        //     $stok_input = $data['stok'][$index];

        //     // Cari atau buat total stok untuk barang_id dan toko_id yang sama
        //     $totalStokToko = TotalStokToko::updateOrCreate(
        //         ['toko_id' => $stoktoko->toko_id, 'barang_id' => $barang_id],
        //         ['total_stok' => $data['stok'][$index]]
        //     );

        //     // Kurangi stok di gudang
        //     $totalStokGudang = TotalStokGudang::findOrFail($barang_id);

        //     if ($totalStokGudang->total_stok >= $data['stok'][$index]) {
        //         // Kurangi stok di gudang
        //         $totalStokGudang->total_stok -= $data['stok'][$index];
        //         $totalStokGudang->save();

        //         // Buat entri baru di DetailStokToko
        //         $newDetailStokToko = new DetailStokToko();
        //         $newDetailStokToko->stoktoko_id = $stoktoko->id;
        //         $newDetailStokToko->barang_id = $barang_id;
        //         $newDetailStokToko->stok = $data['stok'][$index];
        //         $newDetailStokToko->save();
        //     } else {
        //         // Stok di gudang tidak mencukupi
        //         return redirect()->back()->with('error', 'Stok tidak mencukupi di gudang.');
        //     }
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // // Simpan informasi ke tabel stoktoko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $item => $value) {
        //     // Cek apakah barang sudah ada di toko ini dengan kombinasi barang_id dan id stoktoko
        //     $existingDetailStokToko = DetailStokToko::where('stoktoko_id', $stoktoko->id)
        //                                             ->where('barang_id', $value)
        //                                             ->first();

        //     if ($existingDetailStokToko) {
        //         // Jika barang sudah ada, tambahkan stok di toko
        //         $existingDetailStokToko->stok += $data['stok'][$item];
        //         $existingDetailStokToko->save();
        
        //         // Tambahkan juga stok di total_stok_tokos
        //         TotalStokToko::updateOrCreate(
        //             ['toko_id' => $stoktoko->toko_id, 'barang_id' => $value],
        //             ['total_stok' => DB::raw("total_stok + {$data['stok'][$item]}")]
        //         );
        //     } else {
        //         // Buat entri baru di DetailStokToko
        //         $newDetailStokToko = new DetailStokToko();
        //         $newDetailStokToko->stoktoko_id = $stoktoko->id;
        //         $newDetailStokToko->barang_id = $value;
        //         $newDetailStokToko->stok = $data['stok'][$item];
        //         $newDetailStokToko->save();
        
        //         // Kurangi stok di gudang
        //         $totalStokGudang = TotalStokGudang::findOrFail($value);
        
        //         if ($totalStokGudang->total_stok >= $data['stok'][$item]) {
        //             // Kurangi stok di gudang
        //             $totalStokGudang->total_stok -= $data['stok'][$item];
        //             $totalStokGudang->save();
        
        //             // Tambahkan juga stok di total_stok_tokos
        //             TotalStokToko::updateOrCreate(
        //                 ['toko_id' => $stoktoko->toko_id, 'barang_id' => $value],
        //                 ['total_stok' => DB::raw("total_stok + {$data['stok'][$item]}")]
        //             );
        //         } else {
        //             // Stok di gudang tidak cukup, berikan respons atau lakukan penanganan lain
        //             return redirect()->back()->with('error', 'Stok tidak mencukupi di gudang.');
        //         }
        //     }
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // // Simpan informasi ke tabel stoktoko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $item => $value) {
        //     // Cek apakah barang sudah ada di toko ini dengan kombinasi barang_id dan id stoktoko
        //     $existingDetailStokToko = DetailStokToko::where('stoktoko_id', $stoktoko->id)
        //                                             ->where('barang_id', $value)
        //                                             ->first();

        //     if ($existingDetailStokToko) {
        //         // Jika barang sudah ada, tambahkan stok di toko
        //         $existingDetailStokToko->stok += $data['stok'][$item];
        //         $existingDetailStokToko->save();
        //     } else {
        //         // Buat entri baru di DetailStokToko
        //         $newDetailStokToko = new DetailStokToko();
        //         $newDetailStokToko->stoktoko_id = $stoktoko->id;
        //         $newDetailStokToko->barang_id = $value;
        //         $newDetailStokToko->stok = $data['stok'][$item];
        //         $newDetailStokToko->save();
        //     }

        //     // // Catat histori stok di HistoriDetailStokToko
        //     // $histori = new HistoriDetailStokToko();
        //     // $histori->detail_stok_toko_id = $existingDetailStokToko ? $existingDetailStokToko->id : $newDetailStokToko->id;
        //     // $histori->stok = $data['stok'][$item];
        //     // $histori->save();

        //     // Kurangi stok di gudang
        //     $totalStokGudang = TotalStokGudang::where('id', $value)->first();
        //     $totalStokGudang->total_stok -= $data['stok'][$item];
        //     $totalStokGudang->save();

        //     // Cari atau buat total stok untuk barang_id dan toko_id yang sama
        //     TotalStokToko::updateOrCreate(
        //         ['toko_id' => $stoktoko->toko_id, 'barang_id' => $totalStokGudang->barang_id],
        //         ['total_stok' => DB::raw("total_stok + {$data['stok'][$item]}")]
        //     );
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // // Simpan informasi ke tabel stoktoko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $item => $value) {
        //     // Cek apakah barang sudah ada di toko ini
        //     $existingDetailStokToko = DetailStokToko::where('stoktoko_id', $stoktoko->id)
        //                                             ->where('barang_id', $value)
        //                                             ->first();

        //     if ($existingDetailStokToko) {
        //         // Jika barang sudah ada, tambahkan stok di toko
        //         $existingDetailStokToko->stok += $data['stok'][$item];
        //         $existingDetailStokToko->save();
        //     } else {
        //         // Buat entri baru di DetailStokToko
        //         $newDetailStokToko = new DetailStokToko();
        //         $newDetailStokToko->stoktoko_id = $stoktoko->id;
        //         $newDetailStokToko->barang_id = $value;
        //         $newDetailStokToko->stok = $data['stok'][$item];
        //         $newDetailStokToko->save();
        //     }

        //     // Catat histori stok di HistoriDetailStokToko
        //     $histori = new HistoriDetailStokToko();
        //     $histori->detail_stok_toko_id = $existingDetailStokToko ? $existingDetailStokToko->id : $newDetailStokToko->id;
        //     $histori->stok = $data['stok'][$item];
        //     $histori->save();

        //     // Kurangi stok di gudang
        //     $detailStokGudang = DetailStokGudang::where('id', $value)->first();
        //     $detailStokGudang->stok -= $data['stok'][$item];
        //     $detailStokGudang->save();
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // // Simpan informasi ke tabel stoktoko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Inisialisasi array untuk melacak total stok yang akan dikurangkan di DetailStokGudang
        // $totalStokToBeReduced = [];

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $item => $value) {
        //     // Cek apakah barang sudah ada di toko ini
        //     $existingBarang = DetailStokToko::where('stoktoko_id', $data['toko_id'])
        //                                     ->where('barang_id', $value)
        //                                     ->first();

        //     if ($existingBarang) {
        //         // Jika barang sudah ada, tambahkan stok di toko dan tambahkan ke array total stok yang akan dikurangkan
        //         $existingBarang->stok += $data['stok'][$item];
        //         $existingBarang->save();

        //         // Tambahkan stok yang akan dikurangkan ke dalam array
        //         if (isset($totalStokToBeReduced[$value])) {
        //             $totalStokToBeReduced[$value] += $data['stok'][$item];
        //         } else {
        //             $totalStokToBeReduced[$value] = $data['stok'][$item];
        //         }

        //         $histori = new HistoriDetailStokToko();
        //         $histori->detail_stok_toko_id = $existingBarang->id;
        //         $histori->stok = $data['stok'][$item];
        //         $histori->save();

        //     } else {
        //         // Cari detail stok gudang berdasarkan barang_id yang sesuai
        //         $detailStokGudang = DetailStokGudang::where('id', $value)->first();

        //         if (!$detailStokGudang) {
        //             return redirect()->back()->withInput()
        //                 ->withErrors(['barang_id' => 'Barang dengan ID ' . $value . ' tidak ditemukan di stok gudang'])
        //                 ->with('error', 'Gagal menambahkan barang karena barang tidak ditemukan di stok gudang');
        //         }

        //         // Cek apakah stok di gudang cukup
        //         if ($detailStokGudang->stok < $data['stok'][$item]) {
        //             return redirect()->back()->withInput()
        //                 ->withErrors(['barang_id' => 'Stok di gudang tidak mencukupi'])
        //                 ->with('error', 'Gagal menambahkan barang karena stok di gudang tidak mencukupi');
        //         }

        //         // Barang belum ada di toko ini, maka simpan
        //         $data2 = array(
        //             'stoktoko_id' => $data['toko_id'],
        //             'barang_id' => $value,
        //             'stok' => $data['stok'][$item]
        //         );
        //         $newDetailStokToko = DetailStokToko::create($data2);

        //         // Tambahkan stok yang akan dikurangkan ke dalam array
        //         if (isset($totalStokToBeReduced[$value])) {
        //             $totalStokToBeReduced[$value] += $data['stok'][$item];
        //         } else {
        //             $totalStokToBeReduced[$value] = $data['stok'][$item];
        //         }

        //         $histori = new HistoriDetailStokToko();
        //         $histori->detail_stok_toko_id = $newDetailStokToko->id;
        //         $histori->stok = $data['stok'][$item];
        //         $histori->save();
        //     }
        // }

        // // Kurangi stok di gudang sesuai dengan total stok yang akan dikurangkan
        // foreach ($totalStokToBeReduced as $barangId => $stokToBeReduced) {
        //     $detailStokGudang = DetailStokGudang::where('id', $barangId)->first();
        //     $detailStokGudang->stok -= $stokToBeReduced;
        //     $detailStokGudang->save();
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // // Simpan informasi ke tabel stoktoko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Inisialisasi array untuk melacak total stok yang akan dikurangkan di DetailStokGudang
        // $totalStokToBeReduced = [];

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $item => $value) {
        //     // Cek apakah barang sudah ada di toko ini
        //     $existingBarang = DetailStokToko::where('stoktoko_id', $data['toko_id'])
        //                                     ->where('barang_id', $value)
        //                                     ->first();

        //     if ($existingBarang) {
        //         // Jika barang sudah ada, tambahkan stok di toko dan tambahkan ke array total stok yang akan dikurangkan
        //         $existingBarang->stok += $data['stok'][$item];
        //         $existingBarang->save();

        //         // Tambahkan stok yang akan dikurangkan ke dalam array
        //         if (isset($totalStokToBeReduced[$value])) {
        //             $totalStokToBeReduced[$value] += $data['stok'][$item];
        //         } else {
        //             $totalStokToBeReduced[$value] = $data['stok'][$item];
        //         }

        //         $lastHistoriStok = HistoriDetailStokToko::where('detail_stok_toko_id', $existingBarang->id)
        //                                             ->orderBy('created_at', 'desc')
        //                                             ->first();
        //         $historiStok = $lastHistoriStok ? $lastHistoriStok->stok : $existingBarang->stok;

        //         $historiDetailStokToko = new HistoriDetailStokToko();
        //         $historiDetailStokToko->detail_stok_toko_id = $existingBarang->id;
        //         $historiDetailStokToko->stok = $historiStok;
        //         $historiDetailStokToko->save();

        //     } else {
        //         // Cari detail stok gudang berdasarkan barang_id yang sesuai
        //         $detailStokGudang = DetailStokGudang::where('id', $value)->first();

        //         if (!$detailStokGudang) {
        //             return redirect()->back()->withInput()
        //                 ->withErrors(['barang_id' => 'Barang dengan ID ' . $value . ' tidak ditemukan di stok gudang'])
        //                 ->with('error', 'Gagal menambahkan barang karena barang tidak ditemukan di stok gudang');
        //         }

        //         // Cek apakah stok di gudang cukup
        //         if ($detailStokGudang->stok < $data['stok'][$item]) {
        //             return redirect()->back()->withInput()
        //                 ->withErrors(['barang_id' => 'Stok di gudang tidak mencukupi'])
        //                 ->with('error', 'Gagal menambahkan barang karena stok di gudang tidak mencukupi');
        //         }

        //         // Barang belum ada di toko ini, maka simpan
        //         $data2 = array(
        //             'stoktoko_id' => $data['toko_id'],
        //             'barang_id' => $value,
        //             'stok' => $data['stok'][$item]
        //         );
        //         $newDetailStokToko = DetailStokToko::create($data2);

        //         // Tambahkan stok yang akan dikurangkan ke dalam array
        //         if (isset($totalStokToBeReduced[$value])) {
        //             $totalStokToBeReduced[$value] += $data['stok'][$item];
        //         } else {
        //             $totalStokToBeReduced[$value] = $data['stok'][$item];
        //         }

        //         // Tambahkan kode untuk mencatat perubahan stok ke dalam historidetailstoktoko
        //         $historiDetailStokToko = new HistoriDetailStokToko();
        //         $historiDetailStokToko->detail_stok_toko_id = $newDetailStokToko->id;
        //         $historiDetailStokToko->stok = $data['stok'][$item];
        //         $historiDetailStokToko->save();
        //     }
        // }

        // // Kurangi stok di gudang sesuai dengan total stok yang akan dikurangkan
        // foreach ($totalStokToBeReduced as $barangId => $stokToBeReduced) {
        //     $detailStokGudang = DetailStokGudang::where('id', $barangId)->first();
        //     $detailStokGudang->stok -= $stokToBeReduced;
        //     $detailStokGudang->save();
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // // Simpan informasi ke tabel stoktoko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $item => $value) {
        //     // Cek apakah barang sudah ada di toko ini
        //     $existingBarang = DetailStokToko::where('stoktoko_id', $data['toko_id'])
        //                                     ->where('barang_id', $value)
        //                                     ->first();

        //     if ($existingBarang) {
        //         return redirect()->back()->withInput()
        //             ->withErrors(['barang_id' => 'Barang dengan ID ' . $value . ' sudah ada di toko ini'])
        //             ->with('existing_barang_id', $value)
        //             ->with('error', 'Gagal menambahkan barang karena barang sudah ada di toko ini');
        //     } else {
        //         // Cari detail stok gudang berdasarkan barang_id yang sesuai
        //         $detailStokGudang = DetailStokGudang::where('id', $value)->first();

        //         if (!$detailStokGudang) {
        //             return redirect()->back()->withInput()
        //                 ->withErrors(['barang_id' => 'Barang dengan ID ' . $value . ' tidak ditemukan di stok gudang'])
        //                 ->with('error', 'Gagal menambahkan barang karena barang tidak ditemukan di stok gudang');
        //         }

        //         // Cek apakah stok di gudang cukup
        //         if ($detailStokGudang->stok < $data['stok'][$item]) {
        //             return redirect()->back()->withInput()
        //                 ->withErrors(['barang_id' => 'Stok di gudang tidak mencukupi'])
        //                 ->with('error', 'Gagal menambahkan barang karena stok di gudang tidak mencukupi');
        //         }

        //         // Barang belum ada di toko ini, maka simpan
        //         $data2 = array(
        //             'stoktoko_id' => $data['toko_id'],
        //             'barang_id' => $value,
        //             'stok' => $data['stok'][$item]
        //         );
        //         DetailStokToko::create($data2);

        //         // Kurangi stok di gudang
        //         $detailStokGudang->stok -= $data['stok'][$item];
        //         $detailStokGudang->save();
        //     }
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // // Simpan informasi ke tabel stoktoko
        // $stoktoko = new StokToko();
        // $stoktoko->kode_suratjalan = $data['kode_suratjalan'];
        // $stoktoko->toko_id = $data['toko_id'];
        // $stoktoko->save();

        // // Loop melalui data barang dan stok
        // foreach ($data['barang_id'] as $item => $value) {
        //     // Cek apakah barang sudah ada di gudang ini
        //     $existingBarang = DetailStokToko::where('stoktoko_id', $data['toko_id'])
        //                                     ->where('barang_id', $value)
        //                                     ->first();

        //     if ($existingBarang) {
        //         return redirect()->back()->withInput()
        //             ->withErrors(['barang_id' => 'Barang dengan ID ' . $value . ' sudah ada di gudang ini'])
        //             ->with('existing_barang_id', $value)
        //             ->with('error', 'Gagal menambahkan barang karena barang sudah ada di gudang ini');
        //     } else {
        //         // Barang belum ada di gudang ini, maka simpan
        //         $data2 = array(
        //             'stoktoko_id' => $data['toko_id'],
        //             'barang_id' => $value,
        //             'stok' => $data['stok'][$item]
        //         );
        //         DetailStokToko::create($data2);
        //     }
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
