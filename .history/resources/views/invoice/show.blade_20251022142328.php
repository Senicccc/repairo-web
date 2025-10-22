@extends('layouts.app')

@section('content')
<div class="flex justify-center">
    <div class="bg-white text-[12px] font-mono p-4 w-[80mm]" id="invoiceArea">
        <h2 class="text-center text-base font-bold mb-1">🧾 REPAIRO SERVICE INVOICE</h2>
        <p class="text-center text-xs mb-3">Tracking ID: {{ $repair->tracking_id ?? '-' }}</p>

        <table class="w-full text-xs mb-2 border-t border-b border-dashed border-black">
            <tr>
                <td colspan="2" class="font-semibold pt-1 pb-1">Customer Info</td>
            </tr>
            <tr><td>Name</td><td class="text-right">{{ $repair->customer_name ?? '-' }}</td></tr>
            <tr><td>Phone</td><td class="text-right">{{ $repair->phone ?? '-' }}</td></tr>
            <tr><td>IMEI</td><td class="text-right">{{ $repair->imei ?? '-' }}</td></tr>
            <tr><td>Status</td><td class="text-right">{{ ucfirst($repair->status) }}</td></tr>
        </table>

        <table class="w-full text-xs mb-2 border-b border-dashed border-black">
            <tr>
                <td colspan="2" class="font-semibold pt-1 pb-1">Device Info</td>
            </tr>
            <tr><td>Brand</td><td class="text-right">{{ $repair->phone_brand }}</td></tr>
            <tr><td>Model</td><td class="text-right">{{ $repair->phone_model }}</td></tr>
            <tr><td>Complaint</td><td class="text-right">{{ $repair->complaint }}</td></tr>
            <tr><td>Diagnosis</td><td class="text-right">{{ $repair->diagnosis ?? '-' }}</td></tr>
            <tr><td>Sparepart</td><td class="text-right">{{ $repair->sparepart ?? '-' }}</td></tr>
            <tr><td>Technician</td><td class="text-right">{{ $repair->technician ?? '-' }}</td></tr>
            <tr><td>Cost</td><td class="text-right">Rp{{ number_format($repair->cost ?? 0, 0, ',', '.') }}</td></tr>
        </table>

        <table class="w-full text-xs mb-2 border-b border-dashed border-black">
            <tr>
                <td colspan="2" class="font-semibold pt-1 pb-1">Payment Info</td>
            </tr>
            @if($repair->payment)
                <tr><td>Invoice No</td><td class="text-right">{{ $repair->payment->invoice_number }}</td></tr>
                <tr><td>Amount</td><td class="text-right">Rp{{ number_format($repair->payment->amount, 0, ',', '.') }}</td></tr>
                <tr><td>Method</td><td class="text-right">{{ ucfirst($repair->payment->payment_method) }}</td></tr>
                <tr><td>Status</td><td class="text-right">{{ ucfirst($repair->payment->status) }}</td></tr>
                <tr><td>Payment Date</td>
                    <td class="text-right">
                        {{ $repair->payment->payment_date ? \Carbon\Carbon::parse($repair->payment->payment_date)->format('d M Y H:i') : '-' }}
                    </td>
                </tr>
            @else
                <tr><td colspan="2" class="text-center">No payment record.</td></tr>
            @endif
        </table>

        <div class="text-center text-xs mt-3">
            <p>Thank you for trusting <strong>Repairo</strong>!</p>
            <p>Generated at {{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>
</div>

<div class="mt-4 text-center">
    <button onclick="printInvoice()" class="px-4 py-2 bg-gray-700 text-white rounded">🖨️ Print</button>
</div>

<script>
function printInvoice() {
    const content = document.getElementById('invoiceArea').outerHTML;
    const printWindow = window.open('', '', 'width=400,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Invoice</title>
            <style>
                @page {
                    size: 80mm auto;
                    margin: 0;
                }
                body {
                    margin: 0;
                    font-family: monospace;
                    font-size: 12px;
                    text-align: left;
                    width: 80mm;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                td {
                    padding: 1px 0;
                }
                .border-b { border-bottom: 1px dashed #000; }
                .border-t { border-top: 1px dashed #000; }
                h2 { text-align: center; font-size: 14px; margin-bottom: 2px; }
                p { margin: 1px 0; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .font-semibold { font-weight: bold; }
            </style>
        </head>
        <body onload="window.print(); window.close();">
            ${content}
        </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
@endsection
