<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-200 text-black">
                    @foreach($columns as $col)
                        <th class="p-4 text-left font-semibold uppercase tracking-wide text-xs">
                            {{ ucfirst(str_replace('_',' ',$col)) }}
                        </th>
                    @endforeach
                    <th class="w-24 p-4 text-center font-semibold uppercase tracking-wide text-xs">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @foreach($rows as $row)
                <tr class="transition-all duration-200 hover:bg-blue-50/50">
                    @foreach($columns as $col)
                        <td class="p-4 text-slate-700">{{ $row->$col }}</td>
                    @endforeach
                    <td class="p-4 text-center">
                        <button 
                            wire:click="edit({{ $row->id }})" 
                            class="inline-flex items-center gap-1.5 rounded-lg bg-sky-600 px-4 py-2 text-xs font-medium text-white transition-all duration-200 hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
