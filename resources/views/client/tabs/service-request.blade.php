<x-layouts.portal title="Request a service" subtitle="Request a new service from MME Digital">

    <div class="grid grid-cols-2 gap-5">

        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">New service request</h2>

            <form method="POST" action="{{ route('client.services.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label" for="service_name">Service name</label>
                    <input id="service_name" name="service_name" type="text" class="form-input"
                           placeholder="e.g. Google Ads, Social Media, AI Phone Assistant..."
                           value="{{ old('service_name') }}" required>
                    @error('service_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" class="form-textarea"
                              placeholder="Tell us what you're looking for and why...">{{ old('description') }}</textarea>
                    @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label" for="notes">Additional notes (optional)</label>
                    <textarea id="notes" name="notes" class="form-textarea"
                              placeholder="Budget range, timeline, any other context...">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-primary w-full">Submit request</button>
            </form>
        </div>

        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Previous requests</h2>

            @forelse($requests as $req)
                <div class="py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">{{ $req->service_name }}</p>
                            @if($req->description)
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $req->description }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-0.5">{{ $req->created_at->format('M j, Y') }}</p>
                        </div>
                        <span class="badge badge-{{ $req->status === 'new' ? 'new' : ($req->status === 'in_progress' ? 'pending' : 'complete') }} flex-shrink-0">
                            {{ $req->statusLabel() }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-6 text-center">No service requests yet.</p>
            @endforelse
        </div>

    </div>

</x-layouts.portal>
