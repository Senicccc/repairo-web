@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 border rounded text-[13px] font-mono" id="invoiceArea">
    <h2 class="text-center text-2xl font-bold mb-1">REPAIRO SERVICE INVOICE</h2>
    <p class="text-center text-xs mb-4">Tracking ID: {{ $repair->tracking_id ?? '-' }}</p>

    <div class="grid grid-cols-3 gap-4 border-t border-b py-3 mb-4">
        <div>
            <h3 class="font-semibold mb-1 text-sm border-b pb-1">Customer Info</h3>
            <p><strong>Name:</strong> {{ $repair->customer_name ?? '-' }}</p>
            <p><strong>Phone:</strong> {{ $repair->phone ?? '-' }}</p>
            <p><strong>IMEI:</strong> {{ $repair->imei ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($repair->status) }}</p>
        </div>

        <div>
            <h3 class="font-semibold mb-1 text-sm border-b pb-1">Device Info</h3>
            <p><strong>Brand:</strong> {{ $repair->phone_brand }}</p>
            <p><strong>Model:</strong> {{ $repair->phone_model }}</p>
            <p><strong>Complaint:</strong> {{ $repair->complaint }}</p>
            <p><strong>Diagnosis:</strong> {{ $repair->diagnosis ?? '-' }}</p>
            <p><strong>Sparepart:</strong> {{ $repair->sparepart ?? '-' }}</p>
            <p><strong>Technician:</strong> {{ $repair->technician ?? '-' }}</p>
        </div>

        <div>
            <h3 class="font-semibold mb-1 text-sm border-b pb-1">Payment Info</h3>
            @if($repair->payment)
                <p><strong>Invoice No:</strong> {{ $repair->payment->invoice_number }}</p>
                <p><strong>Repair ID:</strong> {{ $repair->payment->repair_id }}</p>
                <p><strong>Amount:</strong> Rp{{ number_format($repair->payment->amount, 0, ',', '.') }}</p>
                <p><strong>Method:</strong> {{ ucfirst($repair->payment->payment_method) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($repair->payment->status) }}</p>
                <p><strong>Payment Date:</strong> 
                    {{ $repair->payment->payment_date ? \Carbon\Carbon::parse($repair->payment->payment_date)->format('d M Y H:i') : '-' }}
                </p>
            @else
                <p>No payment record.</p>
            @endif
        </div>
    </div>

    <div class="border-t border-b py-2 mb-3 text-xs">
        <p><strong>Created:</strong> {{ $repair->created_at ? $repair->created_at->format('d M Y H:i') : '-' }}</p>
        <p><strong>Updated:</strong> {{ $repair->updated_at ? $repair->updated_at->format('d M Y H:i') : '-' }}</p>
    </div>

    <div class="text-center text-xs">
        <p>Thank you for trusting <strong>Repairo</strong>!</p>
        <p>Generated at {{ now()->format('d M Y H:i') }}</p>
    </div>
</div>

<div class="mt-4 text-center">
    <button onclick="printInvoice()" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 transition">🖨️ Print</button>
</div>

<script>
function printInvoice() {
    const content = document.getElementById('invoiceArea').innerHTML;
    const printWindow = window.open('', '', 'width=900,height=700');
    printWindow.document.write('<html><head><title>Repairo Invoice</title>');
    printWindow.document.write('<style>');
    printWindow.document.write(`
        @page {
            size: A4;
            margin: 12mm;
        }
        @media print {
            @page {
                margin: 12mm;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        body {
            font-family: monospace;
            font-size: 14px;
            line-height: 1.4;
            margin: 20px;
            transform: scale(1.05);
            transform-origin: top left;
            color: #000;
        }
        h2 {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        p { margin: 2px 0; }
        h3 {
            margin-bottom: 4px;
            font-size: 12px;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 8px 0;
            margin-bottom: 12px;
        }
        .text-center { text-align: center; }
        .text-xs { font-size: 11px; }
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
