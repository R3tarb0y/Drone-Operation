<?php

namespace App\Http\Controllers;

use App\Models\Receive;
use Illuminate\Http\Request;
use App\Models\Requests;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Asset;
use Illuminate\Support\Facades\Response;
use App\Models\Sparepart;
use App\Models\Warehouse;

class ReceiveController extends Controller
{
    public function index()
    {
        // Ambil semua request yang belum selesai
        $requests = Requests::all();
        $receives = Receive::with(['request', 'request.sparepart'])->get();
    
        return view('receive.index', compact('requests', 'receives')); // Pastikan 'receives' benar
    }
    

    public function updateReceiveStatus(HttpRequest $request, $id)
    {
        $receive = Receive::findOrFail($id);
    
        $request->validate([
            'received_quantity' => 'required|integer|min:1',
        ]);
    
        $receivedQty = $request->received_quantity;
        $requestRecord = $receive->request;
    
        // Update jumlah diterima
        $receive->received_quantity += $receivedQty;
    
        if ($receive->received_quantity >= $requestRecord->quantity) {
            $receive->status = 'delivered';
        } else {
            $receive->status = 'pending_delivered';
        }
    
        $receive->save();
    
        // Perbarui stok warehouse
        $sparepart = $requestRecord->sparepart;
        $warehouse = $requestRecord->warehouse;
    
        $sparepartWarehouse = $sparepart->warehouses()->where('warehouse_id', $warehouse->id)->first();
    
        if ($sparepartWarehouse) {
            // Update stok jika relasi sudah ada
            $currentQuantity = $sparepartWarehouse->pivot->quantity;
            $sparepart->warehouses()->updateExistingPivot($warehouse->id, [
                'quantity' => $currentQuantity + $receivedQty,
            ]);
        } else {
            // Tambahkan relasi jika belum ada
            $sparepart->warehouses()->attach($warehouse->id, [
                'quantity' => $receivedQty,
            ]);
        }
    
        return redirect()->route('receive.index')->with('success', 'Barang berhasil diterima dan stok diperbarui!');
    }
    





    public function storeApproval(HttpRequest $request, $id)
    {
        $receive = Receive::findOrFail($id);
        $requestRecord = $receive->request;
    
        // Validasi jumlah yang diterima
        $maxQuantity = $requestRecord->quantity - $receive->received_quantity;
        $validated = $request->validate([
            'received_quantity' => 'required|integer|min:1|max:' . $maxQuantity,
        ]);
    
        $receivedQty = $validated['received_quantity'];
    
        // Update kuantitas yang diterima
        $receive->received_quantity += $receivedQty;
    
        // Tentukan status berdasarkan jumlah diterima
        if ($receive->received_quantity >= $requestRecord->quantity) {
            $receive->status = 'delivered';
        } else {
            $receive->status = 'pending_delivered';
        }
        
        $receive->save();
        
        // Jika kategori asset, simpan data asset
        if ($requestRecord->kategori === 'asset') {
            $assets = $request->input('assets'); // Mendapatkan array asset yang dikirimkan
            foreach ($assets as $assetData) {
                // Validasi input asset menggunakan nama dinamis
                $validatedAsset = $request->validate([
                    "assets.*.nama" => 'required|string',
                    "assets.*.jenis" => 'required|string',
                    "assets.*.manufacture" => 'required|string',
                    "assets.*.tahun" => 'required|string',
                ]);
                
    

                        // Simpan data asset
                Asset::create([
                    'nama_barang' => $assetData['nama'],
                    'jenis' => $assetData['jenis'],
                     'manufacture' => $assetData['manufacture'],
                    'tahun' => $assetData['tahun'],
                    'tanggal' => now(),
                ]);
                    
                
                
            }
        }
        
        // Untuk sparepart, kita perlu memperbarui stok di warehouse
        if ($requestRecord->kategori === 'sparepart') {
            $sparepart = $requestRecord->sparepart;
            $warehouse = $requestRecord->warehouse;
    
            // Update stok sparepart di warehouse
            $sparepartWarehouse = $sparepart->warehouses()->where('warehouse_id', $warehouse->id)->first();
    
            if ($sparepartWarehouse) {
                // Update stok jika relasi sudah ada
                $currentQuantity = $sparepartWarehouse->pivot->quantity;
                $sparepart->warehouses()->updateExistingPivot($warehouse->id, [
                    'quantity' => $currentQuantity + $receivedQty,
                ]);
            } else {
                // Tambahkan relasi jika belum ada
                $sparepart->warehouses()->attach($warehouse->id, [
                    'quantity' => $receivedQty,
                ]);
            }
        }
    
        return redirect()->route('receive.index')->with('success', 'Barang berhasil diterima dan stok diperbarui!');
    }
    
    
    public function exportCsv(HttpRequest $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Query dengan filter tanggal
        $query = Receive::query();
    
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        // Ambil data dengan filter tanggal dan relasi yang diperlukan
        $receives = $query->with(['request', 'request.sparepart', 'request.warehouse'])->get();
    
        $filename = "receives_" . date('YmdHis') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
    
        $columns = [
            'ID',
            'Request ID',
            'Sparepart Name',
            'Asset Name',
            'Warehouse Name',
            'Requested Quantity',
            'Received Quantity',
            'Status',
            'Tanggal',
        ];
    
        $callback = function () use ($receives, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
    
            foreach ($receives as $receive) {
                fputcsv($file, [
                    $receive->id,
                    $receive->request_id,
                    $receive->request->sparepart->nama_sparepart ?? 'N/A',
                    $receive->request->nama_asset ?? 'N/A',
                    $receive->request->warehouse->name ?? 'N/A',
                    $receive->request->quantity ?? 0,
                    $receive->received_quantity ?? 0,
                    $receive->status,
                    $receive->created_at->format('Y-m-d'),
                ]);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    

    

    




}
