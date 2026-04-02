<x-layouts.admin title="Project stages" subtitle="Set and update client project timelines">

    <div class="grid grid-cols-2 gap-5">

        {{-- Set / update a stage --}}
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Set project stage</h2>

            <form method="POST" action="{{ route('admin.project-stages.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label">Client</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select client...</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('user_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} — {{ $client->company_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Project name</label>
                    <input type="text" name="project_name" class="form-input"
                           placeholder="e.g. Website redesign, Field forms app..."
                           value="{{ old('project_name') }}" required>
                    @error('project_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Current stage</label>
                    <select name="current_stage" class="form-select" required>
                        @foreach(\App\Models\ProjectStage::STAGES as $key => $label)
                            <option value="{{ $key }}" {{ old('current_stage') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('current_stage') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Stage notes (shown to client)</label>
                    <textarea name="stage_notes" class="form-textarea"
                              placeholder="Optional note visible to the client about this stage...">{{ old('stage_notes') }}</textarea>
                </div>

                <button type="submit" class="btn-primary w-full">Save stage</button>
            </form>
        </div>

        {{-- Current stages --}}
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Active projects</h2>

            @forelse($stages->where('is_active', true) as $stage)
            <div class="py-3 border-b border-gray-100 last:border-0">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $stage->project_name }}</p>
                        <p class="text-xs text-gray-400">
                            {{ $stage->user->name }} — {{ $stage->user->company_name }}
                        </p>
                        @if($stage->stage_notes)
                            <p class="text-xs text-gray-500 mt-1 italic">{{ $stage->stage_notes }}</p>
                        @endif
                    </div>
                    <span class="badge badge-active flex-shrink-0">{{ $stage->stageLabel() }}</span>
                </div>

                {{-- Mini stage track --}}
                <div class="flex items-center mt-2 gap-0">
                    @foreach(\App\Models\ProjectStage::STAGES as $key => $label)
                    @php
                        $keys = array_keys(\App\Models\ProjectStage::STAGES);
                        $idx  = array_search($key, $keys);
                        $cur  = $stage->stageIndex();
                    @endphp
                    <div class="flex items-center flex-1 last:flex-none">
                        <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $idx <= $cur ? 'bg-navy-900' : 'bg-gray-200' }}"
                             style="{{ $idx <= $cur ? 'background-color:#1B2A5E' : '' }}"
                             title="{{ $label }}"></div>
                        @if(!$loop->last)
                            <div class="h-px flex-1 {{ $idx < $cur ? 'bg-navy-900' : 'bg-gray-200' }}"
                                 style="{{ $idx < $cur ? 'background-color:#1B2A5E' : '' }}"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
                <p class="text-sm text-gray-400 py-6 text-center">No active projects yet.</p>
            @endforelse
        </div>

    </div>

</x-layouts.admin>
