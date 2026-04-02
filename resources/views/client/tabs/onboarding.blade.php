<x-layouts.portal title="Onboarding checklist" subtitle="Complete your setup so MME can get started">

    {{-- Progress bar --}}
    <div class="card mb-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-gray-800">Setup progress</span>
            <span class="text-sm font-semibold" style="color:#1B2A5E">{{ $percentage }}%</span>
        </div>
        <div class="progress-bar-track">
            <div class="progress-bar-fill" style="width: {{ $percentage }}%"></div>
        </div>
        @if($percentage === 100)
            <p class="text-xs text-green-600 mt-2 font-medium">All items complete — you're all set!</p>
        @else
            <p class="text-xs text-gray-400 mt-2">{{ count(array_diff(array_column($items->toArray(), 'id'), $done)) }} items remaining</p>
        @endif
    </div>

    {{-- Checklist --}}
    <div class="card">
        <h2 class="text-sm font-semibold text-gray-800 mb-4">Checklist items</h2>

        @forelse($items as $item)
        @php $isDone = in_array($item->id, $done); @endphp
        <div class="py-4 border-b border-gray-100 last:border-0">
            <div class="flex items-start gap-3">

                {{-- Check indicator --}}
                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 {{ $isDone ? 'bg-green-100' : 'bg-gray-100' }}">
                    @if($isDone)
                        <svg width="12" height="12" viewBox="0 0 16 16" fill="none">
                            <path d="M3 8l3 3 7-7" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @else
                        <span class="w-2 h-2 rounded-full bg-gray-300 inline-block"></span>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium {{ $isDone ? 'text-gray-400 line-through' : 'text-gray-800' }}">
                        {{ $item->label }}
                    </p>
                    @if($item->description)
                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->description }}</p>
                    @endif
                    @if($item->requires_file)
                        <p class="text-xs text-blue-600 mt-0.5">File upload required</p>
                    @endif
                </div>

                {{-- Action --}}
                <div class="flex-shrink-0">
                    @if($isDone)
                        <form method="POST" action="{{ route('client.onboarding.undo', $item) }}">
                            @csrf @method('DELETE')
                            <button class="text-xs text-gray-400 hover:text-gray-600 underline">Undo</button>
                        </form>
                    @else
                        <button onclick="openModal('modal-{{ $item->id }}')"
                                class="btn-primary text-xs px-3 py-1.5">
                            Mark complete
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modal for completing item --}}
        @if(!$isDone)
        <div id="modal-{{ $item->id }}"
             class="modal-backdrop hidden fixed inset-0 bg-black bg-opacity-30 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl border border-gray-200 p-6 w-full max-w-md mx-4"
                 onclick="event.stopPropagation()">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ $item->label }}</h3>

                <form method="POST" action="{{ route('client.onboarding.complete', $item) }}"
                      enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    @if($item->requires_file)
                    <div>
                        <label class="form-label">Upload file</label>
                        <input type="file" name="file" class="form-input" required>
                    </div>
                    @endif

                    <div>
                        <label class="form-label">Notes (optional)</label>
                        <textarea name="notes" class="form-textarea"
                                  placeholder="Any notes or links to share..."></textarea>
                    </div>

                    <div class="flex gap-3 pt-1">
                        <button type="button" onclick="closeModal('modal-{{ $item->id }}')"
                                class="btn-secondary flex-1">Cancel</button>
                        <button type="submit" class="btn-primary flex-1">Mark complete</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @empty
            <p class="text-sm text-gray-400 py-6 text-center">No checklist items set up yet. MME will add these shortly.</p>
        @endforelse
    </div>

</x-layouts.portal>
