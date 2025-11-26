{{-- detail modal --}}
    <flux:modal name="detail-service-category" class="md:w-96">
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="border-b border-gray-200 pb-4">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 shadow-lg shadow-sky-500/30">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <flux:heading size="lg" class="text-gray-900">Service Category</flux:heading>
                    <flux:text class="mt-0.5 text-sm text-gray-500">
                        {{ $description ?? 'No description available' }}
                    </flux:text>
                </div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Name Card -->
            <div class="col-span-2 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="text-xs font-medium uppercase tracking-wider text-gray-500">Category Name</div>
                <div class="mt-1 text-base font-semibold text-gray-900">{{ $name }}</div>
            </div>

            <!-- Code Card -->
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="text-xs font-medium uppercase tracking-wider text-gray-500">Code</div>
                <div class="mt-1 font-mono text-sm font-semibold text-sky-600">{{ $code }}</div>
            </div>

            <!-- Status Card -->
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="text-xs font-medium uppercase tracking-wider text-gray-500">Status</div>
                <div class="mt-2">
                    @if($is_active)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-gray-500"></span>
                            Inactive
                        </span>
                    @endif
                </div>
            </div>

            <!-- Created At -->
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="text-xs font-medium uppercase tracking-wider text-gray-500">Created</div>
                <div class="mt-1 text-sm text-gray-700">{{ $created_at }}</div>
            </div>

            <!-- Updated At -->
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="text-xs font-medium uppercase tracking-wider text-gray-500">Updated</div>
                <div class="mt-1 text-sm text-gray-700">{{ $updated_at }}</div>
            </div>
        </div>

        <!-- Services List -->
        @if(count($services))
        <div class="rounded-lg border border-sky-100 bg-sky-50 p-4">
            <div class="mb-3 flex items-center gap-2">
                <svg class="h-5 w-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <flux:heading size="sm" class="text-sky-900">Services ({{ count($services) }})</flux:heading>
            </div>
            <div class="max-h-48 space-y-2 overflow-y-auto">
                @foreach($services as $service)
                    <div class="flex items-center gap-2 rounded-md bg-white px-3 py-2 shadow-sm ring-1 ring-gray-900/5 transition-all hover:shadow-md">
                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-sky-100">
                            <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="truncate text-sm font-medium text-gray-900">{{ $service->name }}</div>
                            <div class="text-xs font-mono text-gray-500">{{ $service->code }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</flux:modal>