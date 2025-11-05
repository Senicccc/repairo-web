<section id="payments" class="tab-section p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Payments Management</h2>
    </div>
    
    <div class="mb-4">
        <input type="text" id="paymentSearch" placeholder="Search payments..." class="border border-gray-300 px-4 py-2 rounded-lg w-1/2">
    </div>
    
    {{-- PERBAIKAN: Tambahkan wrapper dengan overflow --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto"> 
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Repair ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="paymentsTableBody">
                    @foreach ($payments as $payment)
                    <tr data-id="{{ $payment->id }}" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $payment->repair->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                            {{ $payment->repair->tracking_id ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                            {{ str_replace('_', ' ', $payment->payment_method) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-badge
                                {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 amount-cell">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $payment->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-3">
                            <button type="button" class="text-blue-600 hover:text-blue-900 edit-payment" data-id="{{ $payment->id }}">Edit</button>
                            <button type="button" class="text-red-600 hover:text-red-900 delete-payment" data-id="{{ $payment->id }}">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div> {{-- Tutup div overflow-x-auto --}}
    </div>
    
    <div class="mt-4 text-sm text-gray-600">
        Total: {{ $payments->count() }} payments
    </div>
</section>