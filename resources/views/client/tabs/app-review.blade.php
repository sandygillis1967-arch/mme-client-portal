<x-layouts.portal title="Web app review" subtitle="Review your builds and log feedback per screen or section">

    @if($reviews->isEmpty())
        <div class="card text-center py-12">
            <p class="text-sm text-gray-500">No app reviews yet.</p>
            <p class="text-xs text-gray-400 mt-1">MME will post a review here when your app is ready to test.</p>
        </div>
    @endif

    @foreach($reviews as $review)
    <div class="card mb-4">

        {{-- Review header --}}
        <div class="flex items-center justify-between cursor-pointer"
             onclick="toggleReview('review-{{ $review->id }}', 'chev-{{ $review->id }}')">
            <div>
                <h2 class="text-sm font-semibold text-gray-900">{{ $review->displayName() }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $review->isApproved() ? 'Approved ' . $review->approved_at?->format('M j, Y') : 'Sent for review ' . $review->created_at->format('M j, Y') }}
                    &nbsp;·&nbsp; {{ $review->feedback->count() }} feedback {{ Str::plural('item', $review->feedback->count()) }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="badge badge-{{ $review->isApproved() ? 'approved' : 'review' }}">
                    {{ $review->statusLabel() }}
                </span>
                <svg id="chev-{{ $review->id }}"
                     class="w-4 h-4 text-gray-400 transition-transform duration-200 {{ $loop->first && !$review->isApproved() ? 'rotate-90' : '' }}"
                     viewBox="0 0 16 16" fill="none">
                    <path d="M6 4l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- Review body --}}
        <div id="review-{{ $review->id }}"
             class="{{ $loop->first && !$review->isApproved() ? '' : 'hidden' }} mt-4 pt-4 border-t border-gray-100">

            {{-- Staging URL bar --}}
            <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 mb-5">
                <svg class="text-gray-400 flex-shrink-0" width="14" height="14" viewBox="0 0 16 16" fill="none">
                    <rect x="2" y="3" width="12" height="8" rx="1.5" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M5 14h6M8 11v3" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
                <span class="text-xs font-mono text-blue-600 flex-1 truncate">{{ $review->staging_url }}</span>
                <a href="{{ $review->staging_url }}" target="_blank" rel="noopener"
                   class="text-xs font-medium text-blue-600 border border-blue-200 rounded-md px-2.5 py-1 hover:bg-blue-50 flex-shrink-0 flex items-center gap-1">
                    Open staging
                    <svg width="10" height="10" viewBox="0 0 16 16" fill="none">
                        <path d="M10 3h3v3M13 3l-7 7M7 5H4a1 1 0 00-1 1v6a1 1 0 001 1h6a1 1 0 001-1v-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            {{-- Feedback thread --}}
            <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-3">Feedback thread</h3>

            @forelse($review->feedback as $fb)
            <div class="border border-gray-200 rounded-lg p-3 mb-2.5 bg-white">
                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                    <span class="text-xs font-semibold text-gray-800">{{ $fb->screen_section }}</span>
                    <span class="badge badge-{{ $fb->issueClass() }}">{{ $fb->issueLabel() }}</span>
                    <span class="text-xs text-gray-400 ml-auto">{{ $fb->created_at->format('M j') }}</span>
                </div>
                <p class="text-xs text-gray-600 leading-relaxed mb-2">{{ $fb->description }}</p>
                <div class="flex items-center gap-1.5">
                    <span class="dot {{ $fb->statusDotClass() }}"></span>
                    <span class="text-[10px] text-gray-400">{{ $fb->statusLabel() }}</span>
                </div>
                @if($fb->admin_notes)
                    <div class="mt-2 bg-blue-50 border border-blue-100 rounded-md px-2.5 py-1.5">
                        <p class="text-[10px] font-semibold text-blue-600 mb-0.5">MME note</p>
                        <p class="text-xs text-blue-800">{{ $fb->admin_notes }}</p>
                    </div>
                @endif
            </div>
            @empty
                <p class="text-xs text-gray-400 py-3 text-center">No feedback logged yet.</p>
            @endforelse

            {{-- Actions --}}
            @if(!$review->isApproved())
            <div class="flex gap-3 mt-4 pt-4 border-t border-gray-100">
                <button onclick="openModal('approve-{{ $review->id }}')"
                        class="btn-approve flex-1">
                    Approve this version
                </button>
                <button onclick="openModal('feedback-{{ $review->id }}')"
                        class="btn-secondary flex-1">
                    Log feedback ↗
                </button>
            </div>
            @else
                @if($review->approval_notes)
                    <p class="text-xs text-gray-500 mt-3 bg-gray-50 rounded-lg px-3 py-2">
                        <span class="font-medium">Your approval note:</span> {{ $review->approval_notes }}
                    </p>
                @endif
            @endif
        </div>
    </div>

    {{-- Feedback modal --}}
    @if(!$review->isApproved())
    <div id="feedback-{{ $review->id }}"
         class="modal-backdrop hidden fixed inset-0 bg-black bg-opacity-30 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl border border-gray-200 p-6 w-full max-w-md mx-4" onclick="event.stopPropagation()">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Log feedback — {{ $review->displayName() }}</h3>

            <form method="POST" action="{{ route('client.app-review.feedback', $review) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Screen / section</label>
                    <input type="text" name="screen_section" class="form-input"
                           placeholder="e.g. Job form, Dashboard, Login screen" required>
                </div>
                <div>
                    <label class="form-label">Issue type</label>
                    <select name="issue_type" class="form-select" required>
                        <option value="">Select type...</option>
                        <option value="bug">Bug / something broken</option>
                        <option value="change_request">Change request</option>
                        <option value="design_issue">Looks wrong / design issue</option>
                        <option value="works_great">Works great — no issues</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea"
                              placeholder="Describe what you're seeing or what you'd like changed..." required></textarea>
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closeModal('feedback-{{ $review->id }}')"
                            class="btn-secondary flex-1">Cancel</button>
                    <button type="submit" class="btn-primary flex-1">Submit feedback</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Approve modal --}}
    <div id="approve-{{ $review->id }}"
         class="modal-backdrop hidden fixed inset-0 bg-black bg-opacity-30 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl border border-gray-200 p-6 w-full max-w-md mx-4" onclick="event.stopPropagation()">
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Approve {{ $review->displayName() }}</h3>
            <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                By approving, you confirm you're happy with this version and MME can proceed.
                You can still log support tickets after approval.
            </p>

            <form method="POST" action="{{ route('client.app-review.approve', $review) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Final notes (optional)</label>
                    <textarea name="approval_notes" class="form-textarea"
                              placeholder="Any last message for the MME team..."></textarea>
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closeModal('approve-{{ $review->id }}')"
                            class="btn-secondary flex-1">Go back</button>
                    <button type="submit" class="btn-approve flex-1">Confirm approval</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @endforeach

</x-layouts.portal>

<script>
function toggleReview(bodyId, chevId) {
    const body = document.getElementById(bodyId);
    const chev = document.getElementById(chevId);
    body.classList.toggle('hidden');
    chev.classList.toggle('rotate-90');
}
</script>
