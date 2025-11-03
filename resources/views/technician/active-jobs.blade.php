<div class="mb-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Active Jobs</h3>
        <div class="text-sm text-gray-600">
            {{ $currentJobs->count() }} active • Max 6 jobs
        </div>
    </div>
    
    @if($currentJobs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($currentJobs as $job)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow"
                 data-active-id="{{ $job->id }}"
                 data-diagnosis="{{ $job->diagnosis ?? '' }}"
                 data-status="{{ $job->status ?? 'in_progress' }}"
                 data-cost="{{ $job->cost ?? '' }}">
                <div class="p-6">
                    <!-- Job Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="font-semibold text-lg text-gray-800">#{{ $job->tracking_id }}</h4>
                            <p class="text-sm text-gray-600">{{ $job->phone_brand }} {{ $job->phone_model }}</p>
                        </div>
                        <span class="{{ \App\Models\Repair::getStatusColor($job->status) }} px-3 py-1 rounded-full text-xs font-medium">
                            {{ \App\Models\Repair::getStatuses()[$job->status] ?? ucfirst($job->status) }}
                        </span>
                    </div>
                    
                    <!-- Customer Info -->
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700">{{ $job->customer_name ?? $job->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $job->phone ?? $job->user->phone }}</p>
                    </div>
                    
                    <!-- Complaint -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-700"><strong>Complaint:</strong> {{ Str::limit($job->complaint, 80) }}</p>
                        @if($job->diagnosis)
                        <p class="text-sm text-gray-700 mt-1"><strong>Diagnosis:</strong> {{ Str::limit($job->diagnosis, 60) }}</p>
                        @endif
                    </div>
                    
                    <!-- Action Button -->
                    <button onclick="openJobModal({{ $job->id }})" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        Update Job
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Active Jobs</h3>
            <p class="text-gray-500 mb-4">You don't have any active jobs at the moment.</p>
            <a href="{{ route('technician.dashboard', ['section' => 'available']) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Find Available Jobs
            </a>
        </div>
    @endif
</div>