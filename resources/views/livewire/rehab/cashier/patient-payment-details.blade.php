{{-- resources/views/livewire/rehab/cashier/patient-payment-details.blade.php --}}

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8" x-data="{ 
        showAlert: @entangle('showAlert'),
        alertMessage: @entangle('alertMessage'),
        alertType: @entangle('alertType')
     }">

    <!-- Alert Notification -->
    <div x-show="showAlert" x-cloak x-init="setTimeout(() => showAlert = false, 5000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed top-4 right-4 z-50 max-w-md w-full">
        <div class="rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
            <div class="flex items-center justify-between p-4" :class="{
                    'bg-gradient-to-r from-green-500 to-emerald-600': alertType === 'success',
                    'bg-gradient-to-r from-red-500 to-rose-600': alertType === 'error',
                    'bg-gradient-to-r from-yellow-500 to-orange-600': alertType === 'warning',
                    'bg-gradient-to-r from-blue-500 to-indigo-600': alertType === 'info'
                 }">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <template x-if="alertType === 'success'">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="alertType === 'error'">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                    </div>
                    <div>
                        <p class="font-medium text-white" x-text="alertMessage"></p>
                    </div>
                </div>
                <button @click="showAlert = false" class="text-white/80 hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Back Button -->
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('rehab.cashier.queue') }}"
                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Details</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Order #{{ $order->id }} • {{
                                $order->encounter->encounter->patient->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="px-3 py-1 text-sm rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($order->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                        <span
                            class="px-3 py-1 text-sm rounded-full {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $order->status === 'paid' ? 'Active' : 'Pending' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Progress Overview -->
        <!-- Payment Progress Overview - Fixed Progress Bar -->
        <!-- Payment Progress Overview - Fixed with proper remaining calculation -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Amount Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Total Amount</p>
                @php $totalAmount = $this->grandTotal(); @endphp
                <p class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white truncate">
                    ETB {{ number_format($totalAmount, 2) }}
                </p>
            </div>

            <!-- Paid Amount Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Paid Amount</p>
                <p class="text-xl lg:text-2xl font-bold text-green-600 truncate">ETB {{
                    number_format($order->paid_amount, 2) }}</p>
            </div>

            <!-- Remaining Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Remaining</p>
                @php
                $totalAmount = $this->grandTotal();
                $remainingAmount = max(0, $totalAmount - $order->paid_amount);
                @endphp
                <p
                    class="text-xl lg:text-2xl font-bold {{ $remainingAmount > 0 ? 'text-orange-600' : 'text-green-600' }} truncate">
                    ETB {{ number_format($remainingAmount, 2) }}
                </p>
            </div>
            <!-- Progress Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Progress</p>
        <div class="flex items-center gap-2">
            <div class="flex-1 min-w-0">
                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                    @php 
                        $totalAmount = $this->grandTotal();
                        $progress = $totalAmount > 0 ? min(round(($order->paid_amount / $totalAmount) * 100, 1), 100) : 0;
                    @endphp
                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300"
                        style="width: {{ $progress }}%"></div>
                </div>
            </div>
            <span class="text-sm font-bold text-indigo-600 whitespace-nowrap">{{ $progress }}%</span>
        </div>
        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
            <span class="truncate">Paid: {{ number_format($order->paid_amount, 0) }}</span>
            <span class="truncate">Total: {{ number_format($totalAmount, 0) }}</span>
        </div>
    </div>
        </div>
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div
                class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-blue-600 dark:text-blue-400">Installments Paid</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $this->paidInstallmentsCount()
                            }}/{{ $this->totalInstallmentsCount() }}</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 rounded-xl p-4 border border-amber-200 dark:border-amber-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-amber-600 dark:text-amber-400">Next Due Date</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $this->nextDueDate() ? $this->nextDueDate()->format('M d, Y') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-purple-600 dark:text-purple-400">Next Amount</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{
                            number_format($this->nextDueAmount(), 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient & Order Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Patient Information
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Name:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{
                            $order->encounter->encounter->patient->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Doctor:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{
                            $order->encounter->encounter->doctor->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Order Date:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $order->created_at->format('M d, Y')
                            }}</span>
                    </div>
                </div>
            </div>

            @if($order->bedSelections->isNotEmpty())
            @php $bedSelection = $order->bedSelections->first(); @endphp
            <div
                class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
                <h3 class="font-semibold text-purple-800 dark:text-purple-300 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    Bed Information
                </h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-purple-600 dark:text-purple-400">Bed Class</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $bedSelection->bedClass->name ?? 'N/A'
                            }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-purple-600 dark:text-purple-400">Bed Number</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $bedSelection->bed->bed_number ?? 'N/A'
                            }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-purple-600 dark:text-purple-400">Duration</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $bedSelection->duration_days ?? 0 }}
                            days</p>
                    </div>
                    <div>
                        <p class="text-xs text-purple-600 dark:text-purple-400">Price per Day</p>
                        <p class="font-medium text-gray-900 dark:text-white">ETB {{
                            number_format($bedSelection->price_per_day ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Payment Schedule -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Payment Schedule</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($order->paymentInstallments as $installment)
                    <div class="border rounded-lg p-4 {{ $installment->status === 'paid' ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 
                        ($installment->due_date->isPast() && $installment->status !== 'paid' ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' : 
                        'bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-700') }}">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-full {{ $installment->status === 'paid' ? 'bg-green-100 dark:bg-green-900/30' : ($installment->due_date->isPast() ? 'bg-red-100 dark:bg-red-900/30' : 'bg-indigo-100 dark:bg-indigo-900/30') }} flex items-center justify-center">
                                    <span
                                        class="font-bold {{ $installment->status === 'paid' ? 'text-green-600 dark:text-green-400' : ($installment->due_date->isPast() ? 'text-red-600 dark:text-red-400' : 'text-indigo-600 dark:text-indigo-400') }}">
                                        #{{ $installment->installment_number }}
                                    </span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900 dark:text-white">Installment {{
                                            $installment->installment_number }}</span>
                                        @if($installment->status === 'paid')
                                        <span
                                            class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs rounded-full">Paid</span>
                                        @elseif($installment->due_date->isPast())
                                        <span
                                            class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 text-xs rounded-full">Overdue</span>
                                        @else
                                        <span
                                            class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs rounded-full">Pending</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Amount: ETB {{ number_format($installment->amount, 2) }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Due Date</p>
                                    <p
                                        class="font-medium {{ $installment->due_date->isPast() && $installment->status !== 'paid' ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                        {{ $installment->due_date->format('M d, Y') }}
                                    </p>
                                </div>

                                @if($installment->status !== 'paid')
                                <button wire:click="openPaymentModal({{ $installment->id }})"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                    Pay Now
                                </button>
                                @else
                                <div class="px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                                    Paid on {{ $installment->paid_date->format('M d, Y') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($installment->paid_amount > 0 && $installment->status !== 'paid')
                        <div class="mt-3">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Progress</span>
                                <span class="font-medium text-indigo-600 dark:text-indigo-400">
                                    {{ round(($installment->paid_amount / $installment->amount) * 100, 1) }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 dark:bg-indigo-500 h-2 rounded-full"
                                    style="width: {{ ($installment->paid_amount / $installment->amount) * 100 }}%">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Paid: ETB {{ number_format($installment->paid_amount, 2) }} • Remaining: ETB {{
                                number_format($installment->getRemainingAmount(), 2) }}
                            </p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        @if($showPaymentModal && $selectedInstallment)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Process Payment</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Installment #{{ $selectedInstallment->installment_number }} • Order #{{ $order->id }}
                    </p>
                </div>

                <div class="p-6 space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Installment Amount:</span>
                            <span class="font-bold text-gray-900 dark:text-white">ETB {{
                                number_format($selectedInstallment->amount, 2) }}</span>
                        </div>
                        @if($selectedInstallment->paid_amount > 0)
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Already Paid:</span>
                            <span class="font-medium text-green-600">ETB {{
                                number_format($selectedInstallment->paid_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span class="font-semibold text-gray-900 dark:text-white">Remaining:</span>
                            <span class="text-xl font-bold text-indigo-600">ETB {{
                                number_format($selectedInstallment->getRemainingAmount(), 2) }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment
                            Method</label>
                        <select wire:model="paymentMethod"
                            class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="insurance">Insurance</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount to
                            Pay</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500">ETB</span>
                            <input type="number" step="0.01" wire:model.live="paymentAmount"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                min="1" max="{{ $selectedInstallment->getRemainingAmount() }}">
                        </div>
                    </div>

                    @if($changeAmount > 0)
                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="flex justify-between">
                            <span class="font-medium text-green-800">Change Due:</span>
                            <span class="text-xl font-bold text-green-600">ETB {{ number_format($changeAmount, 2)
                                }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closePaymentModal"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button wire:click="processPayment" @if($paymentAmount <=0 || $paymentAmount>
                        $selectedInstallment->getRemainingAmount()) disabled @endif
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50">
                        Confirm Payment
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>