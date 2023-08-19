<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Mutasi;
use App\Models\StokGudang;
use App\Models\DetailMutasi;
use Illuminate\Http\Request;
use App\Models\DetailStokGudang;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;

class MutasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gudang = Gudang::all();
        $mutasis = Mutasi::with('detailMutasi')->get();

        return view('dashboard.mutasi.dataMutasi', compact('mutasis', 'gudang') ,[
            'title' => 'Mutasi',
            'desc'=> 'Data Mutasi',
            'tableTitle' => 'Data Mutasi'
        ]);
    }

    public function pembantu()
    {
        $detailstokgudang = DetailStokGudang::all();
        $gudang = Gudang::all();
        $mutasi = Mutasi::all();

        $urut = (Mutasi::count() == 0)? 10001 : (int)substr(Mutasi::all()->last()->kode_mutasi, - 5) + 1 ;
        $nomer = 'MTSI' . $urut;

        return view('dashboard.mutasi.index', compact('mutasi', 'nomer', 'detailstokgudang', 'gudang') ,[
            'title' => 'Mutasi',
            'desc'=> 'Data Mutasi',
            'tableTitle' => 'Data Mutasi'
        ]);
    }

    public function cetakPdf($id, $ukuran)
    {
        $mutasis = Mutasi::with('detailMutasi')->find($id);

        
        if ($ukuran === 'a4') {
            $pdf = FacadePdf::loadView('dashboard.mutasi.pdf', compact('mutasis', 'ukuran'));
            $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas A4
        } elseif ($ukuran === 'a6') {
            $pdf = FacadePdf::loadView('dashboard.mutasi.pdf1', compact('mutasis', 'ukuran'));
            $pdf->setPaper('a6', 'portrait'); // Set ukuran kertas A6
        }

        return $pdf->stream('surat_mutasi.pdf');


        // $mutasis = Mutasi::with('detailMutasi')->find($id);

        // $pdf = FacadePdf::loadView('dashboard.mutasi.pdf', compact('mutasis'))->setPaper($ukuran);

        // return $pdf->download('mutasi.pdf');
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

        $mutasi = new Mutasi();
        $mutasi->kode_mutasi = $data['kode_mutasi'];
        $mutasi->save();

        if (isset($data['barang_id']) && count($data['barang_id']) > 0) {
            foreach ($data['barang_id'] as $item => $value) {
                $detailStokgudang = DetailStokGudang::find($value);

                if ($detailStokgudang) {
                    $stokAwal = $detailStokgudang->stok;
                    $stokKeluar = $data['jumlah'][$item];

                    if ($stokAwal >= $stokKeluar) {
                        if ($stokKeluar <= $stokAwal) {
                            if (isset($data['gudang_tujuan_id'][$item])) {
                                $gudangTujuanID = $data['gudang_tujuan_id'][$item];
                
                                $detailStokgudangTujuan = DetailStokGudang::where('stokgudang_id', $gudangTujuanID)
                                    ->where('barang_id', $value)
                                    ->first();
                
                                if ($detailStokgudangTujuan) {
                                    if ($detailStokgudangTujuan->stokgudang->gudang_id == $gudangTujuanID) {
                                        return redirect()->back()->with('error', 'Barang sudah ada di gudang tujuan.');
                                    }
                                }
                            }
                            // Mengurangkan stok di gudang asal
                            $detailStokgudang->stok -= $stokKeluar;
                            $detailStokgudang->save();

                            // Menambahkan data stok di gudang tujuan (jika belum ada)
                            if (isset($data['gudang_tujuan_id'][$item])) {
                                $gudangTujuanID = $data['gudang_tujuan_id'][$item];
                                $detailStokgudangTujuan = DetailStokGudang::where('stokgudang_id', $gudangTujuanID)
                                    ->where('barang_id', $value)
                                    ->first();

                                if ($detailStokgudangTujuan) {
                                    // Update stok yang ada di gudang tujuan
                                    $detailStokgudangTujuan->stok += $stokKeluar;
                                    $detailStokgudangTujuan->save();
                                } else {
                                    // Buat entri baru di stok_gudangs untuk gudang tujuan
                                    $newStokgudangTujuan = new StokGudang();
                                    $newStokgudangTujuan->gudang_id = $gudangTujuanID;
                                    $newStokgudangTujuan->save();

                                    // Buat entri baru di detail_stok_gudangs
                                    $newDetailStokgudangTujuan = new DetailStokGudang();
                                    $newDetailStokgudangTujuan->stokgudang_id = $newStokgudangTujuan->id;
                                    $newDetailStokgudangTujuan->barang_id = $value;
                                    $newDetailStokgudangTujuan->stok = $stokKeluar;
                                    $newDetailStokgudangTujuan->save();
                                }
                                
                                // Menambahkan data mutasi
                                $detailMutasi = new DetailMutasi();
                                $detailMutasi->mutasi_id = $mutasi->id;
                                $detailMutasi->barang_id = $value;
                                $detailMutasi->gudang_awal_id = $detailStokgudang->stokgudang->gudang_id;
                                $detailMutasi->gudang_tujuan_id = $gudangTujuanID;
                                $detailMutasi->jumlah_barang = $stokKeluar;
                                $detailMutasi->save();
                            }
                        } else {
                            // Jumlah stok yang ingin dipindahkan melebihi stok awal
                            return redirect()->back()->with('error', 'Jumlah stok melebihi stok di gudang.');
                        }
                    } else {
                        // Jumlah stok yang ingin dipindahkan melebihi stok awal
                        return redirect()->back()->with('error', 'Jumlah stok melebihi stok di gudang.');
                    }
                }
            }
        }

        return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // $mutasi = new Mutasi();
        // $mutasi->kode_mutasi = $data['kode_mutasi'];
        // $mutasi->save();

        // if (isset($data['barang_id']) && count($data['barang_id']) > 0) {
        //     foreach ($data['barang_id'] as $item => $value) {
        //         // Mengambil data detail stok gudang berdasarkan barang_id
        //         $detailStokgudang = DetailStokgudang::find($value);

        //         if ($detailStokgudang) {
        //             $stokAwal = $detailStokgudang->stok;
        //             $stokKeluar = $data['jumlah'][$item];

        //             if ($stokAwal >= $stokKeluar) {
        //                 // Cek apakah jumlah stok yang ingin dipindahkan tidak melebihi stok awal
        //                 if ($stokKeluar <= $stokAwal) {
        //                     if (isset($data['gudang_tujuan_id'][$item])) {
        //                         $gudangTujuanID = $data['gudang_tujuan_id'][$item];
        
        //                         $detailStokgudangTujuan = DetailStokgudang::where('stokgudang_id', $gudangTujuanID)
        //                             ->where('barang_id', $value)
        //                             ->first();
        
        //                         if ($detailStokgudangTujuan) {
        //                             return redirect()->back()->with('error', 'Barang sudah ada di gudang');
        //                         }
        //                     }
        //                     else {
        //                         // Mengurangkan stok di gudang asal
        //                         $detailStokgudang->stok -= $stokKeluar;
        //                         $detailStokgudang->save();

        //                         // Menambahkan data stok di gudang tujuan (jika belum ada)
        //                         $detailStokgudangTujuan = DetailStokgudang::where('stokgudang_id', $data['gudang_tujuan_id'][$item])
        //                             ->where('barang_id', $value)
        //                             ->first();

        //                         if ($detailStokgudangTujuan) {
        //                             // Update stok yang ada di gudang tujuan
        //                             $detailStokgudangTujuan->stok += $stokKeluar;
        //                             $detailStokgudangTujuan->save();
        //                         } else {
        //                             // Buat entri baru di stok_gudangs untuk gudang tujuan
        //                             $newStokgudangTujuan = new StokGudang();
        //                             $newStokgudangTujuan->gudang_id = $data['gudang_tujuan_id'][$item];
        //                             $newStokgudangTujuan->save();

        //                             // Buat entri baru di detail_stok_gudangs
        //                             $newDetailStokgudangTujuan = new DetailStokgudang();
        //                             $newDetailStokgudangTujuan->stokgudang_id = $newStokgudangTujuan->id;
        //                             $newDetailStokgudangTujuan->barang_id = $value;
        //                             $newDetailStokgudangTujuan->stok = $stokKeluar; // Set stok awal sesuai jumlah mutasi
        //                             $newDetailStokgudangTujuan->save();
        //                         }
                                
        //                         // Menambahkan data mutasi
        //                         $detailMutasi = new DetailMutasi();
        //                         $detailMutasi->mutasi_id = $mutasi->id;
        //                         $detailMutasi->barang_id = $value;
        //                         $detailMutasi->gudang_awal_id = $detailStokgudang->stokgudang->gudang_id;
        //                         $detailMutasi->gudang_tujuan_id = $data['gudang_tujuan_id'][$item];
        //                         $detailMutasi->jumlah_barang = $stokKeluar;
        //                         $detailMutasi->save();
        //                     }
        //                 } else {
        //                     // Jumlah stok yang ingin dipindahkan melebihi stok awal
        //                     return redirect()->back()->with('error', 'Jumlah stok melebihi stok di gudang.');
        //                 }
        //             } else {
        //                 // Jumlah stok yang ingin dipindahkan melebihi stok awal
        //                 return redirect()->back()->with('error', 'Jumlah stok melebihi stok di gudang.');
        //             }
        //         }
        //     }
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');




        // $data = $request->all();

        // $mutasi = new Mutasi();
        // $mutasi->kode_mutasi = $data['kode_mutasi'];
        // $mutasi->save();

        // if (isset($data['barang_id']) && count($data['barang_id']) > 0) {
        //     $detailData = [];

        //     foreach ($data['barang_id'] as $item => $value) {
        //         // Mengambil data detail stok gudang berdasarkan barang_id
        //         $detailStokgudang = DetailStokgudang::find($value);

        //         if ($detailStokgudang) {
        //             $stokAwal = $detailStokgudang->stok;
        //             $stokKeluar = $data['jumlah'][$item];

        //             if ($stokAwal >= $stokKeluar) {
        //                 $detailData[] = [
        //                     'mutasi_id' => $mutasi->id,
        //                     'barang_id' => $value,
        //                     'gudang_awal_id' => $detailStokgudang->stokgudang->gudang_id,
        //                     'gudang_tujuan_id' => $data['gudang_tujuan_id'][$item],
        //                     'jumlah_barang' => $stokKeluar,
        //                     'created_at' => now(),
        //                     'updated_at' => now(),
        //                 ];

        //                 // Kurangi stok di detailstokgudang
        //                 $detailStokgudang->stok -= $stokKeluar;
        //                 $detailStokgudang->save();
        //             }
        //         }
        //     }

        //     // Menyimpan semua data detail mutasi dalam satu proses
        //     DetailMutasi::insert($detailData);
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');

        // $data = $request->all();

        // $mutasi = new Mutasi();
        // $mutasi->kode_mutasi = $data['kode_mutasi'];
        // $mutasi->save();

        // if (isset($data['barang_id']) && count($data['barang_id']) > 0) {
        //     $detailData = [];

        //     foreach ($data['barang_id'] as $item => $value) {
        //         // Mengambil data detail stok gudang berdasarkan barang_id
        //         $detailStokgudang = DetailStokgudang::find($value);

        //         if ($detailStokgudang) {
        //             $detailData[] = [
        //                 'mutasi_id' => $mutasi->id,
        //                 'barang_id' => $value,
        //                 'gudang_awal_id' => $detailStokgudang->stokgudang->gudang_id,
        //                 'gudang_tujuan_id' => $data['gudang_tujuan_id'][$item],
        //                 'jumlah_barang' => $data['jumlah'][$item],
        //                 'created_at' => now(),
        //                 'updated_at' => now(),
        //             ];
        //         }
        //     }

        //     // Menyimpan semua data detail mutasi dalam satu proses
        //     DetailMutasi::insert($detailData);
        // }

        // return redirect()->back()->with('status', 'Data Berhasil di input');
        // $data = $request->all();

        // // Membuat objek Mutasi baru dan menyimpannya
        // $mutasi = new Mutasi();
        // $mutasi->kode_mutasi = $data['kode_mutasi'];
        // $mutasi->save();

        // if (count($data['barang_id']) > 0) {
        //     foreach ($data['barang_id'] as $item => $value) {
        //         $detailData = [
        //             'mutasi_id' => $mutasi->id,
        //             'barang_id' => $value, // Menggunakan $value bukan $data['barang_id'][$item]
        //             'gudang_awal_id' => $data['gudang_awal_id'][$item],
        //             'gudang_tujuan_id' => $data['gudang_tujuan_id'][$item],
        //             'jumlah_barang' => $data['jumlah'][$item],
        //         ];
        
        //         DetailMutasi::create($detailData);
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
