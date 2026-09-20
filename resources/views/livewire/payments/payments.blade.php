<div class="max-w-[95rem] mx-auto">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Patients Payments</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage and  all patient payments </p>
        </div>
    </div>

    {{-- Success Message --}}
    @session('success')
        <div class="fixed top-5 right-5 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm p-4 rounded-lg shadow-lg z-50 flex items-center gap-3"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" role="alert">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            {{ $value }}
        </div>
    @endsession

    {{-- Modals --}}
    <livewire:patients.create-patients />
    <livewire:patients.edit-patients />
    <livewire:patients.patient-detail />

    {{-- Search Bar --}}
    <div class="mb-6">
        <div class="relative">
            <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" wire:model.live="search" placeholder="Search by name, card number, phone..."
                class="w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white transition-all duration-200 text-gray-700">
        </div>
    </div>

    {{-- Table Card --}}
    <div
        class="bg-white dark:bg-slate-800 shadow-xl rounded-xl overflow-hidden border border-gray-100 dark:border-slate-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead
                    class="bg-gradient-to-r from-slate-50 to-gray-50 dark:from-slate-700 dark:to-slate-600 border-b border-gray-200 dark:border-slate-600">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            No</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Name</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Card Number</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Gender</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Phone</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Region</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Registered</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Payment Status</th>
                        <th
                            class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse ($patients as $patient)
                        <tr class="hover:bg-blue-50 dark:hover:bg-slate-700/50 transition-colors duration-150 group">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $patient->name }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-mono bg-gray-50 dark:bg-slate-700/30 px-3 py-2 rounded inline-block max-w-xs truncate">
                                {{ $patient->card_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $patient->gender === 'Male' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-300' }}">
                                    {{ $patient->gender }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex flex-col gap-1">
                                    <span class="font-medium">{{ $patient->phone_number1 }}</span>
                                    @if ($patient->phone_number2)
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-500">{{ $patient->phone_number2 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                {{ $patient->region }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $patient->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    // Get latest unpaid payment
                                    $payment = $patient->latestUnpaidCardPayment();
                                @endphp

                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
        {{ $payment
            ? 'bg-gradient-to-r from-red-100 to-red-100 text-red-800 dark:from-red-900/40 dark:to-emerald-900/40 dark:text-red-300'
            : 'bg-gradient-to-r from-green-100 to-green-100 text-green-700 dark:from-green-900/40 dark:to-green-900/40 dark:text-green-300' }}">
                                    <span
                                        class="w-2 h-2 rounded-full mr-2 {{ $payment ? 'bg-red-400' : 'bg-green-500' }}"></span>
                                    {{ $payment ? 'Not Paid' : 'Paid' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm">
                                <div class="flex justify-center items-center gap-3">
                                    {{-- View Detail Button --}}
                                    <flux:button size="sm" variant="ghost"
    onclick="window.location='{{ route('payments-detail', ['patient' => $patient->id]) }}'"
    class="p-1 hover:bg-gray-100 dark:hover:bg-slate-700/30 rounded">
    <flux:icon.identification class="text-green-500" />
</flux:button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400 text-lg font-medium">No patients found
                                    </p>
                                    <p class="text-gray-500 dark:text-gray-500 text-sm">Try adjusting your search
                                        criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div
            class="px-6 py-4 bg-gray-50 dark:bg-slate-700/30 border-t border-gray-200 dark:border-slate-700 flex justify-end">
            {{ $patients->links() }}
        </div>
    </div>
</div>
