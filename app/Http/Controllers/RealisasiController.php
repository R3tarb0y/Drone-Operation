<?php

namespace App\Http\Controllers;

use App\Models\Realisasi;
use App\Models\Estimation;
use App\Models\Asset;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Sparepart;
use App\Models\Requests;
use App\Models\WarehouseSpareparts;
use App\Models\SparepartTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RealisasiController extends Controller
{
    // Menampilkan semua data realisasi
    public function index()
    {
        $realisasi = Realisasi::with(['asset', 'estimation'])->get();
        $spareparts = Sparepart::all();
        return view('realisasi.index', compact('realisasi','spareparts'));
    }

// In RealisasiController.php



public function approve($id, HttpRequest $request)
{
    // Validasi payment_type
    $request->validate([
        'payment_type' => 'required|string|in:bayar_sendiri,garansi,asuransi,pilot,asuransi_pilot',
    ]);

    // Ambil data realisasi
    $realisasi = Realisasi::findOrFail($id);
    Log::info('Mengambil data realisasi', ['realisasi_id' => $id]);

    // Simpan payment_type dan set status approved
    $realisasi->payment_type = $request->payment_type;
    $realisasi->is_approved = true;
    Log::info('Payment type diset dan realisasi di-approve', [
        'payment_type' => $request->payment_type,
        'realisasi_id' => $id,
    ]);

    // Ambil spareparts dan inisialisasi total_cost
    $spareparts = json_decode($realisasi->spareparts, true);
    $totalCost = $realisasi->total_cost;
    Log::info('Memulai proses sparepart', ['spareparts' => $spareparts]);

    // Logika berdasarkan payment_type
    switch ($request->payment_type) {
        case 'bayar_sendiri':
            foreach ($spareparts as $sparepart) {
                Log::info('Proses bayar sendiri dimulai untuk sparepart', ['sparepart' => $sparepart]);

                // Cari data di warehouse berdasarkan id_sparepart
                $warehouseSparepart = WarehouseSpareparts::where('sparepart_id', $sparepart['id_sparepart'])->first();

                if (!$warehouseSparepart) {
                    Log::error('Sparepart tidak ditemukan di warehouse', [
                        'sparepart_id' => $sparepart['id_sparepart']
                    ]);
                    return redirect()->back()->with('error', 'Stok tidak ditemukan untuk sparepart_id: ' . $sparepart['id_sparepart']);
                }

                // Kurangi stok di warehouse
                Log::info('Stok sebelum dikurangi', [
                    'sparepart_id' => $sparepart['id_sparepart'],
                    'quantity_sebelum' => $warehouseSparepart->quantity
                ]);

                $warehouseSparepart->quantity -= $sparepart['quantity'];

                // Cek apakah stok cukup
                if ($warehouseSparepart->quantity < 0) {
                    Log::error('Stok tidak cukup untuk diproses', [
                        'sparepart_id' => $sparepart['id_sparepart'],
                        'quantity_tersisa' => $warehouseSparepart->quantity
                    ]);
                    return redirect()->back()->with('error', 'Stok tidak cukup untuk memproses transaksi.');
                }

                // Simpan perubahan stok
                $warehouseSparepart->save();
                Log::info('Stok berhasil diperbarui', [
                    'sparepart_id' => $sparepart['id_sparepart'],
                    'quantity_terbaru' => $warehouseSparepart->quantity
                ]);

                // Catat transaksi keluar untuk sparepart
                SparepartTransaction::create([
                    'asset_id' => $realisasi->asset_id, // Asset terkait
                    'sparepart_id' => $sparepart['id_sparepart'], // Sparepart yang dikeluarkan
                    'quantity' => $sparepart['quantity'], // Jumlah yang dikeluarkan
                    'transaction_type' => 'out', // Jenis transaksi keluar
                    'transaction_date' => Carbon::now('Asia/Jakarta'),
                ]);
            }
            break;

        case 'garansi':
            // Set total cost menjadi 0 untuk garansi
            $totalCost = 0;
            Log::info('Payment type garansi, total_cost diset menjadi 0', ['realisasi_id' => $id]);

            // Catat transaksi garansi (tidak ada pengurangan stok)
            foreach ($spareparts as $sparepart) {
                SparepartTransaction::create([
                    'asset_id' => $realisasi->asset_id,
                    'sparepart_id' => $sparepart['id_sparepart'],
                    'quantity' => $sparepart['quantity'],
                    'transaction_type' => 'out',
                    'transaction_date' => Carbon::now('Asia/Jakarta'),
                ]);
            }
            break;

            case 'asuransi_pilot':
                // Set total cost menjadi 0 untuk garansi
                $totalCost = 5000000;
                Log::info('Payment type garansi, total_cost diset menjadi 5 juta', ['realisasi_id' => $id]);
    
                // Catat transaksi garansi (tidak ada pengurangan stok)
                foreach ($spareparts as $sparepart) {
                    SparepartTransaction::create([
                        'asset_id' => $realisasi->asset_id,
                        'sparepart_id' => $sparepart['id_sparepart'],
                        'quantity' => $sparepart['quantity'],
                        'transaction_type' => 'out',
                        'transaction_date' => Carbon::now('Asia/Jakarta'),
                    ]);
                }
                break;
            
                case 'pilot':
                    // Initialize total cost to 0
                    $totalCost = 0;
                    Log::info('Payment type pilot, menghitung total cost berdasarkan spareparts', ['realisasi_id' => $id]);
                
                    // Calculate the total cost based on the spareparts' quantity and unit price
                    foreach ($spareparts as $sparepart) {
                        $totalCost += $sparepart['quantity'] * $sparepart['unit_price'];
                
                        // Catat transaksi untuk sparepart (tidak ada pengurangan stok)
                        SparepartTransaction::create([
                            'asset_id' => $realisasi->asset_id,
                            'sparepart_id' => $sparepart['id_sparepart'],
                            'quantity' => $sparepart['quantity'],
                            'transaction_type' => 'out',
                            'transaction_date' => Carbon::now('Asia/Jakarta'),
                        ]);
                    }
                    Log::info('Total cost dihitung untuk pilot', ['total_cost' => $totalCost]);
                
                    break;
                

        case 'asuransi':
            // Set total cost menjadi 5 juta untuk asuransi
            $totalCost = 5000000;
            Log::info('Payment type asuransi, total_cost diset menjadi 5 juta', ['realisasi_id' => $id]);

            // Catat transaksi asuransi (tidak ada pengurangan stok)
            foreach ($spareparts as $sparepart) {
                SparepartTransaction::create([
                    'asset_id' => $realisasi->asset_id,
                    'sparepart_id' => $sparepart['id_sparepart'],
                    'quantity' => $sparepart['quantity'],
                    'transaction_type' => 'out',
                    'transaction_date' => Carbon::now('Asia/Jakarta'),
                ]);
            }
            break;

        default:
            Log::error('Payment type tidak valid', ['payment_type' => $request->payment_type]);
            return redirect()->back()->with('error', 'Payment type tidak valid.');
    }

    // Simpan perubahan total_cost
    $realisasi->total_cost = $totalCost;
    $realisasi->save();
    Log::info('Realisasi berhasil di-update', ['realisasi_id' => $id, 'total_cost' => $totalCost]);

    // Redirect dengan pesan sukses
    return redirect()->route('realisasi.index')->with('success', 'Realisasi approved and handled successfully.');
}



public function exportCsv(HttpRequest $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Validasi dan parsing tanggal
    if ($startDate && $endDate) {
        try {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Tanggal tidak valid.');
        }
    }

    // Query data Realisasi dengan filter tanggal dan relasi terkait
    $query = Realisasi::with(['asset', 'estimation']);

    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    $realisasiData = $query->get();

    // Nama file untuk diunduh
    $filename = "realisasi_" . date('YmdHis') . ".csv";

    // Header untuk file CSV
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    // Kolom untuk CSV
    $columns = [
        'ID',
        'Asset Name',
        'Spareparts',
        'Payment Type',
        'Total Cost',
        'Status',
        'Tanggal Realisasi',
    ];

    // Callback untuk pembuatan CSV
    $callback = function () use ($realisasiData, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($realisasiData as $realisasi) {
            $sparepartsDetails = collect(json_decode($realisasi->spareparts, true))->map(function ($sparepart) {
                return "{$sparepart['nama_barang']} (Qty: {$sparepart['quantity']}, Price: {$sparepart['unit_price']})";
            })->implode('; ');
            fputcsv($file, [
                $realisasi->id,
                $realisasi->asset->nama_barang ?? 'N/A',
                $sparepartsDetails,
                $realisasi->payment_type ?? 'N/A',
                $realisasi->total_cost ?? 0,
                $realisasi->is_approved ? 'Approved' : 'Pending',
                $realisasi->created_at ? $realisasi->created_at->format('Y-m-d') : 'N/A',
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}


}
