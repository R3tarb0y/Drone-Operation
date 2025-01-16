<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Asset;
use App\Models\Sparepart;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Ambil data laporan dengan relasi asset (tanpa memuat spareparts langsung)
        $reports = Report::with('asset')->get();
    
        // Loop untuk menambahkan spareparts dari JSON yang ada di kolom `spareparts`
        foreach ($reports as $report) {
            $sparepartsDetails = collect(json_decode($report->spareparts, true)); // Tambahkan parameter true untuk array
            $report->spareparts_details = $sparepartsDetails;
        }
    
        return view('reports.index', compact('reports'));
    }
    

    public function create()
    {
        // Mengambil data spareparts dari database
        $spareparts = Sparepart::all();
        
        // Mengambil data assets (drone) jika diperlukan
        $assets = Asset::all();
        
        // Mengirimkan data ke view
        return view('reports.create', compact('spareparts', 'assets'));
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id_asset',
            'pilot_name' => 'required|string',
            'chronology' => 'required|string',
            'spareparts' => 'required|array',
            'spareparts.*.id' => 'required|exists:spareparts,id_sparepart',
            'spareparts.*.quantity' => 'required|integer|min:1',
            'spareparts.*.damage_part' => 'required|string',
        ]);

        $sparepartsDetails = collect($validated['spareparts'])->map(function ($sparepart) {
            $sparepartModel = Sparepart::find($sparepart['id']);
            return [
                'id' => $sparepartModel->id_sparepart,
                'nama_barang' => $sparepartModel->nama_sparepart,
                'quantity' => $sparepart['quantity'],
                'damage_part' => $sparepart['damage_part'],
            ];
        });

        $report = new Report();
        $report->asset_id = $validated['asset_id'];
        $report->pilot_name = $validated['pilot_name'];
        $report->chronology = $validated['chronology'];
        $report->spareparts = $sparepartsDetails->toJson(); // Simpan detail spareparts sebagai JSON
        $report->damages = $sparepartsDetails->toJson();    // Simpan juga untuk damages (opsional)
        $report->save();

        return redirect()->route('reports.index')->with('success', 'Report created successfully');
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Query dengan filter tanggal
        $query = Report::with('asset');
    
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        // Ambil data laporan
        $reports = $query->get();
    
        $filename = "reports_" . date('YmdHis') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
    
        $columns = [
            'ID',
            'Asset Name',
            'Pilot Name',
            'Chronology',
            'Spareparts',
            'Created At',
        ];
    
        $callback = function () use ($reports, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
    
            foreach ($reports as $report) {
                // Decode spareparts JSON to a readable string
                $sparepartsDetails = collect(json_decode($report->spareparts, true))->map(function ($sparepart) {
                    return "{$sparepart['nama_barang']} (Qty: {$sparepart['quantity']}, Damage: {$sparepart['damage_part']})";
                })->implode('; ');
    
                fputcsv($file, [
                    $report->id,
                    $report->asset->nama_barang ?? 'N/A',
                    $report->pilot_name,
                    $report->chronology,
                    $sparepartsDetails,
                    $report->created_at->format('Y-m-d'),
                ]);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    

    
    

    
    
}

