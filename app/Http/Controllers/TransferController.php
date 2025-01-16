<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Sparepart;
use App\Models\Warehouse;
use App\Models\WarehouseSpareparts;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    // Menampilkan daftar transfer
    public function index()
    {
        $transfers = Transfer::with(['sparepart', 'warehousePengirim', 'warehousePenerima'])->get();
        return view('transfer.index', compact('transfers'));
    }

    // Menampilkan form untuk membuat transfer
    public function create()
    {
        $spareparts = Sparepart::all();  // Daftar sparepart
        $warehouses = Warehouse::all();  // Daftar warehouse
        return view('transfer.create', compact('spareparts', 'warehouses'));
    }

    // Menyimpan data transfer
    public function store(Request $request)
    {   

    

        $request->validate([
            'spareparts.*.id' => 'required|exists:spareparts,id_sparepart',
            'gudang_pengirim' => 'required|exists:warehouses,id',
            'gudang_penerima' => 'required|exists:warehouses,id',
            'jumlah_barang' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'nama_pengguna' => 'required|string',
        ]);
        
        
       

        Transfer::create([
            'sparepart_id' => $request->sparepart_id,
            'gudang_pengirim' => $request->gudang_pengirim,
            'gudang_penerima' => $request->gudang_penerima,
            'jumlah_barang' => $request->jumlah_barang,
            'keterangan' => $request->keterangan,
            'nama_pengguna' => $request->nama_pengguna,
            'status' => 'pending',  // Status transfer adalah pending
        ]);

        return redirect()->route('transfer.index')->with('success', 'Permintaan transfer berhasil dibuat!');
    }

    // Menyetujui transfer dan memperbarui stok
    public function approve($id)
    {
        $transfer = Transfer::findOrFail($id);

        // Periksa apakah ada cukup stok di gudang pengirim
        $sparepartWarehouseSender = $transfer->sparepart->warehouses()
            ->where('warehouse_id', $transfer->gudang_pengirim)
            ->first();

        if ($sparepartWarehouseSender && $sparepartWarehouseSender->pivot->quantity >= $transfer->jumlah_barang) {
            // Update stok pengirim
            $sparepartWarehouseSender->pivot->quantity -= $transfer->jumlah_barang;
            $sparepartWarehouseSender->pivot->save();

            // Update stok penerima
            $sparepartWarehouseReceiver = $transfer->sparepart->warehouses()
                ->where('warehouse_id', $transfer->gudang_penerima)
                ->first();

            if ($sparepartWarehouseReceiver) {
                // Update stok jika relasi sudah ada
                $sparepartWarehouseReceiver->pivot->quantity += $transfer->jumlah_barang;
                $sparepartWarehouseReceiver->pivot->save();
            } else {
                // Jika relasi belum ada, buatkan relasi baru
                $transfer->sparepart->warehouses()->attach($transfer->gudang_penerima, [
                    'quantity' => $transfer->jumlah_barang,
                ]);
            }

            // Ubah status transfer menjadi approved
            $transfer->status = 'approved';
            $transfer->save();

            return redirect()->route('transfer.index')->with('success', 'Transfer disetujui dan stok diperbarui!');
        } else {
            return redirect()->route('transfer.index')->with('error', 'Stok di gudang pengirim tidak mencukupi!');
        }
    }

    public function getStok(Request $request)
    {
        $sparepartId = $request->query('sparepart_id');
        $warehouseId = $request->query('warehouse_id');
    
        if (!$sparepartId || !$warehouseId) {
            return response()->json(['stok' => 'Invalid parameters'], 400);
        }
    
        // Cari stok di gudang
        $stok = WarehouseSpareparts::where('sparepart_id', $sparepartId)
                    ->where('warehouse_id', $warehouseId)
                    ->value('quantity');
    
        return response()->json(['stok' => $stok ?? 0]);
    }
    
    public function exportCsv(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Query dengan filter tanggal
        $query = Transfer::with(['sparepart', 'warehousePengirim', 'warehousePenerima']);
    
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        // Ambil data dengan relasi yang diperlukan
        $transfers = $query->get();
    
        $filename = "transfer_" . date('YmdHis') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
    
        $columns = [
            'ID',
            'Sparepart Name',
            'Warehouse Pengirim',
            'Warehouse Penerima',
            'Quantity',
            'Keterangan',
            'Status',
            'Tanggal',
        ];
    
        $callback = function () use ($transfers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
    
            foreach ($transfers as $transfer) {
                fputcsv($file, [
                    $transfer->id,
                    $transfer->sparepart->nama_sparepart?? 'N/A',
                    $transfer->warehousePengirim->name ?? 'N/A',
                    $transfer->warehousePenerima->name ?? 'N/A',
                    $transfer->jumlah_barang ?? 0,
                    $transfer->keterangan ?? 'N/A',
                    $transfer->status,
                    $transfer->created_at->format('Y-m-d'),
                ]);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    

}
