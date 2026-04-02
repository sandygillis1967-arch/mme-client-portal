<x-layouts.admin title="App reviews" subtitle="Manage web app review threads">
    <x-slot:actions>
        <a href="{{ route('admin.app-reviews.create') }}" class="btn-primary">New review</a>
    </x-slot:actions>

    @forelse($reviews as $review)
    <div class="card mb-4">
        <div class="flex items-center justify-between mb-3">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-semibold text-gray-900">{{ $review->displayName() }}</h2>
                    <span class="badge badge-{{ $review->isApproved() ? 'approved' : 'review' }}">
                        {{ $review->statusLabel() }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $review->user->name }} — {{ $review->user->company_name }}
                    &nbsp;·&nbsp; {{ $review->created_at->format('M j, Y') }}
                    &nbsp;·&nbsp; {{ $review->feedback->count() }} feedback items
                </p>
            </div>
            <a href="{{ $review->staging_url }}" target="_blank"
               class="text-xs text-blue-600 border border-blue-200 rounded-md px-2.5 py-1 hover:bg-blue-50">
                Open staging ↗
            </a>
        </div>

        @if($review->feedback->count())
        <div class="border-t border-gray-100 pt-3">
            @foreach($review->feedback as $fb)
            <div class="flex items-start gap-3 py-2.5 border-b border-gray-100 last:border-0">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <span class="text-xs font-medium text-gray-800">{{ $fb->screen_section }}</span>
                        <span class="badge badge-{{ $fb->issueClass() }}">{{ $fb->issueLabel() }}</span>
                        <span class="text-xs text-gray-400">{{ $fb->created_at->format('M j') }}</span>
                    </div>
                    <p class="text-xs text-gray-600">{{ $fb->description }}</p>
                </div>

                @if(!$review->isApproved())
                <form method="POST" action="{{ route('admin.app-reviews.feedback.update', $fb) }}" class="flex items-center gap-2 flex-shrink-0">
                    @csrf @method('PATCH')
                    <select name="status" class="text-xs border border-gray-200 rounded-md px-2 py-1 bg-white" onchange="this.form.submit()">
                        <option value="open"        {{ $fb->status === 'open'        ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ $fb->status === 'in_progress' ? 'selected' : '' }}>In progress</option>
                        <option value="done"        {{ $fb->status === 'done'        ? 'selected' : '' }}>Done</option>
                    </select>
                    <span class="dot {{ $fb->statusDotClass() }}"></span>
                </form>
                @else
                    <span class="dot {{ $fb->statusDotClass() }} flex-shrink-0 mt-1.5"></span>
                @endif
            </div>
            @endforeach
        </div>
        @else
            <p class="text-xs text-gray-400 pt-2">No feedback logged yet by client.</p>
        @endif
    </div>
    @empty
        <div class="card text-center py-12">
            <p class="text-sm text-gray-400">No app reviews yet.</p>
            <a href="{{ route('admin.app-reviews.create') }}" class="btn-primary mt-4 inline-block">Create first review</a>
        </div>
    @endforelse

</x-layouts.admin>
