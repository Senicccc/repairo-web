<section id="payments" class="tab-section p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Payments Management</h2>
    </div>
    
    <div class="mb-4">
        <input type="text" id="paymentSearch" placeholder="Search payments..." class="border border-gray-300 px-4 py-2 rounded-lg w-1/2">
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
    </div>
    
    <div class="mt-4 text-sm text-gray-600">
        Total: {{ $payments->count() }} payments
    </div>
</section>

{{-- Payment Edit Modal --}}
<div id="editPaymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
        <button type="button" id="closePaymentModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <h3 class="text-lg font-semibold mb-4">Edit Payment</h3>
        <form id="editPaymentForm">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="editPaymentId" name="payment_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="editPaymentStatus" name="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <select id="editPaymentMethod" name="payment_method" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="cash">Cash</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="e_wallet">E-Wallet</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <input type="number" id="editPaymentAmount" name="amount" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter amount in Rupiah">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelPaymentEdit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Save Changes</button>
            </div>
        </form>
    </div>
</div>