<?php

namespace App\Http\Controllers;

use App\Models\DetailStokToko;
use App\Models\DetailTransaksi;
use App\Models\StokToko;
use App\Models\TotalStokToko;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;


class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = now()->format('dmYHi');
        $urut = (Transaksi::count() == 0) ? 10001 : (int)substr(Transaksi::all()->last()->kode_transaksi, -5) + 1;
        $nomer = 'TRS' . $now . $urut;


        $namaPengguna = Auth::user()->name;
        $daftarBarang = TotalStokToko::whereHas('totalStokGudang.barang', function ($query) {
            $query->where('toko_id', 1);
        })->get();
        

        return view('dashboard.transaksi.index', compact('nomer', 'namaPengguna', 'daftarBarang'), [
            'title' => 'Transaksi'
        ]);
    }

    public function histori(Request $request)
    {
        $tanggal = $request->input('tanggal');

        // Jika tanggal disediakan, lakukan filter berdasarkan tanggal
        if ($tanggal) {
            $transaksiData = Transaksi::whereDate('created_at', $tanggal)->get();
        } else {
            // Jika tanggal tidak disediakan, ambil semua data pengeluaran
            $transaksiData = Transaksi::all();
        }
        // $query = Transaksi::with(['user', 'detailTransaksis']);

        // // Cek apakah ada tanggal dalam permintaan
        // if ($request->has('tanggal')) {
        //     $tanggal = Carbon::parse($request->tanggal);
        //     $query->whereDate('created_at', $tanggal);
        // }

        // $transaksiData = $query->get();

        return view('dashboard.transaksi.histori', compact('transaksiData', 'tanggal'), [
            'title' => 'Histori Transaksi',
        ]);
    }

    public function generatePDF(Request $request)
    {
        $tanggal = $request->input('tanggal');

        $transaksis = Transaksi::whereDate('created_at', $tanggal)
            ->get();

        $pdf = FacadePdf::loadView('dashboard.transaksi.pdf', compact('transaksis', 'tanggal'));
        
        return $pdf->stream('HistoriTransaksi-'.$tanggal.'.pdf');
    }

    // public function generatePDF(Request $request)
    // {
    //     $tanggal = $request->input('tanggal');

    //     // Jika tanggal disediakan, lakukan filter berdasarkan tanggal
    //     if ($tanggal) {
    //         $transaksiData = Transaksi::with(['user', 'detailTransaksis'])
    //             ->whereDate('created_at', $tanggal)
    //             ->get();
    //     } else {
    //         // Jika tanggal tidak disediakan, ambil semua data transaksi
    //         $transaksiData = Transaksi::with(['user', 'detailTransaksis'])
    //             ->get();
    //     }

    //     $pdf = FacadePdf::loadView('dashboard.transaksi.pdf', compact('transaksiData', 'tanggal'));

    //     return $pdf->stream('HistoriTransaksi-'.$tanggal.'.pdf');
    // }

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
        // Validasi data yang diterima dari form
        $request->validate([
            'kode_transaksi' => 'required',
            'uang_pembayaran' => 'required',
            'uang_kembalian' => 'required',
            'total_keseluruhan' => 'required',
            'barang_id' => 'required|array',
            'jumlah' => 'required|array',
            'total_harga' => 'required|array', // Pastikan total_harga juga disertakan dalam validasi
        ]);

        // Simpan transaksi
        $transaksi = new Transaksi;
        $transaksi->kode_transaksi = $request->kode_transaksi;
        $transaksi->user_id = auth()->user()->id;
        $transaksi->harga_total = (int) str_replace(['Rp', ' ', '.'], '', $request->total_keseluruhan);
        $transaksi->uang_pembayaran = (int) str_replace(['Rp', ' ', '.'], '', $request->uang_pembayaran);
        $transaksi->uang_kembalian = (int) str_replace(['Rp', ' ', '.'], '', $request->uang_kembalian);
        $transaksi->save();

        // Simpan detail transaksi
        $barangIds = $request->input('barang_id');
        $jumlahArray = $request->input('jumlah');
        $totalHargaArray = $request->input('total_harga'); // Pastikan total_harga juga diambil dari request

        foreach ($barangIds as $index => $barangId) {
            $jumlah = $jumlahArray[$index];
            $totalHarga = (int) str_replace(['Rp', ' ', '.'], '', $totalHargaArray[$index]);
        
            $detailTransaksi = new DetailTransaksi();
            $detailTransaksi->transaksi_id = $transaksi->id;
            $detailTransaksi->barangtoko_id = $barangId;
            $detailTransaksi->jumlah = $jumlah;
            $detailTransaksi->total = $totalHarga;
            $detailTransaksi->save();
        
            // Update stok toko
            $barangToko = TotalStokToko::findOrFail($barangId);
            $barangToko->total_stok -= $jumlah;
            $barangToko->save();
        }

        return redirect()->back()->with('status', 'Transaksi Berhasil');
        
        // $request->validate([
        //     'kode_transaksi' => 'required',
        //     'uang_pembayaran' => 'required',
        //     'uang_kembalian' => 'required',
        //     'total_keseluruhan' => 'required',
        //     'barang_id' => 'required|array',
        //     'jumlah' => 'required|array'
        // ]);
    
        // // Simpan transaksi
        // $transaksi = new Transaksi;
        // $transaksi->kode_transaksi = $request->kode_transaksi;
        // $transaksi->user_id = auth()->user()->id;
        // $transaksi->harga_total = (int) str_replace(['Rp', ' ', '.'], '', $request->total_keseluruhan);
        // $transaksi->uang_pembayaran = (int) str_replace(['Rp', ' ', '.'], '', $request->uang_pembayaran);
        // $transaksi->uang_kembalian = (int) str_replace(['Rp', ' ', '.'], '', $request->uang_kembalian);
        // $transaksi->save();

        // $barangIds = $request->input('barang_id');
        // $jumlahArray = $request->input('jumlah');
        // $totalHargaArray = $request->input('total_harga'); // Make sure this corresponds to the input field name

        // foreach ($barangIds as $index => $barangId) {
        //     $jumlah = $jumlahArray[$index];
        //     $totalHarga = $totalHargaArray[$index]; // Get the corresponding total harga for this index
            
        //     // Simpan detail transaksi
        //     $detailTransaksi = new DetailTransaksi();
        //     $detailTransaksi->transaksi_id = $transaksi->id;
        //     $detailTransaksi->barangtoko_id = $barangId;
        //     $detailTransaksi->jumlah = $jumlah;
        //     $detailTransaksi->total = $totalHarga; // Use the correct variable here
        //     $detailTransaksi->save();
        // }

        // return redirect()->back()->with('status', 'Berhasil');

        // $kodeTransaksi = $request->input('kode_transaksi');
        // $user_id = auth()->user()->id;
        // $uangDibayar = $request->input('uangDibayar');
        // $totalKeseluruhan = $request->input('totalKeseluruhan');
        
        // // Simpan data transaksi
        // $transaksi = new Transaksi();
        // $transaksi->kode_transaksi = $kodeTransaksi;
        // $transaksi->user_id = $user_id;
        // $transaksi->harga_total = $totalKeseluruhan;
        // $transaksi->uang_pembayaran = $uangDibayar;
        // $transaksi->uang_kembalian = $uangDibayar - $totalKeseluruhan;
        // $transaksi->save();
        
        // // Simpan detail transaksi (baris barang yang dipilih)
        // $selectedItems = $request->input('selected_items');
        // foreach ($selectedItems as $item) {
        //     $barangId = $item['barang_id'];
        //     $jumlah = $item['jumlah'];
            
        //     $detailTransaksi = new DetailTransaksi();
        //     $detailTransaksi->transaksi_id = $transaksi->id;
        //     $detailTransaksi->barangtoko_id = $barangId;
        //     $detailTransaksi->jumlah = $jumlah;
        //     $detailTransaksi->save();
        // }
        
        // return redirect()->back()->with('status', 'Transaksi berhasil disimpan.');
        // Ambil data dari formulir
        // $kodeTransaksi = $request->kode_transaksi;
        // $userId = auth()->user()->id;
        // // $hargaTotal = $request->harga_total;
        // $hargaTotal = str_replace(['Rp', ' ', '.'], '', $request->harga_total);
        // $hargaTotal = str_replace(',', '.', $hargaTotal);
        // $uangPembayaran = str_replace(['Rp', ' ', '.'], '', $request->uang_pembayaran);
        // $uangPembayaran = str_replace(',', '.', $uangPembayaran);
        // $uangKembalian = str_replace(['Rp', ' ', '.'], '', $request->uang_kembalian);
        // $uangKembalian = str_replace(',', '.', $uangKembalian);
        // // $uangPembayaran = $request->uang_pembayaran;
        // // $uangKembalian = $request->uang_kembalian;
        // // $selectedItems = json_decode($request->selected_items);
        // $selectedItemsJson = $request->input('selected_items_json');
        // $selectedItems = json_decode($selectedItemsJson);

        // // Simpan data transaksi ke dalam database
        // $transaksi = new Transaksi();
        // $transaksi->kode_transaksi = $kodeTransaksi;
        // $transaksi->user_id = $userId;
        // // $transaksi->harga_total = $hargaTotal;
        // $transaksi->harga_total = floatval($hargaTotal);
        // $transaksi->uang_pembayaran = floatval($uangPembayaran);
        // $transaksi->uang_kembalian = floatval($uangKembalian);
        // // $transaksi->uang_pembayaran = $uangPembayaran;
        // // $transaksi->uang_kembalian = $uangKembalian;
        // $transaksi->save();

        // // Simpan detail barang transaksi ke dalam database
        // foreach ($selectedItems as $item) {
        //     $detailTransaksi = new DetailTransaksi();
        //     $detailTransaksi->transaksi_id = $transaksi->id;
        //     $detailTransaksi->barang_id = $item['kode']; // Menggunakan sintaksis array
        //     $detailTransaksi->jumlah = $item['jumlah']; // Menggunakan sintaksis array
        //     $detailTransaksi->save();
        // }

        // // Redirect atau berikan respons sesuai kebutuhan Anda
        // return redirect()->back()->with('status', 'berhasil');

        // $selectedItems = $request->input('selectedItems', []);
        // $data = $request->all();
        // // Simpan data transaksi
        // $transaksi = new Transaksi();
        // $transaksi->kode_transaksi = $request->input('kode_transaksi');
        // $transaksi->user_id = auth()->user()->id;
        // $transaksi->harga_total = $data['harga_total'];
        // $transaksi->uang_pembayaran = $request->input('uang_pembayaran');
        // $transaksi->uang_kembalian = $data['uang_kembalian'];
        // $transaksi->save();

        // // Simpan detail transaksi untuk setiap barang yang dipilih
        // foreach ($selectedItems as $item) {
        //     if ($item['jumlah'] > 0) {
        //         $detailTransaksi = new DetailTransaksi();
        //         $detailTransaksi->transaksi_id = $transaksi->id;
        //         $detailTransaksi->barangtoko_id = $item['barangId'];
        //         $detailTransaksi->jumlah = $item['jumlah'];
        //         $detailTransaksi->save();
        //     }
        // }

        // return response()->json(['message' => 'Transaksi berhasil disimpan.']);
    }

    private function parseTotalPembelian($amount)
    {
        $amount = str_replace(['Rp', '.', ','], '', $amount);
        return intval($amount);
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
