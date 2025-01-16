<?php

namespace App\Http\Controllers;

use App\Models\Estimation;
use App\Models\Asset;
use App\Models\Report;
use App\Models\Sparepart;
use App\Models\Requests;
use App\Models\Realisasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request as HttpRequest;

class EstimationController extends Controller
{
    // Method untuk index
    public function index()
    {
        $estimations = Estimation::with(['asset', 'report'])->get();
        $spareparts = Sparepart::all();
    
        // Decode spareparts JSON untuk setiap estimation
        $estimations->each(function ($estimation) {
            $estimation->decoded_spareparts = json_decode($estimation->spareparts, true);
        });
    
        // Ambil data spareparts untuk tampilan
        $sparepartsData = Sparepart::all()->map(function ($sparepart) {
            $requests = Requests::where('sparepart_id', $sparepart->id_sparepart)->get();
            $unitPrice = $requests->first()->unit_price ?? 0; // Default ke 0 jika tidak ada harga
    
            return [
                'id_sparepart' => $sparepart->id_sparepart,
                'nama_sparepart' => $sparepart->nama_sparepart,
                'harga_db' => $sparepart->harga,
                'unit_price' => $unitPrice, // Sertakan unit price dari Requests
            ];
        });
    
        return view('estimations.index', compact('estimations', 'spareparts', 'sparepartsData'));
    }

    // Method untuk membuat estimation baru
    public function create()
    {
        $reports = Report::with('asset')->get();
        $assets = Asset::all(); // Semua asset jika tidak ada report
    
        // Ambil semua spareparts dengan harga satuan dari Requests
        $sparepartsData = Sparepart::all()->map(function ($sparepart) {
            $requests = Requests::where('sparepart_id', $sparepart->id_sparepart)->get();
            $unitPrice = $requests->first()->unit_price ?? 0;

            return [
                'id_sparepart' => $sparepart->id_sparepart,
                'nama_sparepart' => $sparepart->nama_sparepart,
                'harga_db' => $sparepart->harga,
                'unit_price' => $unitPrice,
            ];
        });

        return view('estimations.create', compact('reports', 'assets', 'sparepartsData'));
    }

    // Method untuk menyimpan estimation baru
    public function store(HttpRequest $request)
    {
        // Decode JSON menjadi array PHP
        $spareparts = json_decode($request->spareparts, true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['spareparts' => 'Invalid spareparts data format.']);
        }
    
        $request->merge(['spareparts' => $spareparts]);
    
