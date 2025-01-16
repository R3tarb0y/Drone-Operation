<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\Warehouse;
use App\Models\SparepartTransaction;

use Illuminate\Http\Request;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua gudang untuk dropdown
        $warehouses = Warehouse::all();
    
        // Cek apakah ada filter berdasarkan warehouse_id
        if ($request->has('warehouse_id') && $request->warehouse_id != '') {
            // Jika ada filter, ambil sparepart yang terkait dengan gudang tertentu
            $spareparts = Sparepart::whereHas('warehouses', function ($query) use ($request) {
                $query->where('warehouses.id', $request->warehouse_id);
            })->get();
    
            // Hitung total quantity dari sparepa rt yang terkait dengan gudang yang dipilih
            $totalQuantity = $spareparts->sum(function ($sparepart) {
                return $sparepart->warehouses->where('id', request('warehouse_id'))->first()->pivot->quantity ?? 0;
            });
        } else {
            // Jika tidak ada filter, ambil semua sparepart dan hitung total quantity keseluruhan
            $spareparts = Sparepart::all();
            $totalQuantity = $spareparts->sum('quantity'); // Jumlahkan seluruh quantity dari semua sparepart
        }
    
        // Kirim data sparepart, total quantity, dan warehouse ke view
        return view('sparepart.index', compact('spareparts', 'warehouses', 'totalQuantity'));
    }
    

    public function store(Request $request)
    {
        // Validasi input dan simpan data transaksi
        $transaction = new SparepartTransaction();
        $transaction->sparepart_id = $request->sparepart_id;
        $transaction->quantity = $request->quantity;
        $transaction->warehouse_id = $request->warehouse_id;
        $transaction->save();

        // Update quantity sparepart berdasarkan perubahan di gudang
        $sparepart = Sparepart::find($request->sparepart_id);
        $totalQuantity = $sparepart->warehouses()->sum('sparepart_warehouse.quantity');
        
        // Update nilai quantity di tabel sparepart
        $sparepart->quantity = $totalQuantity;
        $sparepart->save();

        return redirect()->route('sparepart.index');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $spareparts = Sparepart::where('nama_sparepart', 'LIKE', '%' . $query . '%')->get();

        return response()->json($spareparts);
    }

    public function addSparepart(Request $request)
    {   
        // Validasi input
        $validated = $request->validate([
            'kode_material' => 'required|unique:spareparts,kode_material',
            'nama_sparepart' => 'required|string|max:255',
        ]);
    
        // Buat sparepart baru
        Sparepart::create([
            'kode_material' => $validated['kode_material'],
            'nama_sparepart' => $validated['nama_sparepart'],
            'quantity' => 0, // Default quantity
        ]);
    
        // Redirect dengan pesan sukses
        return redirect()->route('sparepart.index')->with('success', 'Sparepart berhasil ditambahkan!');
    }
    
}
    