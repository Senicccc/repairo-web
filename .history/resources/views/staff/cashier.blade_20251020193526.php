@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Dashboard Kasir</h2>

    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Daftar Servis</h3>
        <button onclick="document.getElementById('addRepairModal').showModal()" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Tambah Servis
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full border border-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-3 py-2 border">Tracking ID</th>
                    <th class="px-3 py-2 border">Pelanggan</th>
                    <th class="px-3 py-2 border">Perangkat</th>
                    <th class="px-3 py-2 border">Kerusakan</th>
                    <th class="px-3 py-2 border">Status</th>
                    <th class="px-3 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($repairs as $repair)
                <tr class="text-center">
                    <td class="border px-3 py-2">{{ $repair->tracking_id }}</td>
                    <td class="border px-3 py-2">{{ $repair->user ? $repair->user->name : '-' }}</td>
                    <td class="border px-3 py-2">{{ $repair->device_type }} {{ $repair->brand }} {{ $repair->model }}</td>
                    <td class="border px-3 py-2">{{ $repair->damage_description }}</td>
                    <td class="border px-3 py-2">
                        <span class="px-2 py-1 rounded text-sm
                            {{ $repair->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($repair->status) }}
                        </span>
                    </td>
                    <td class="border px-3 py-2 space-x-2">
                        @if ($repair->status == 'completed' && !$repair->payment)
                            <button onclick="openPaymentModal({{ $repair->id }})"
                                class="bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700">
                                Bayar
                            </button>
                        @endif
                        @if ($repair->payment)
                            <a href="{{ route('invoice.show', $repair->id) }}" 
                               class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800">
                                Cetak Invoice
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Servis -->
<dialog id="addRepairModal" class="modal">
    <form method="POST" action="{{ route('repairs.store') }}" class="p-6 bg-white rounded shadow-md w-[480px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Tambah Servis Baru</h3>
        <input type="text" name="name" placeholder="Nama Pelanggan (opsional)" class="w-full border p-2 mb-3 rounded">
        <input type="text" name="phone" placeholder="Nomor Telepon" class="w-full border p-2 mb-3 rounded">
        <input type="text" name="device_type" placeholder="Tipe Perangkat" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="brand" placeholder="Merek" required class="w-full border p-2 mb-3 rounded">
        <input type="text" name="model" placeholder="Model" class="w-full border p-2 mb-3 rounded">
        <textarea name="damage_description" placeholder="Deskripsi Kerusakan" required class="w-full border p-2 mb-3 rounded"></textarea>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addRepairModal').close()" class="px-3 py-1 bg-gray-300 rounded">Batal</button>
            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Simpan</button>
        </div>
    </form>
</dialog>

<!-- Modal Pembayaran -->
<dialog id="addPaymentModal" class="modal">
    <form method="POST" action="{{ route('payments.store') }}" class="p-6 bg-white rounded shadow-md w-[400px] mx-auto">
        @csrf
        <h3 class="text-lg font-semibold mb-4">Pembayaran Servis</h3>
        <input type="hidden" name="repair_id" id="repair_id">
        <input type="number" name="amount" placeholder="Total Bayar" required class="w-full border p-2 mb-3 rounded">
        <select name="method" required class="w-full border p-2 mb-3 rounded">
            <option value="cash">Cash</option>
            <option value="transfer">Transfer Bank</option>
            <option value="ewallet">E-Wallet</option>
        </select>
        <select name="status" required class="w-full border p-2 mb-3 rounded">
            <option value="paid">Lunas</option>
            <option value="pending">Menunggu</option>
        </select>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('addPaymentModal').close()" class="px-3 py-1 bg-gray-300 rounded">Batal</button>
            <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded">Konfirmasi</button>
        </div>
    </form>
</dialog>

<script>
    function openPaymentModal(id) {
        document.getElementById('repair_id').value = id;
        document.getElementById('addPaymentModal').showModal();
    }
</script>
@endsection
