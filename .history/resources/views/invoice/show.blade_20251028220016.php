@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 border rounded text-[13px] font-mono" id="invoiceArea">
    <h2 class="text-center text-xl font-bold mb-1">REPAIRO SERVICE INVOICE</h2>
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

    {{-- Spareparts Section --}}
    <div class="border-t border-b py-3 mb-4">
        <h3 class="font-semibold mb-2 text-sm border-b pb-1">Spareparts Used</h3>
        @if($repair->repairSpareparts && $repair->repairSpareparts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 text-left">No</th>
                            <th class="border px-2 py-1 text-left">Sparepart Name</th>
                            <th class="border px-2 py-1 text-left">Category</th>
                            <th class="border px-2 py-1 text-center">Qty</th>
                            <th class="border px-2 py-1 text-right">Price</th>
                            <th class="border px-2 py-1 text-right">Subtotal</th>
                            <th class="border px-2 py-1 text-left">Source</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSparepartCost = 0;
                        @endphp
                        @foreach($repair->repairSpareparts as $index => $sparepart)
                            @php
                                $subtotal = $sparepart->price * $sparepart->quantity;
                                $totalSparepartCost += $subtotal;
                            @endphp
                            <tr>
                                <td class="border px-2 py-1">{{ $index + 1 }}</td>
                                <td class="border px-2 py-1">
                                    {{ $sparepart->name }}
                                    @if($sparepart->sparepart_id)
                                        <br><span class="text-xs text-gray-600">(ID: {{ $sparepart->sparepart_id }})</span>
                                    @endif
                                </td>
                                <td class="border px-2 py-1">{{ $sparepart->category ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center">{{ $sparepart->quantity }}</td>
                                <td class="border px-2 py-1 text-right">Rp{{ number_format($sparepart->price, 0, ',', '.') }}</td>
                                <td class="border px-2 py-1 text-right">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td class="border px-2 py-1">
                                    @if($sparepart->source === 'in_store')
                                        <span class="bg-green-100 text-green-800 px-1 rounded text-xs">Internal</span>
                                    @elseif($sparepart->source === 'customer_owned')
                                        <span class="bg-blue-100 text-blue-800 px-1 rounded text-xs">Customer</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-800 px-1 rounded text-xs">External</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        {{-- Total Row --}}
                        <tr class="bg-gray-50 font-semibold">
                            <td colspan="5" class="border px-2 py-1 text-right">Total Spareparts Cost:</td>
                            <td class="border px-2 py-1 text-right">Rp{{ number_format($totalSparepartCost, 0, ',', '.') }}</td>
                            <td class="border px-2 py-1"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 italic">No spareparts used for this repair.</p>
        @endif
    </div>

    {{-- Cost Summary --}}
    <div class="border-t border-b py-3 mb-4">
        <h3 class="font-semibold mb-2 text-sm border-b pb-1">Cost Summary</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p><strong>Spareparts Total:</strong> Rp{{ number_format($totalSparepartCost ?? 0, 0, ',', '.') }}</p>
                <p><strong>Service Cost:</strong> 
                    @php
                        $serviceCost = $repair->cost ? ($repair->cost - ($totalSparepartCost ?? 0)) : 0;
                    @endphp
                    Rp{{ number_format($serviceCost, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-gray-100 p-2 rounded">
                <p class="font-bold"><strong>Grand Total:</strong> Rp{{ number_format($repair->cost ?? 0, 0, ',', '.') }}</p>
            </div>
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
    const printWindow = window.open('', '', 'width=1000,height=600');
    printWindow.document.write('<html><head><title>Repairo Invoice</title>');
    printWindow.document.write('<style>');
    printWindow.document.write(`
        @page {
            size: landscape;
            margin: 0;
        }
        body {
            font-family: monospace;
            font-size: 13px;
            margin: 0;
            padding: 10px 25px;
            width: 100%;
            color: #000;
        }
        h2 {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 10px 0;
            margin-bottom: 12px;
        }
        h3 {
            margin-bottom: 4px;
            font-size: 12px;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }
        p { margin: 2px 0; }
        .text-center { text-align: center; }
        .text-xs { font-size: 11px; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 8px;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .bg-gray-100 { background-color: #f3f4f6; }
        .bg-green-100 { background-color: #dcfce7; }
        .bg-blue-100 { background-color: #dbeafe; }
        .bg-yellow-100 { background-color: #fef3c7; }
        @media print {
            html, body { width: 297mm; height: auto; }
            button { display: none; }
        }
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