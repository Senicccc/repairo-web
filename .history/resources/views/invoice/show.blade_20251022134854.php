@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-4 border rounded text-sm font-mono" id="invoiceArea">
    <h2 class="text-center text-lg font-semibold mb-2">🧾 Repair Invoice</h2>
    <p class="text-center text-xs mb-4">Tracking ID: {{ $repair->tracking_id ?? '-' }}</p>

    <div class="border-t border-b py-2 mb-2">
        <h3 class="font-semibold mb-1">Customer Info</h3>
        <p><strong>Name:</strong> {{ $repair->customer_name ?? '-' }}</p>
        <p><strong>Phone:</strong> {{ $repair->phone ?? '-' }}</p>
        <p><strong>IMEI:</strong> {{ $repair->imei ?? '-' }}</p>
    </div>

    <div class="border-b py-2 mb-2">
        <h3 class="font-semibold mb-1">Device Info</h3>
        <p><strong>Brand:</strong> {{ $repair->phone_brand }}</p>
        <p><strong>Model:</strong> {{ $repair->phone_model }}</p>
        <p><strong>Complaint:</strong> {{ $repair->complaint }}</p>
        <p><strong>Diagnosis:</strong> {{ $repair->diagnosis ?? '-' }}</p>
        <p><strong>Sparepart:</strong> {{ $repair->sparepart ?? '-' }}</p>
        <p><strong>Technician:</strong> {{ $repair->technician ?? '-' }}</p>
        <p><strong>Technician ID:</strong> {{ $repair->technician_id ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($repair->status) }}</p>
        <p><strong>Cost:</strong> Rp{{ number_format($repair->cost ?? 0, 0, ',', '.') }}</p>
        <p><strong>Created:</strong> {{ $repair->created_at ? $repair->created_at->format('d M Y H:i') : '-' }}</p>
        <p><strong>Updated:</strong> {{ $repair->updated_at ? $repair->updated_at->format('d M Y H:i') : '-' }}</p>
    </div>

    <div class="border-b py-2 mb-2">
        <h3 class="font-semibold mb-1">Payment Info</h3>
        @if($repair->payment)
            <p><strong>Invoice No:</strong> {{ $repair->payment->invoice_number }}</p>
            <p><strong>Repair ID:</strong> {{ $repair->payment->repair_id }}</p>
            <p><strong>Amount:</strong> Rp{{ number_format($repair->payment->amount, 0, ',', '.') }}</p>
            <p><strong>Method:</strong> {{ ucfirst($repair->payment->payment_method) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($repair->payment->status) }}</p>
            <p><strong>Payment Date:</strong> {{ $repair->payment->payment_date ? $repair->payment->payment_date->format('d M Y H:i') : '-' }}</p>
            <p><strong>Created:</strong> {{ $repair->payment->created_at ? $repair->payment->created_at->format('d M Y H:i') : '-' }}</p>
            <p><strong>Updated:</strong> {{ $repair->payment->updated_at ? $repair->payment->updated_at->format('d M Y H:i') : '-' }}</p>
        @else
            <p>No payment record.</p>
        @endif
    </div>

    <div class="text-center text-xs mt-2">
        <p>Thank you for trusting <strong>Repairo</strong>!</p>
        <p>Generated at {{ now()->format('d M Y H:i') }}</p>
    </div>
</div>

<div class="mt-4 text-center no-print">
    <button onclick="printInvoice()" class="px-3 py-1 bg-gray-700 text-white rounded">Print</button>
</div>

<style>
@media print {
    .no-print { display: none; }
    body { font-family: monospace; font-size: 11px; width: 58mm; margin: 0; }
    #invoiceArea { border: none; }
    .border-t, .border-b { border-top: 1px dashed #000; border-bottom: 1px dashed #000; }
}
</style>

<script>
function printInvoice() {
    window.print();
}
</script>
@endsection
