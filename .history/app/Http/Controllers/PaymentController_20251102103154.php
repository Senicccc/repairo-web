// Di RepairController, pastikan status flow-nya konsisten:
public function update(Request $request, $id)
{
$repair = Repair::findOrFail($id);
$user = Auth::user();

if ($user->role === 'technician') {
$data = $request->validate([
'diagnosis' => 'nullable|string',
'cost' => 'nullable|numeric|min:0',
'status' => 'required|in:pending,in_progress,diagnosed,waiting_parts,finished,cancelled', // technician bisa set
finished
]);

$repair->update([
'diagnosis' => $data['diagnosis'] ?? $repair->diagnosis,
'cost' => $data['cost'] ?? $repair->cost,
'status' => $data['status'],
'technician_id' => $user->id,
'technician' => $user->name,
]);

return response()->json(['success' => true, 'message' => 'Job updated successfully.']);
}

if ($user->role === 'cashier') {
$data = $request->validate([
'status' => 'required|string|in:finished,paid,cancelled', // cashier bisa set paid
]);

$repair->update($data);

// Jika status diubah jadi paid, buat payment otomatis
if ($data['status'] === 'paid' && $repair->cost > 0) {
Payment::create([
'repair_id' => $repair->id,
'amount' => $repair->cost,
'payment_method' => 'cash', // default
'status' => 'paid',
]);

// Tambah poin loyalitas
if ($repair->user_id) {
$loyalty = new LoyaltyController();
$loyalty->addPoints($repair->user_id, $repair->cost);
}
}

return redirect()->back()->with('success', 'Repair status updated.');
}

abort(403, 'Unauthorized');
}