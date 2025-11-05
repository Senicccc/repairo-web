<section id="payments" class="tab-section">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Payments Management</h2>
        <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 flex items-center">
            <span class="mr-2">+</span> Add Payment
        </button>
    </div>
    
    <div class="mb-4">
        <input type="text" placeholder="Search payments..." class="border border-gray-300 px-4 py-2 rounded-lg w-1/2 search" data-table="payments">
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
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
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($payments as $payment)
                <tr class="hover:bg-gray-50">
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
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                               ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $payment->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 text-sm text-gray-600">
        Total: {{ $payments->count() }} payments
    </div>
</section>