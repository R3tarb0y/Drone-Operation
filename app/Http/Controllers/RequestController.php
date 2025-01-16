<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\Requests;  // Pastikan model Requests diimport
use App\Models\Warehouse;
use App\Models\Receive;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        $spareparts = Sparepart::all();  
        $warehouses = Warehouse::all(); 
        $requests = Requests::all();  

        return view('request.index', compact('spareparts', 'warehouses', 'requests'));
    }


    public function store(HttpRequest $request)
    {
        // Validasi berdasarkan kategori
        if ($request->input('kategori') === 'asset') {
            // Validasi untuk kategori asset
            $validated = $request->validate([
                'kategori' => 'required|string|in:asset',
                'kode_material' => 'required|string',
                'nama_asset' => 'required|string',
                'warehouse_id' => 'required|exists:warehouses,id',
                'quantity' => 'required|integer|min:1',
                'keterangan' => 'nullable|string',
            ]);
        } elseif ($request->input('kategori') === 'sparepart') {
            // Validasi untuk kategori sparepart
            $validated = $request->validate([
                'kategori' => 'required|string|in:sparepart',
                'spareparts' => 'required|array|min:1',
                'spareparts.*.id' => 'required|exists:spareparts,id_sparepart',
                'spareparts.*.quantity' => 'required|integer|min:1',
                'warehouse_id' => 'required|exists:warehouses,id',
                'keterangan' => 'nullable|string',
                'no_pp' => 'required|string',
            ]);
        } else {
            return redirect()->back()->with('error', 'Kategori tidak valid.');
        }
    
        try {
            if ($validated['kategori'] === 'sparepart') {
                $spareparts = $validated['spareparts'];
    
                foreach ($spareparts as $sparepart) {
                    $sparepartModel = Sparepart::find($sparepart['id']);
    
                    Requests::create([
                        'kategori' => 'sparepart',
                        'sparepart_id' => $sparepart['id'],
                        'kode_material' => $sparepartModel ? $sparepartModel->kode_material : null,
                        'warehouse_id' => $validated['warehouse_id'],
                        'quantity' => $sparepart['quantity'],
                        'unit_price' => null, // Disimpan null untuk sekarang
                        'keterangan' => $validated['keterangan'],
                        'no_pp' => $validated['no_pp'],
                        'status' => 'pp',
                    ]);
                }
            } elseif ($validated['kategori'] === 'asset') {
                Requests::create([
                    'kategori' => 'asset',
                    'kode_material' => $validated['kode_material'],
                    'nama_asset' => $validated['nama_asset'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'quantity' => $validated['quantity'],
                    'keterangan' => $validated['keterangan'] ?? null,
                    'status' => 'pending',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data ke database: ' . $e->getMessage());
            return redirect()->route('request.index')->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    
        return redirect()->route('request.index')->with('success', 'Data berhasil disimpan.');
    }
    
    public function exportCSV(HttpRequest $request)
    {
        // Ambil filter dari input form
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $sumberDana = $request->input('sumberdana');
        
        // Query data berdasarkan filter
        $query = Requests::query();
        
        // Filter berdasarkan tanggal jika ada
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Filter berdasarkan sumber dana jika ada
        if ($sumberDana) {
            $query->where('sumberdana', $sumberDana);
        }

        // Ambil hasil query
        $requests = $query->get();

        // Set header untuk file CSV
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=requests.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];

        // Buat file CSV menggunakan output buffering
        $callback = function () use ($requests) {
            $handle = fopen('php://output', 'w');
            
            // Menulis heading kolom
            fputcsv($handle, [
                'ID', 
                'Kategori', 
                'Kode Material', 
                'Nama Asset', 
                'Nama Sparepart',  // Kolom nama sparepart
                'Warehouse ID', 
                'Quantity', 
                'Keterangan', 
                'Status', 
                'No PP', 
                'No PO', 
                'Vendor', 
                'Sumber Dana', 
                'Harga', 
                'Unit Price'
            ]);

            // Menulis data request
            foreach ($requests as $request) {
                // Ambil nama sparepart jika kategori adalah sparepart
                $namaSparepart = $request->kategori === 'sparepart' ? $request->sparepart->nama_sparepart : null;

                fputcsv($handle, [
                    $request->id,
                    $request->kategori,
                    $request->kode_material,
                    $request->nama_asset,
                    $namaSparepart,  // Tampilkan nama sparepart jika ada
                    $request->warehouse_id,
                    $request->quantity,
                    $request->keterangan,
                    $request->status,
                    $request->no_pp,
                    $request->no_po,
                    $request->vendor,
                    $request->sumberdana,
                    $request->price,
                    $request->unit_price
                ]);
            }

            fclose($handle);
        };

        // Mengirim file CSV ke browser
        return Response::stream($callback, 200, $headers);
    }

    public function updateStatus(HttpRequest $request, $id)
{
    // Cari data request berdasarkan ID
    $requestItem = Requests::findOrFail($id);
    
    // Validasi jika request tidak ditemukan (opsional, karena findOrFail sudah handle)
    if (!$requestItem) {
        return redirect()->back()->with('error', 'Data request tidak ditemukan.');
    }

    // Update nomor PP jika ada
    if ($request->has('no_pp') && $request->input('no_pp') !== $requestItem->no_pp) {
        $requestItem->no_pp = $request->no_pp;
        $requestItem->status = 'pp';  // Set status menjadi 'pp'
        $requestItem->save();
    }

    // Jika form PO dikirim, update data PO, net price (price), dan unit price
    if ($request->has('no_po')) {
        $requestItem->status = 'po';
        $requestItem->no_po = $request->no_po;
        $requestItem->vendor = $request->vendor;

        // Menyimpan sumberdana (Capex / Opex)
        if ($request->has('sumberdana')) {
            $requestItem->sumberdana = $request->input('sumberdana');
        }

        // Validasi bahwa `price` (net price) ada dan `quantity` lebih dari 0
        if ($request->has('price') && $requestItem->quantity > 0) {
            $netPrice = $request->input('price');
            $quantity = $requestItem->quantity;
            $unitPrice = $netPrice / $quantity;
        
            Log::info("Net Price: $netPrice, Quantity: $quantity, Unit Price: $unitPrice");
        
            $requestItem->price = $netPrice;
            $requestItem->unit_price = $unitPrice;
        }
        
        // Simpan data lainnya
        $requestItem->save();
    }

    return redirect()->back()->with('success', 'Request updated successfully');
}

    
    
    


    
    public function approve($id)
    {
        $request = Requests::findOrFail($id);

    // Check if the request is already approved
    if ($request->status !== 'approved') {
        // Update status to 'approved'
        $request->status = 'approved';
        $request->save();  // Save status change


        
        // Create a GR (Goods Receipt) number
        $grNumber = 'GR-' . str_pad(Receive::count() + 1, 6, '0', STR_PAD_LEFT);

        try {
            // Insert data into 'receive' table
            $receive = Receive::create([
                'request_id' => $request->id,
                'warehouse_id' => $request->warehouse_id,
                'gr_number' => $grNumber,
                'received_quantity' => 0, // Default to 0, you can adjust this later
                'status' => 'pending', // Set the status to 'pending' for now
            ]);

            // Log that the data was successfully inserted into the receive table
            Log::info('Data berhasil dimasukkan ke tabel receive', ['receive_id' => $receive->id]);

            // Update the stock in the warehouse
            $warehouse = Warehouse::find($request->warehouse_id);
            if ($warehouse) {
                $warehouse->stock += $request->quantity;
                $warehouse->save();  // Save the updated stock
            }

            // Redirect with success message
            return redirect()->route('request.index')->with('success', 'Request berhasil disetujui dan data diterima!');
        } catch (\Exception $e) {
            // Log the error and return an error message
            Log::error('Error saat menyimpan data ke tabel receive: ' . $e->getMessage());
            return redirect()->route('request.index')->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    } else {
        // If the request is already approved
        return redirect()->route('request.index')->with('success', 'Request sudah disetujui!');
    }
    }


    

    

    


}
