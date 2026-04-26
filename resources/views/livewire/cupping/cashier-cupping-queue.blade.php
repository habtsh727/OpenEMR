<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="h-14 w-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Cupping Payment Queue</h1>
                            <p class="text-blue-100 mt-1">Process payments for cupping therapy orders</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 text-center">
                            <p class="text-xs text-blue-200">Pending Payments</p>
                            <p class="text-2xl font-bold text-white">{{ $therapies->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert --}}
        @if($showAlert)
        <div class="mb-4 p-4 rounded-xl flex items-center justify-between shadow-lg
            {{ $alertType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
               ($alertType === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
                'bg-yellow-100 border border-yellow-400 text-yellow-700') }}">
            <span>{{ $alertMessage }}</span>
            <button wire:click="closeAlert" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Patient</label>
                    <input type="text" wire:model.live="search" placeholder="Search by patient name..."
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Status</label>
                    <select wire:model.live="statusFilter" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="all">All Pending</option>
                        <option value="pending_payment">No Payment Made</option>
                        <option value="partial_paid">Partial Payment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Per Page</label>
                    <select wire:model.live="perPage" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Orders Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($therapies as $therapy)
                @php
                    $totalAmount = $therapy->sessions->sum('session_amount');
                    $totalPaid = $therapy->sessions->sum('paid_amount');
                    $pendingAmount = $totalAmount - $totalPaid;
                    $paidSessions = $therapy->sessions->where('payment_status', 'paid')->count();
                    $totalSessions = $therapy->sessions->count();
                    $unpaidSessions = $therapy->sessions->where('payment_status', '!=', 'paid')->count();
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    {{-- Card Header --}}
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">{{ $therapy->encounter->patient->name ?? 'N/A' }}</h3>
                                <p class="text-xs text-gray-500 mt-1">Card: {{ $therapy->encounter->patient->card_number ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Order Date</p>
                                <p class="text-sm font-medium">{{ $therapy->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5 space-y-3">
                        {{-- Ordered Packages --}}
                        <div>
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ordered Packages:</div>
                            <div class="space-y-1">
                                @foreach($therapy->groupedPackages as $packageId => $group)
                                    <div class="text-sm">
                                        <span class="font-medium">{{ $group->first()->package_name_snapshot }}</span>
                                        <span class="text-gray-500 text-xs ml-2">{{ $group->count() }} session(s)</span>
                                        <div class="text-xs text-gray-500">@ ETB {{ number_format($group->first()->package_price_snapshot, 2) }}/session</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Sessions Progress --}}
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Sessions Progress:</span>
                            <span class="font-medium">{{ $paidSessions }}/{{ $totalSessions }} completed</span>
                        </div>

                        {{-- Financial Info --}}
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-3">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Amount:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">ETB {{ number_format($totalAmount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Paid Amount:</span>
                                <span class="font-semibold text-green-600">ETB {{ number_format($totalPaid, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Pending Amount:</span>
                                <span class="font-semibold text-red-600">ETB {{ number_format($pendingAmount, 2) }}</span>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        <div class="mt-2">
                            @if($pendingAmount == 0)
                                <span class="inline-block w-full text-center px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-medium">
                                    Fully Paid ✓
                                </span>
                            @elseif($totalPaid > 0)
                                <span class="inline-block w-full text-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-medium">
                                    Partial Payment - {{ $unpaidSessions }} session(s) pending
                                </span>
                            @else
                                <span class="inline-block w-full text-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium">
                                    No Payment - {{ $totalSessions }} session(s) pending
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Card Footer --}}
                    <div class="bg-gray-50 dark:bg-gray-700/30 px-5 py-3 border-t border-gray-200 dark:border-gray-700">
                        <button wire:click="processPayment({{ $therapy->id }})"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-medium transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Process Payment
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">No pending payments found</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">All payments are up to date</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $therapies->links() }}
        </div>

        {{-- Session Selection Modal --}}
@if($showSessionSelectionModal && $selectedTherapy)
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
     x-data="{
        selectAll: @entangle('selectAll'),
        sessions: @entangle('sessionsList'),
        updateSelectAll() {
            let allSelected = this.sessions.every(s => s.selected);
            if (this.selectAll !== allSelected) {
                this.selectAll = allSelected;
            }
        },
        toggleAll() {
            this.sessions.forEach((session, index) => {
                session.selected = this.selectAll;
            });
            $wire.set('sessionsList', this.sessions);
            $wire.calculateSelectedTotal();
        }
     }"
     x-init="updateSelectAll()"
     x-on:select-all-changed.window="updateSelectAll()">

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

        <div class="sticky top-0 bg-white dark:bg-gray-800 border-b px-6 py-4 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Select Sessions to Pay</h3>
                <p class="text-sm text-gray-500">{{ $selectedTherapy->encounter->patient->name }}</p>
            </div>
            <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>

        <div class="p-6">
            {{-- Select All --}}
            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox"
                        x-model="selectAll"
                        @change="toggleAll()"
                        class="w-4 h-4 text-blue-600 rounded">
                    <span class="ml-2 font-medium">Select All Sessions</span>
                </label>
            </div>

            {{-- Sessions List --}}
            <div class="space-y-3 max-h-96 overflow-y-auto mb-6">
                @foreach($sessionsList as $index => $session)
                    <div class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/30
                        {{ $session['selected'] ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700' }}"
                        wire:click="toggleSessionSelection({{ $index }})">
                        <input type="checkbox"
                            wire:model="sessionsList.{{ $index }}.selected"
                            @change="$wire.calculateSelectedTotal(); $dispatch('select-all-changed')"
                            class="mt-1 mr-3">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-semibold">Session #{{ $session['session_number'] }}</span>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session['session_date'])->format('l, F d, Y') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold">ETB {{ number_format($session['amount'], 2) }}</div>
                                    @if($session['paid_amount'] > 0)
                                        <div class="text-xs text-green-600">Paid: ETB {{ number_format($session['paid_amount'], 2) }}</div>
                                        <div class="text-xs text-red-600">Due: ETB {{ number_format($session['remaining'], 2) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs text-blue-600 mt-1">Package: {{ $session['package_name'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Total Summary --}}
            <div class="border-t pt-4">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold">Total Selected:</span>
                    <span class="text-2xl font-bold text-blue-600">
                        ETB {{ number_format(array_sum(array_column(array_filter($sessionsList, function($s) { return $s['selected']; }), 'remaining')), 2) }}
                    </span>
                </div>

                <div class="flex gap-3">
                    <button wire:click="closePaymentModal" class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                    <button wire:click="proceedToPayment" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
                        Proceed to Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

        {{-- Payment Modal --}}
        @if($showPaymentModal && $selectedTherapy)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">

                <div class="sticky top-0 bg-white dark:bg-gray-800 border-b px-6 py-4 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Process Payment</h3>
                        <p class="text-sm text-gray-500">{{ $selectedTherapy->encounter->patient->name }}</p>
                    </div>
                    <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <div class="p-6 space-y-4">
                    {{-- Selected Sessions Summary --}}
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                        <p class="text-sm font-medium mb-2">Selected Sessions:</p>
                        <div class="space-y-1">
                            @foreach($sessionsList as $session)
                                @if($session['selected'])
                                    <div class="flex justify-between text-sm">
                                        <span>Session #{{ $session['session_number'] }}</span>
                                        <span>ETB {{ number_format($session['remaining'], 2) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="border-t mt-2 pt-2 flex justify-between font-bold">
                            <span>Total Due:</span>
                            <span class="text-red-600">ETB {{ number_format($paymentAmount, 2) }}</span>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Payment Method</label>
                        <select wire:model="paymentMethod" class="w-full px-3 py-2 border rounded-lg">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    @if($paymentMethod == 'mobile_money')
                    <div>
                        <label class="block text-sm font-medium mb-1">Transaction ID</label>
                        <input type="text" wire:model="transactionId" placeholder="Enter transaction ID" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    @endif

                    {{-- Amount Paid --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Amount Paid (ETB)</label>
                        <input type="number" wire:model.live="paymentAmount" step="0.01" class="w-full px-3 py-2 border rounded-lg text-lg font-semibold">
                    </div>

                    {{-- Change Due --}}
                    @if($changeAmount > 0)
                    <div class="bg-green-100 dark:bg-green-900/20 rounded-lg p-4 text-center">
                        <span class="text-sm text-green-700">Change Due</span>
                        <div class="text-2xl font-bold text-green-700">ETB {{ number_format($changeAmount, 2) }}</div>
                    </div>
                    @endif
                </div>

                <div class="sticky bottom-0 bg-white dark:bg-gray-800 border-t px-6 py-4 flex gap-3">
                    <button wire:click="closePaymentModal" class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                    <button wire:click="confirmPayment" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">Confirm Payment</button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
