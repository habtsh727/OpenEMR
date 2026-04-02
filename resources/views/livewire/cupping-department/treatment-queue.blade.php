<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @if(!$showReportForm)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-purple-600 dark:bg-purple-700">
                <h2 class="text-2xl font-bold text-white">🏥 Cupping Treatment Queue</h2>
                <p class="text-purple-100">Patients ready for cupping therapy</p>
            </div>

            <div class="p-6">
                @if(count($queue) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left">#</th>
                                    <th class="px-4 py-3 text-left">Patient Name</th>
                                    <th class="px-4 py-3 text-left">Therapy ID</th>
                                    <th class="px-4 py-3 text-left">Session</th>
                                    <th class="px-4 py-3 text-left">Items</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($queue as $item)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 font-medium text-center">#{{ $item->position }}</td>
                                        <td class="px-4 py-3 font-medium">
                                            {{ $item->cuppingSession->cuppingTherapy->encounter->patient->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            #{{ $item->cuppingSession->cupping_therapy_id }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                {{ $item->cuppingSession->session_number }}/{{ $item->cuppingSession->cuppingTherapy->total_sessions }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($item->cuppingSession->items as $itemDetail)
                                                    <span class="inline-block text-xs bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">
                                                        {{ $itemDetail->cuppingType->name }} ({{ $itemDetail->cuppingLocation->name }})
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button wire:click="startTreatment({{ $item->cupping_session_id }})"
                                                    class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                                                Start Treatment
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                        Total waiting: {{ count($queue) }} patient(s) in treatment queue
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🏥</div>
                        <div class="text-gray-500 dark:text-gray-400">No pending treatments in queue</div>
                        <p class="text-sm text-gray-400 mt-2">Paid sessions will appear here automatically</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        @livewire('cupping-department.report-form', ['session' => $selectedSession], key($selectedSession->id))
        <div class="mt-4 text-center">
            <button wire:click="closeReportForm" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                ← Back to Queue
            </button>
        </div>
    @endif
</div>