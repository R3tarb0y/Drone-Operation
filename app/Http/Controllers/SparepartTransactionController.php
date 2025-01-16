<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Sparepart;
use App\Models\SparepartTransaction;
use App\Models\Warehouse;
use App\Models\WarehouseSpareparts;
use Illuminate\Http\Request;

class SparepartTransactionController extends Controller
{

    public function index()
    {
        $transactions = SparepartTransaction::all();  // Fetch all transactions
        return view('sparepart.transaction.index', compact('transactions'));  // Pass the data to the view
    }
    // Show form for adding sparepart in (GET)
    public function showInTransactions() 
    {
        $transactions = SparepartTransaction::where('transaction_type', 'in')->with('sparepart')->get();
        $spareparts = Sparepart::all();
        $warehouses = Warehouse::all();
        return view('sparepart.transaction.in', compact('transactions', 'spareparts', 'warehouses'));
    }

    // Show form for adding sparepart out (GET)
    public function showOutTransactions()
    {
        $transactions = SparepartTransaction::where('transaction_type', 'out')->with('sparepart', 'asset')->get();
        $spareparts = Sparepart::all();
        $assets = Asset::all(); // Untuk menampilkan pilihan asset pada transaksi keluar
        return view('sparepart.transaction.out', compact('transactions', 'spareparts', 'assets'));
    }

    public function addStockToWarehouse(Request $request)
{
    $request->validate([
        'sparepart_id' => 'required|exists:spareparts,id_sparepart',
        'warehouse_id' => 'required|exists:warehouses,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $sparepart = Sparepart::findOrFail($request->sparepart_id);

    $sparepart->warehouses()->syncWithoutDetaching([
        $request->warehouse_id => ['quantity' => Sparepart::raw('quantity + ' . $request->quantity)],
    ]);

    return back()->with('success', 'Stok berhasil ditambahkan ke gudang.');
}


    // Method to handle the form submission (POST) for 'in'
    public function addSparepartIn(Request $request)
    {
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id_sparepart',
            'quantity' => 'required|integer|min:1',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $sparepart = Sparepart::findOrFail($request->sparepart_id);
        $warehouseId = $request->warehouse_id;

        // Ambil jumlah stok yang ada di gudang
        $currentStock = $sparepart->warehouses()->where('warehouse_id', $warehouseId)->first()->pivot->quantity ?? 0;

        // Perbarui jumlah sparepart di gudang tanpa menghapus data yang ada
        $sparepart->warehouses()->syncWithoutDetaching([
            $warehouseId => ['quantity' => $currentStock + $request->quantity],
        ]);

        // Simpan transaksi
        SparepartTransaction::create([
            'sparepart_id' => $sparepart->id_sparepart,
            'transaction_type' => 'in',
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('sparepart.transaction.index')->with('success', 'Transaksi masuk berhasil!');
    }
    public function subtractStockFromWarehouse(Request $request)
    {
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id_sparepart',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $sparepart = Sparepart::findOrFail($request->sparepart_id);
        $warehouse = $sparepart->warehouses()->find($request->warehouse_id);
    
        // Check if the warehouse has enough stock
        if (!$warehouse || $warehouse->pivot->quantity < $request->quantity) {
            return back()->with('error', 'Stok di gudang tidak mencukupi.');
        }
    
        // Reduce the stock in the warehouse pivot table
        $sparepart->warehouses()->updateExistingPivot($request->warehouse_id, [
            'quantity' => WarehouseSpareparts::raw('quantity - ' . $request->quantity),  // Subtract from pivot quantity
        ]);
    
        return back()->with('success', 'Stok berhasil dikurangi dari gudang.');
    }

    // Method to handle the form submission for 'out' (POST)
    public function subtractSparepartOut(Request $request)
{
    $request->validate([
        'sparepart_id' => 'required|exists:spareparts,id_sparepart',
        'quantity' => 'required|integer|min:1',
        'asset_id' => 'required|exists:assets,id_asset',  // Pastikan asset_id ada
    ]);

    // Retrieve sparepart
    $sparepart = Sparepart::findOrFail($request->sparepart_id);

    // Find warehouse where the stock will be deducted from (you may need additional logic here to define the warehouse)
    $warehouse = $sparepart->warehouses()->first();  // You can define which warehouse here if needed

    // Check if the sparepart has enough quantity in the warehouse
    $currentStockInWarehouse = $warehouse->pivot->quantity ?? 0;

    if ($currentStockInWarehouse < $request->quantity) {
        return back()->with('error', 'Jumlah sparepart tidak mencukupi di gudang.');
    }

    // Create 'out' transaction with asset association
    $transaction = SparepartTransaction::create([
        'sparepart_id' => $sparepart->id_sparepart,
        'transaction_type' => 'out',
        'quantity' => $request->quantity,
        'asset_id' => $request->asset_id,  // Pastikan asset_id diisi
    ]);

    // Update the quantity in the warehouse pivot table
    $sparepart->warehouses()->updateExistingPivot($warehouse->id, [
        'quantity' => WarehouseSpareparts::raw('quantity - ' . $request->quantity),  // Subtract from pivot quantity
    ]);

    return redirect()->route('sparepart.transaction.index')->with('success', 'Transaksi keluar berhasil!');
}
    


    // Store transaction (including out with asset association)
    public function storeOutTransaction(Request $request)
    {
        $validated = $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id_sparepart',
            'asset_id' => 'required|exists:assets,id_asset',
            'quantity' => 'required|integer|min:1',
        ]);
    
        // Simpan transaksi
        $transaction = SparepartTransaction::create([
            'sparepart_id' => $validated['sparepart_id'],
            'asset_id' => $validated['asset_id'],
            'quantity' => $validated['quantity'],
            'transaction_type' => 'out',
        ]);
    
        return redirect()->route('sparepart.transaction.out')->with('success', 'Transaksi berhasil disimpan!');
    }

    // Create new transaction (both 'in' and 'out' forms)
    public function create($transactionType)
    {
        $spareparts = Sparepart::all();
        $assets = Asset::all(); // Show assets for 'out' transaction
        return view('sparepart_transactions.create', compact('transactionType', 'spareparts', 'assets'));
    }

    // Store the transaction (general store function for 'in' and 'out')
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id_sparepart',
            'asset_id' => 'nullable|exists:assets,id_asset', // Asset could be null for 'in' transactions
            'transaction_type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
        ]);

        // Create the transaction
        SparepartTransaction::create($validated);

        return redirect()->route('sparepart.transaction.index')->with('success', 'Transaksi berhasil ditambahkan');
    }


}