        // Validasi request
        $validated = $request->validate([
            'has_report' => 'required|in:0,1',
            'report_id' => 'required_if:has_report,1|nullable|exists:reports,id',
            'asset_id' => 'required_if:has_report,0|nullable|exists:assets,id_asset',
            'spareparts' => 'required|array',
            'spareparts.*.id' => 'required|exists:spareparts,id_sparepart', 
            'spareparts.*.quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);
    
        $sparepartsData = collect($validated['spareparts'])->map(function ($sparepart) {
            $sparepartModel = Sparepart::findOrFail($sparepart['id']);
            $requests = Requests::where('sparepart_id', $sparepart['id'])->first();
            $unitPrice = $requests ? $requests->unit_price : 0;
    
            return [
                'id_sparepart' => $sparepart['id'],
                'nama_barang' => $sparepartModel->nama_sparepart,
                'quantity' => $sparepart['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $unitPrice * $sparepart['quantity'],
            ];
        });
    
        $totalCost = $sparepartsData->sum('total_price'); // Menghitung total cost dengan benar
    
        Estimation::create([
            'asset_id' => $validated['asset_id'],
            'report_id' => $validated['has_report'] == '1' ? $validated['report_id'] : null,
            'spareparts' => $sparepartsData->toJson(),
            'total_cost' => $totalCost,
        ]);
    
        return redirect()->route('estimations.index')->with('success', 'Estimation created successfully.');
    }

    public function updateSpareparts(HttpRequest $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'spareparts' => 'required|array',
            'spareparts.*.sparepart_id' => 'required|exists:spareparts,id_sparepart',
            'spareparts.*.quantity' => 'required|integer|min:1',
            'total_cost' => 'required|numeric',
        ]);
    
        // Find the estimation record by its ID
        $estimation = Estimation::findOrFail($id);
    
        // Decode the existing spareparts data from JSON into an array
        $existingSpareparts = json_decode($estimation->spareparts, true);
    
        // Iterate over the updated spareparts from the request
        foreach ($validated['spareparts'] as $updatedSparepart) {
            $found = false;
    
            foreach ($existingSpareparts as &$sparepart) {
                // Check if the sparepart is an array and has the 'id_sparepart' key
                if (is_array($sparepart) && isset($sparepart['id_sparepart']) && $sparepart['id_sparepart'] == $updatedSparepart['sparepart_id']) {
                    // Update the sparepart
                    $sparepart['quantity'] = $updatedSparepart['quantity'];
                    $requests = Requests::where('sparepart_id', $updatedSparepart['sparepart_id'])->first();
                    $unitPrice = $requests ? $requests->unit_price : 0;
                    $sparepart['unit_price'] = $unitPrice;
                    $sparepart['total_price'] = $unitPrice * $sparepart['quantity'];
                    $sparepartModel = Sparepart::find($updatedSparepart['sparepart_id']);
                    $sparepart['nama_barang'] = $sparepartModel ? $sparepartModel->nama_sparepart : 'Unknown';
    
                    $found = true;
                    break;
                }
            }
    
            if (!$found) {
                // Add a new sparepart
                $requests = Requests::where('sparepart_id', $updatedSparepart['sparepart_id'])->first();
                $unitPrice = $requests ? $requests->unit_price : 0;
                $sparepartModel = Sparepart::find($updatedSparepart['sparepart_id']);
                $existingSpareparts[] = [
                    'id_sparepart' => $updatedSparepart['sparepart_id'],
                    'quantity' => $updatedSparepart['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * $updatedSparepart['quantity'],
                    'nama_barang' => $sparepartModel ? $sparepartModel->nama_sparepart : 'Unknown',
                ];
            }
        }
    
        // Calculate the total cost after updating or adding spareparts
        $totalCost = array_sum(array_column($existingSpareparts, 'total_price'));
    
        // Update the estimation record with the new spareparts JSON and total cost
        $estimation->update([
            'spareparts' => json_encode(array_values($existingSpareparts)),
            'total_cost' => $totalCost,
            'status' => 'update',  // Change status to 'update'
        ]);
    
        return redirect()->route('estimations.index')->with('success', 'Spareparts updated successfully.');
    }
    
    
    
    public function removeSparepart(HttpRequest $request, $estimationId)
    {
        // Validate sparepart_id is present and exists
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id_sparepart',
        ]);
        
    
        try {
            $estimation = Estimation::findOrFail($estimationId);
            $sparepartId = $request->input('sparepart_id');

            $estimation = Estimation::findOrFail($estimationId);
            $spareparts = json_decode($estimation->spareparts, true);
            
            // Remove the sparepart using the correct column name
            $spareparts = array_filter($spareparts, function ($sparepart) use ($sparepartId) {
                return $sparepart['id_sparepart'] != $sparepartId; // Adjust this to match the correct column
            });
            
            $estimation->spareparts = json_encode(array_values($spareparts));
            $estimation->save();
            
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Log the error to get more information
            Log::error('Error removing sparepart: ' . $e->getMessage());
            return response()->json(['error' => 'Error removing sparepart'], 500);
        }
    }
    
    public function approveEstimation($id)
    {
        // Find the estimation record by its ID
        $estimation = Estimation::findOrFail($id);
    
        // Fetch the correct asset_id from the Estimation
        $assetId = $estimation->asset_id;  // Ensure asset_id is correctly assigned from Estimation
    
        // Check if the asset exists
        $asset = Asset::find($assetId);
        if (!$asset) {
            return redirect()->route('estimations.index')->with('error', 'Invalid asset associated with the estimation.');
        }
    
        // Update the status to 'approved'
        $estimation->status = 'approved';
        $estimation->save();
    
        // Prepare data for the 'realisasi' table
        $sparepartsData = $estimation->spareparts; // Assuming spareparts are stored as JSON in 'spareparts' column
        $totalCost = $estimation->total_cost;
    
        // Insert data into the realisasi table without setting payment_type and is_approved
        $realisasi = new Realisasi();
        $realisasi->asset_id = $assetId; // Use the correct asset ID
        $realisasi->estimation_id = $estimation->id;
        $realisasi->spareparts = $sparepartsData; // Insert the spareparts JSON data
        $realisasi->total_cost = $totalCost;
        $realisasi->save();
    
        return redirect()->route('estimations.index')->with('success', 'Estimation approved and data sent to realisasi successfully.');
    }
    

    

    
 
    
    public function getDataByReport(HttpRequest $request)
    {
        $request->validate([
            'report_id' => 'nullable|exists:reports,id',
        ]);
    
        if (!$request->report_id) {
            return response()->json([
                'asset' => null,
                'spareparts' => [],
            ]);
        }
    
        // Ambil laporan beserta relasi asset
        $report = Report::with('asset')->findOrFail($request->report_id);
    
        // Periksa apakah laporan memiliki asset
        if ($report->asset) {
            $assetData = [
                'id' => $report->asset->id_asset, 
                'nama_asset' => $report->asset->nama_asset
            ];
        } else {
            $assetData = null;
        }
    
        // Proses spareparts
        $spareparts = collect(json_decode($report->spareparts, true))->map(function ($sparepart) {
            $sparepartModel = Sparepart::find($sparepart['id']);
            $requests = Requests::where('sparepart_id', $sparepart['id'])->first();
            $unitPrice = $requests ? $requests->unit_price : 0; // Ambil harga satuan sparepart
    
            return [
                'id' => $sparepart['id'],
                'nama_barang' => $sparepartModel->nama_sparepart,
                'quantity' => $sparepart['quantity'],
                'unit_price' => $unitPrice, 
            ];
        });
    
        // Kembalikan asset dan spareparts
        return response()->json([
            'asset' => $assetData,
            'spareparts' => $spareparts,
        ]);
    }

    // Method untuk mendapatkan harga dan quantity sparepart
    public function getPriceQuantity(HttpRequest $request)
    {
        $sparepartId = $request->get('sparepart_id');
        $sparepart = Sparepart::find($sparepartId);

        if (!$sparepart) {
            return response()->json(['error' => 'Sparepart not found'], 404);
        }

        $requests = Requests::where('sparepart_id', $sparepartId)->first();
        $unitPrice = $requests ? $requests->unit_price : 0;

        return response()->json([
            'unit_price' => $unitPrice,
            'sparepart' => $sparepart,
        ]);
    }

    public function exportCsv(HttpRequest $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Query dengan filter tanggal
        $query = Estimation::query();
    
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        // Ambil data estimasi dengan relasi yang diperlukan
        $estimations = $query->with(['asset', 'report'])->get();
    
        $filename = "estimations_" . date('YmdHis') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
    
        // Header kolom untuk CSV
        $columns = [
            'ID',
            'Asset Name',
            'Total Cost',
            'Spareparts',
            'Status',
            'Created At',
        ];
    
        $callback = function () use ($estimations, $columns) {
            $file = fopen('php://output', 'w');
    
            // Tulis header kolom
            fputcsv($file, $columns);
    
            foreach ($estimations as $estimation) {
                // Decode spareparts JSON menjadi string yang bisa dibaca
                $sparepartsDetails = collect(json_decode($estimation->spareparts, true))->map(function ($sparepart) {
                    return "{$sparepart['nama_barang']} (Qty: {$sparepart['quantity']}, Price: {$sparepart['unit_price']})";
                })->implode('; ');
    
                // Tulis baris data ke CSV
                fputcsv($file, [
                    $estimation->id,
                    $estimation->asset->nama_barang ?? 'N/A',
                    $estimation->total_cost,
                    $sparepartsDetails,
                    $estimation->status ?? 'N/A',
                    $estimation->created_at->format('Y-m-d'),
                ]);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    

   


}

