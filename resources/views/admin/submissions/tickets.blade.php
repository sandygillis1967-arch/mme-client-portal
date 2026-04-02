<x-layouts.admin title="Support tickets" subtitle="Respond to client support requests">

    @forelse($tickets as $ticket)
    <div class="card mb-4">
        <div class="flex items-start justify-between gap-4 mb-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <h2 class="text-sm font-semibold text-gray-900">{{ $ticket->subject }}</h2>
                    <span class="badge {{ $ticket->statusClass() }}">{{ $ticket->statusLabel() }}</span>
                    <span class="text-xs {{ $ticket->priorityClass() }} font-medium">{{ ucfirst($ticket->priority) }} priority</span>
                </div>
                <p class="text-xs text-gray-400">
                    {{ $ticket->user->name }} — {{ $ticket->user->company_name }}
                    &nbsp;·&nbsp; {{ $ticket->created_at->format('M j, Y g:i A') }}
                </p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg px-4 py-3 mb-4">
            <p class="text-sm text-gray-700 leading-relaxed">{{ $ticket->description }}</p>
        </div>

        @if($ticket->admin_response)
        <div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-3 mb-4">
            <p class="text-xs font-semibold text-blue-700 mb-1">MME response — {{ $ticket->responded_at?->format('M j, Y') }}</p>
            <p class="text-sm text-blue-800 leading-relaxed">{{ $ticket->admin_response }}</p>
        </div>
        @endif

        @if($ticket->status !== 'closed')
        <form method="POST" action="{{ route('admin.tickets.respond', $ticket) }}" class="space-y-3">
            @csrf @method('PATCH')

            <div>
                <label class="form-label">{{ $ticket->admin_response ? 'Update response' : 'Your response' }}</label>
                <textarea name="admin_response" class="form-textarea"
                          placeholder="Write your response to the client...">{{ old('admin_response', $ticket->admin_response) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <select name="status" class="form-select max-w-[180px]">
                    <option value="open"        {{ $ticket->status === 'open'        ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In progress</option>
                    <option value="closed"      {{ $ticket->status === 'closed'      ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" class="btn-primary">
                    {{ $ticket->admin_response ? 'Update' : 'Send response' }}
                </button>
            </div>
        </form>
        @else
        <div class="flex items-center justify-between">
            <p class="text-xs text-gray-400">Closed {{ $ticket->responded_at?->format('M j, Y') }}</p>
            <form method="POST" action="{{ route('admin.tickets.respond', $ticket) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="open">
                <input type="hidden" name="admin_response" value="{{ $ticket->admin_response }}">
                <button class="text-xs text-blue-600 hover:underline">Reopen ticket</button>
            </form>
        </div>
        @endif
    </div>
    @empty
        <div class="card text-center py-12">
            <p class="text-sm text-gray-400">No support tickets yet.</p>
        </div>
    @endforelse

</x-layouts.admin>
