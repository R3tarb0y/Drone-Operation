<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request as HttpRequest;

class BudgetController extends Controller
{
    public function index()
    {
        // Menampilkan semua data budget
        $budgets = Budget::all();
        return view('budget.index', compact('budgets'));
    }

    public function create()
    {
        // Menampilkan form untuk membuat budget baru
        return view('budget.create');
    }

    public function store(HttpRequest $request)
    {
        // Validasi input
        $validated = $request->validate([
            'tahun' => 'required|integer',
            'jenis_budget' => 'required|string|in:Capex,Opex',
            'total_budget' => 'required|integer|min:1',
        ]);

        // Menyimpan data budget baru
        Budget::create($validated);

        return redirect()->route('budget.index')->with('success', 'Anggaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Menampilkan form untuk mengedit data budget
        $budget = Budget::findOrFail($id);
        return view('budget.edit', compact('budget'));
    }

    public function update(HttpRequest $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'tahun' => 'required|integer',
            'jenis_budget' => 'required|string|in:Capex,Opex',
            'total_budget' => 'required|integer|min:1',
        ]);

        // Update data budget
        $budget = Budget::findOrFail($id);
        $budget->update($validated);

        return redirect()->route('budget.index')->with('success', 'Anggaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Menghapus data budget
        $budget = Budget::findOrFail($id);
        $budget->delete();

        return redirect()->route('budget.index')->with('success', 'Anggaran berhasil dihapus!');
    }
}

