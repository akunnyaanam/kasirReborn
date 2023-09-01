<?php

namespace App\Http\Controllers;

use App\Models\DetailPengeluaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengeluaran = Pengeluaran::all();

        $urut = (Pengeluaran::count() == 0)? 10001 : (int)substr(Pengeluaran::all()->last()->kode_pengeluaran, - 5) + 1;
        $nomer = 'PNGLRN' . $urut;

        return view('dashboard.pengeluaran.index', compact('pengeluaran', 'nomer') ,[
            'title' => 'Form Pengeluaran',
            'desc' => 'Data-data Pengeluaran',
            'tableTitle' => 'Data Pengeluaran'
        ]);
    }

    // public function showDetail()
    // {
    //     $detailPengeluaran = DetailPengeluaran::all();

    //     return view('dashboard.pengeluaran.detailPengeluaran', compact('detailPengeluaran'), [
    //         'title' => 'Detail Pengeluaran'
    //     ]);
    // }

    public function filterPengeluaran(Request $request)
    {
        $tanggal = $request->input('tanggal');

        // Jika tanggal disediakan, lakukan filter berdasarkan tanggal
        if ($tanggal) {
            $detailPengeluaran = DetailPengeluaran::whereDate('created_at', $tanggal)->get();
        } else {
            // Jika tanggal tidak disediakan, ambil semua data pengeluaran
            $detailPengeluaran = DetailPengeluaran::all();
        }

        return view('dashboard.pengeluaran.detailPengeluaran', compact('tanggal', 'detailPengeluaran'), [
            'title' => 'Detail Pengeluaran'
        ]);
    }

    public function generatePDF(Request $request)
    {
        $tanggal = $request->input('tanggal');

        $detailPengeluaran = DetailPengeluaran::whereDate('created_at', $tanggal)
            ->get();

        $pdf = FacadePdf::loadView('dashboard.pengeluaran.pdf', compact('detailPengeluaran', 'tanggal'));
        
        return $pdf->stream('pengeluaran-'.$tanggal.'.pdf');
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
        $validatedData = $request->validate([
            'kode_pengeluaran' => 'required',
            'deskripsi' => 'required',
            'jumlah' => 'required',
        ], [
            'kode_pengeluaran.required' => 'Kode Pengeluaran harus diisi.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'jumlah.required' => 'Masukan Jumlah Uang yang DiButuhkan',
        ]);

        Pengeluaran::create($validatedData);

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
        $pengeluaran = Pengeluaran::find($id);
        return response()->json([
            'status'=>200,
            'pengeluaran'=>$pengeluaran
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'kode_pengeluaran' => 'required',
            'deskripsi' => 'required',
            'jumlah' => 'required'
        ], [
            'kode_pengeluaran.required' => 'Kode Pengeluaran harus diisi.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'jumlah.required' => 'Jumlah yang dibutuhkan harus diisi.',
        ]);

        Pengeluaran::where('id', $request->input('pengeluaran_id'))->update($validatedData);

        return redirect()->back()->with('status', 'Updated berhasillll');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $pengeluaran = $request->input('deleting_id');
        $pengeluaran = Pengeluaran::find($pengeluaran);
        $pengeluaran->delete();
        return redirect()->back()->with('status', 'Delete Berhasil');
    }

    public function verifikasiPengeluaran($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        // Cek apakah pengeluaran sudah terverifikasi sebelumnya
        if ($pengeluaran->detailPengeluaran()->count() === 0) {
            DetailPengeluaran::create([
                'pengeluaran_id' => $id,
                'status' => 'terverifikasi'
            ]);

            return redirect()->back()->with('status', 'Pengeluaran berhasil diverifikasi');
        } else {
            return redirect()->back()->with('status', 'Pengeluaran sudah terverifikasi sebelumnya');
        }
    }
}
