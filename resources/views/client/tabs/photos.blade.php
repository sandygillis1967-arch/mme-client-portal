<x-layouts.portal title="Upload photos" subtitle="Upload images for your website">

    <div class="grid grid-cols-2 gap-5">

        {{-- Upload form --}}
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Upload a photo</h2>

            <form method="POST" action="{{ route('client.photos.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label">Photo file</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-gray-400 transition-colors"
                         onclick="document.getElementById('photo-input').click()">
                        <svg class="mx-auto mb-2 text-gray-400" width="24" height="24" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="4" width="12" height="9" rx="1.5" stroke="currentColor" stroke-width="1.2"/>
                            <path d="M8 7v4M6 9l2-2 2 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="text-sm text-gray-500">Click to select a photo</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF, WebP up to 10MB</p>
                        <p class="text-xs text-gray-600 mt-2 font-medium" id="file-name">No file selected</p>
                    </div>
                    <input id="photo-input" name="photo" type="file" accept="image/*" class="hidden"
                           onchange="document.getElementById('file-name').textContent = this.files[0]?.name ?? 'No file selected'">
                    @error('photo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label" for="placement">Where should this photo go?</label>
                    <textarea id="placement" name="placement_description" class="form-textarea"
                              placeholder="e.g. Homepage hero image, About page team photo...">{{ old('placement_description') }}</textarea>
                    @error('placement_description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label" for="text_to_add">Text to add (optional)</label>
                    <textarea id="text_to_add" name="text_to_add" class="form-textarea"
                              placeholder="Any text or captions to add alongside the photo...">{{ old('text_to_add') }}</textarea>
                    @error('text_to_add') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-primary w-full">Upload photo</button>
            </form>
        </div>

        {{-- Previous uploads --}}
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Previous uploads</h2>

            @forelse($uploads as $upload)
                <div class="py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $upload->original_name }}</p>
                            @if($upload->placement_description)
                                <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $upload->placement_description }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-0.5">{{ $upload->created_at->format('M j, Y') }}</p>
                        </div>
                        <span class="badge badge-{{ $upload->status === 'new' ? 'new' : ($upload->status === 'in_progress' ? 'pending' : 'complete') }} flex-shrink-0">
                            {{ $upload->statusLabel() }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-6 text-center">No uploads yet.</p>
            @endforelse
        </div>

    </div>

</x-layouts.portal>
