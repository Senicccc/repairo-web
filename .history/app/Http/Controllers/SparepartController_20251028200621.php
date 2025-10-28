<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
     public function index()
    {
        $spareparts = Sparepart::all();
        return view('admin.spareparts.index', compact('spareparts'));
    }

    public function create()
    {
        return view('admin.spareparts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Original,OEM,Aftermarket,Replica',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string'
        ]);

        Sparepart::create($data);

        return redirect()->route('admin.spareparts.index')->with('success', 'Sparepart added successfully');
    }

    public function edit(Sparepart $sparepart)
    {
        return view('admin.spareparts.edit', compact('sparepart'));
    }

    public function update(Request $request, Sparepart $sparepart)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Original,OEM,Aftermarket,Replica',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string'
        ]);

        $sparepart->update($data);

        return redirect()->route('admin.spareparts.index')->with('success', 'Sparepart updated successfully');
    }

    public function destroy(Sparepart $sparepart)
    {
        $sparepart->delete();
        return redirect()->route('admin.spareparts.index')->with('success', 'Sparepart deleted successfully');
    }

    public function search(Request $request)
{
    $query = $request->get('q');
    $results = Sparepart::where('name', 'like', "%{$query}%")
        ->orWhere('category', 'like', "%{$query}%")
        ->limit(10)
        ->get(['id', 'name', 'category', 'price']);
    return response()->json($results);
}

}