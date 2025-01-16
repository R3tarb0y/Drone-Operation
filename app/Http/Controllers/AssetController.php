<?php

namespace App\Http\Controllers;
use App\Models\SparepartTransaction;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::all(); // Ambil semua data dari tabel 'asset'
        return view('asset.index', compact('assets'));
    }

    

    public function showHistory($id_asset)
    {
        // Retrieve the asset by id_asset (not id)
        $asset = Asset::findOrFail($id_asset);
    
        // Retrieve transactions for this asset
        $transactions = SparepartTransaction::where('asset_id', $id_asset)
                                            ->where('transaction_type', 'out')
                                            ->get();
    
        // Return view with asset and transactions data
        return view('asset.history', compact('asset', 'transactions'));
    }
    public function edit($id_asset)
    {
        $asset = Asset::findOrFail($id_asset);
        return view('asset.edit', compact('asset'));
    }
    
    public function update(Request $request, $id_asset)
    {
        $asset = Asset::findOrFail($id_asset);
    
        $validated = $request->validate([
            'manufacture' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'tahun' => 'required|string|max:4',
        ]);
    
        $asset->update($validated);
    
        return redirect()->route('asset.index')->with('success', 'Asset updated successfully!');
    }
    


}
