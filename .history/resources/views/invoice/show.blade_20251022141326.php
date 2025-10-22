@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 border rounded text-sm font-mono" id="invoiceArea">
    <h2 class="text-center text-xl font-semibold mb-1"> Repairo Invoice</h2>
    <p class="text-center text-xs mb-4">Tracking ID: {{ $repair->tracking_id ?? '-' }}</p>

    <div class="grid grid-cols-3 gap-4 border-t border-b py-2 mb-3">
        <div>
            <h3 class="font-semibold mb-1 text-sm">Customer Info</h3>
            <p><strong>Name:</strong> {{ $repair->customer_name ?? '-' }}</p>
            <p><strong>Phone:</strong> {{ $repair->phone ?? '-' }}</p>
            <p><strong>IMEI:</strong> {{ $repair->imei ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($repair->status) }}</p>
        </div>

        <div>
            <h3 class="font-semibold mb-1 text-sm">Device Info</h3>
            <p><strong>Brand:</strong> {{ $repair->phone_brand }}</p>
            <p><strong>Model:</strong> {{ $repair->phone_model }}</p>
            <p><strong>Complaint:</strong> {{ $repair->complaint }}</p>
            <p><strong>Diagnosis:</strong> {{ $repair->diagnosis ?? '-' }}</p>
            <p><strong>Sparepart:</strong> {{ $repair->sparepart ?? '-' }}</p>
            <p><strong>Technician:</strong> {{ $repair->technician ?? '-' }}</p>
        </div>

        <div>
            <h3 class="font-semibold mb-1 text-sm">Payment Info</h3>
            @if($repair->payment)
                <p><strong>Invoice No:</strong> {{ $repair->payment->invoice_number }}</p>
                <p><strong>Repair ID:</strong> {{ $repair->payment->repair_id }}</p>
                <p><strong>Amount:</strong> Rp{{ number_format($repair->payment->amount, 0, ',', '.') }}</p>
                <p><strong>Method:</strong> {{ ucfirst($repair->payment->payment_method) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($repair->payment->status) }}</p>
                <p><strong>Payment Date:</strong> {{ $repair->payment->payment_date ? $repair->payment->payment_date->format('d M Y H:i') : '-' }}</p>
            @else
                <p>No payment record.</p>
            @endif
        </div>
    </div>

    <div class="border-b pb-2 mb-2 text-xs">
        <p><strong>Created:</strong> {{ $repair->created_at ? $repair->created_at->format('d M Y H:i') : '-' }}</p>
        <p><strong>Updated:</strong> {{ $repair->updated_at ? $repair->updated_at->format('d M Y H:i') : '-' }}</p>
    </div>

    <div class="text-center text-xs">
        <p>Thank you for trusting <strong>Repairo</strong>!</p>
        <p>Generated at {{ now()->format('d M Y H:i') }}</p>
    </div>
</div>

<div class="mt-4 text-center">
    <button onclick="printInvoice()" class="px-4 py-2 bg-gray-700 text-white rounded">🖨️ Print</button>
</div>

<script>
function printInvoice() {
    const content = document.getElementById('invoiceArea').innerHTML;
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write('<html><head><title>Repair Invoice</title>');
    printWindow.document.write('<style>');
    printWindow.document.write(`
        body {
            font-family: monospace;
            font-size: 12px;
            margin: 10px;
            padding: 0;
            text-align: left;
        }
        h2 { text-align: center; font-size: 16px; margin-bottom: 4px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
        .border-t { border-top: 1px dashed #000; padding-top: 5px; }
        .border-b { border-bottom: 1px dashed #000; padding-bottom: 5px; }
        p { margin: 2px 0; }
        h3 { margin-bottom: 3px; font-size: 12px; border-bottom: 1px solid #000; }
        strong { font-weight: bold; }
        .text-center { text-align: center; }
        .text-xs { font-size: 10px; }
    `);
    printWindow.document.write('</style></head><body>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}
</script>
@endsection
