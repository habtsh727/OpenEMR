{{-- resources/views/livewire/referral/submit-result.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Card Container -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 overflow-hidden">
            
            <!-- Header -->
            <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-white dark:from-gray-900 dark:to-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit Referral Result
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2 flex items-center gap-4">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $referral->facility_name }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $referral->encounter->patient->first_name ?? 'N/A' }}</span>
                            </span>
                        </p>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                            Referral ID: #{{ $referral->id }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form wire:submit.prevent="submit" class="p-8 space-y-8">
                
                <!-- Success/Error Messages -->
                @if (session()->has('success'))
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-red-800 dark:text-red-300 font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Referral Summary Card -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800 rounded-xl p-6 border border-blue-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200">Referral Details</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Reason</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-1">{{ $referral->reason }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Urgency</p>
                                @php
                                    $urgencyColors = [
                                        'routine' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
                                        'urgent' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                                        'emergency' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full mt-1 {{ $urgencyColors[$referral->urgency->value] }}">
                                    {{ ucfirst($referral->urgency->value) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Clinical Summary</p>
                            <div class="bg-white dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {{ \Illuminate\Support\Str::limit($referral->clinical_summary, 300) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result Notes Section -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Result Documentation</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Provide detailed findings and outcomes from the referral</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Result Notes *
                            </span>
                        </label>
                        <div class="relative">
                            <textarea 
                                wire:model="result_notes" 
                                rows="8"
                                class="w-full px-4 py-4 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 resize-none"
                                placeholder="Enter comprehensive findings, diagnosis confirmed, treatments provided, medications prescribed, procedures performed, recommendations given, and follow-up instructions..."
                            ></textarea>
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400 dark:text-gray-500">
                                {{ strlen($result_notes ?? '') }}/2000
                            </div>
                        </div>
                        @error('result_notes') 
                            <div class="mt-2 flex items-center gap-2 text-sm text-red-600 dark:text-red-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Attachments Section -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Result Attachments</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Upload supporting documents (Optional)</p>
                        </div>
                    </div>

                    <!-- Upload Area -->
                    <div 
                        x-data="{ 
                            isDragging: false,
                            handleDragOver(event) {
                                event.preventDefault();
                                this.isDragging = true;
                            },
                            handleDragLeave(event) {
                                event.preventDefault();
                                this.isDragging = false;
                            },
                            handleDrop(event) {
                                event.preventDefault();
                                this.isDragging = false;
                                const files = event.dataTransfer.files;
                                @this.uploadMultiple('result_attachments', files);
                            }
                        }"
                        @dragover="handleDragOver"
                        @dragleave="handleDragLeave"
                        @drop="handleDrop"
                        class="border-2 border-dashed rounded-xl p-8 transition-all duration-200"
                        :class="isDragging 
                            ? 'border-green-400 dark:border-green-500 bg-green-50 dark:bg-green-900/20' 
                            : 'border-gray-300 dark:border-gray-600 hover:border-green-300 dark:hover:border-green-400'"
                    >
                        <div class="text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <div class="mt-4">
                                <label class="cursor-pointer">
                                    <span class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Choose files
                                    </span>
                                    <input 
                                        type="file" 
                                        wire:model="result_attachments" 
                                        multiple 
                                        class="sr-only"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx"
                                    >
                                </label>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">or drag and drop</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                    Images, PDF, DOC, XLS up to 10MB each • Max 10 files
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- File Errors -->
                    @error('result_attachments')
                        <div class="flex items-center gap-2 text-sm text-red-600 dark:text-red-400 mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror

                    @error('result_attachments.*')
                        <div class="flex items-center gap-2 text-sm text-red-600 dark:text-red-400 mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Uploaded Files Preview -->
                    @if ($result_attachments)
                        <div class="mt-6 space-y-3">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Selected Files ({{ count($result_attachments) }})
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($result_attachments as $index => $file)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-900/10 rounded flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                                                    {{ $file->getClientOriginalName() }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ number_format($file->getSize() / 1024, 1) }} KB
                                                </p>
                                            </div>
                                        </div>
                                        <button 
                                            type="button" 
                                            wire:click="removeAttachment({{ $index }})"
                                            class="p-1.5 text-red-500 hover:text-red-700 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors duration-200"
                                            title="Remove file"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Important Notice -->
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 rounded-xl p-5 border border-yellow-200 dark:border-yellow-800">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.284 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-2">Important Notice</h4>
                            <div class="text-sm text-yellow-700 dark:text-yellow-400 space-y-2">
                                <p>✅ Submitting results will mark this referral as <span class="font-bold">completed</span>.</p>
                                <p>✅ This action is final and cannot be undone.</p>
                                <p>✅ Ensure all information is accurate and complete before submission.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-end gap-4">
                    <a 
                        href="{{ url()->previous() }}" 
                        class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200 border border-gray-300 dark:border-gray-600 flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button 
                        type="submit"
                        class="px-8 py-3 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-500 dark:to-emerald-500 rounded-xl hover:from-green-700 hover:to-emerald-700 dark:hover:from-green-600 dark:hover:to-emerald-600 transition-all duration-200 shadow-lg shadow-green-500/30 dark:shadow-green-500/20 flex items-center justify-center gap-3"
                        wire:loading.attr="disabled"
                        wire:target="submit"
                    >
                        <span wire:loading.remove wire:target="submit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </span>
                        <span wire:loading wire:target="submit">
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </span>
                        Submit Results
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Note -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Need help? Contact support at 
                <a href="mailto:support@hospital.com" class="text-blue-600 dark:text-blue-400 hover:underline">
                    support@hospital.com
                </a>
            </p>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="submit" 
         class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm z-50 items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-2xl">
            <div class="flex items-center gap-4">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-800 dark:text-gray-200">Submitting Results</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Please wait while we save your submission...</p>
                </div>
            </div>
        </div>
    </div>
</div>