<x-layouts.portal title="My services" subtitle="Your active services and status">

    {{-- Stat cards --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="stat-card">
            <div class="text-xs text-gray-500 mb-1">Active services</div>
            <div class="text-2xl font-semibold text-gray-900">{{ $activeCount }}</div>
            <div class="text-xs text-gray-400 mt-1">All running</div>
        </div>
        <div class="stat-card">
            <div class="text-xs text-gray-500 mb-1">Open tickets</div>
            <div class="text-2xl font-semibold text-gray-900">{{ $openTickets }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ $openTickets === 1 ? 'Awaiting response' : 'In queue' }}</div>
        </div>
        <div class="stat-card">
            <div class="text-xs text-gray-500 mb-1">Onboarding</div>
            <div class="text-2xl font-semibold text-gray-900">{{ $onboardingPercent }}%</div>
            <div class="text-xs text-gray-400 mt-1">
                @if($onboardingPercent === 100) Complete @else Items remaining @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5">

        {{-- Services list --}}
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Your services</h2>

            @forelse($services as $service)
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 last:border-0">
                    <span class="text-sm text-gray-700">{{ $service->name }}</span>
                    <span class="badge badge-{{ $service->status === 'active' ? 'active' : ($service->status === 'pending' ? 'pending' : 'complete') }}">
                        {{ $service->statusLabel() }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-4 text-center">No services yet. Contact MME to get started.</p>
            @endforelse
        </div>

        {{-- Recent activity --}}
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Recent activity</h2>

            @forelse($recentActivity as $item)
                <div class="flex items-start justify-between py-2.5 border-b border-gray-100 last:border-0 gap-3">
                    <span class="text-sm text-gray-600 leading-snug">{{ $item['label'] }}</span>
                    <span class="text-xs text-gray-400 flex-shrink-0">{{ $item['date']->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-4 text-center">No activity yet.</p>
            @endforelse
        </div>

    </div>

    {{-- Project status tracker --}}
    @if(auth()->user()->feature_project_status)
        @php $stages = auth()->user()->projectStages()->where('is_active', true)->get(); @endphp
        @if($stages->count())
        <div class="card mt-5" id="project">
            <h2 class="text-sm font-semibold text-gray-800 mb-5">Project status</h2>

            @foreach($stages as $stage)
            @php
                $stageKeys = array_keys(\App\Models\ProjectStage::STAGES);
                $currentIdx = $stage->stageIndex();
            @endphp
            <div class="mb-5 last:mb-0">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-700">{{ $stage->project_name }}</span>
                    <span class="badge badge-active">{{ $stage->stageLabel() }}</span>
                </div>
                <div class="flex items-center">
                    @foreach(\App\Models\ProjectStage::STAGES as $key => $label)
                    @php $idx = array_search($key, $stageKeys); @endphp
                    <div class="flex flex-col items-center flex-1">
                        <div class="stage-dot {{ $idx < $currentIdx ? 'stage-dot-done' : ($idx === $currentIdx ? 'stage-dot-active' : 'stage-dot-pending') }}">
                            @if($idx < $currentIdx)
                                <svg width="12" height="12" viewBox="0 0 16 16" fill="none">
                                    <path d="M3 8l3 3 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @else
                                {{ $idx + 1 }}
                            @endif
                        </div>
                        <span class="text-[10px] text-gray-500 mt-1 text-center">{{ $label }}</span>
                    </div>
                    @if(!$loop->last)
                    <div class="stage-line {{ $idx < $currentIdx ? 'stage-line-done' : '' }} mb-4"></div>
                    @endif
                    @endforeach
                </div>
                @if($stage->stage_notes)
                    <p class="text-xs text-gray-500 mt-3 bg-gray-50 rounded-lg px-3 py-2">{{ $stage->stage_notes }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    @endif

</x-layouts.portal>
