<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index()
    {
        $repairs = Repair::with('user', 'payment')->get();
        return response()->json($repairs);
    }

    public function store(Request $request)
    {
        $repair = Repair::create($request->all());
        return response()->json($repair);
    }

    public function update(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);
        $repair->update($request->all());
        return response()->json($repair);
    }

    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();
        return response()->json(['message' => 'Repair deleted successfully']);
    }
}<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index()
    {
        $repairs = Repair::with('user', 'payment')->get();
        return response()->json($repairs);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_type' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'damage_description' => 'required',
        ]);

        $data['status'] = 'waiting';
        $repair = Repair::create($data);
        return response()->json($repair);
    }

    public function update(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $repair->update($request->only([
            'status',
            'diagnosis',
        ]));

        return response()->json($repair);
    }

    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return response()->json(['message' => 'Repair deleted successfully']);
    }
}