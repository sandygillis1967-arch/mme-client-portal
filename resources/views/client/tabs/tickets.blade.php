<x-layouts.portal title="Support tickets" subtitle="Get help or report an issue">

    <div class="grid grid-cols-2 gap-5">

        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Submit a ticket</h2>

            <form method="POST" action="{{ route('client.tickets.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label" for="subject">Subject</label>
                    <input id="subject" name="subject" type="text" class="form-input"
                           placeholder="Brief description of the issue..."
                           value="{{ old('subject') }}" required>
                    @error('subject') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label" for="priority">Priority</label>
                    <select id="priority" name="priority" class="form-select" required>
                        <option value="">Select priority...</option>
                        <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>Low — not urgent</option>
                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium — needs attention</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent — something is broken</option>
                    </select>
                    @error('priority') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" class="form-textarea" style="min-height:120px"
                              placeholder="Describe the issue in detail. What were you trying to do? What happened instead?">{{ old('description') }}</textarea>
                    @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-primary w-full">Submit ticket</button>
            </form>
        </div>

        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Your tickets</h2>

            @forelse($tickets as $ticket)
                <div class="py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <p class="text-sm font-medium text-gray-800">{{ $ticket->subject }}</p>
                        <span class="badge {{ $ticket->statusClass() }} flex-shrink-0">{{ $ticket->statusLabel() }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs {{ $ticket->priorityClass() }} font-medium">{{ ucfirst($ticket->priority) }} priority</span>
                        <span class="text-xs text-gray-400">{{ $ticket->created_at->format('M j, Y') }}</span>
                    </div>
                    @if($ticket->admin_response)
                        <div class="mt-2 bg-blue-50 border border-blue-100 rounded-lg px-3 py-2">
                            <p class="text-xs font-semibold text-blue-700 mb-0.5">MME response</p>
                            <p class="text-xs text-blue-800">{{ $ticket->admin_response }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-400 py-6 text-center">No tickets yet.</p>
            @endforelse
        </div>

    </div>

</x-layouts.portal>
