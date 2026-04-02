<x-layouts.admin title="Onboarding items" subtitle="Manage the checklist all new clients see">

    <div class="grid grid-cols-2 gap-5">

        {{-- Add new item --}}
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">Add checklist item</h2>

            <form method="POST" action="{{ route('admin.onboarding-items.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="form-label">Label</label>
                    <input type="text" name="label" class="form-input"
                           placeholder="e.g. Business logo" value="{{ old('label') }}" required>
                    @error('label') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Description (shown to client)</label>
                    <textarea name="description" class="form-textarea"
                              placeholder="Instructions or context for this item...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="form-label">Sort order</label>
                    <input type="number" name="sort_order" class="form-input"
                           value="{{ old('sort_order', $items->count() + 1) }}" min="1">
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="requires_file" value="1"
                           {{ old('requires_file') ? 'checked' : '' }} class="rounded border-gray-300">
                    Requires file upload
                </label>

                <button type="submit" class="btn-primary w-full">Add item</button>
            </form>
        </div>

        {{-- Current items --}}
        <div class="card">
            <h2 class="text-sm font-semibold text-gray-800 mb-4">
                Current checklist
                <span class="text-gray-400 font-normal">({{ $items->count() }} items)</span>
            </h2>

            @forelse($items as $item)
            <div class="flex items-start gap-3 py-3 border-b border-gray-100 last:border-0">
                <span class="text-xs text-gray-400 w-5 flex-shrink-0 mt-0.5">{{ $item->sort_order }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">{{ $item->label }}</p>
                    @if($item->description)
                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->description }}</p>
                    @endif
                    @if($item->requires_file)
                        <span class="badge badge-new mt-1">File required</span>
                    @endif
                </div>
                <form method="POST" action="{{ route('admin.onboarding-items.destroy', $item) }}"
                      onsubmit="return confirm('Remove this item?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-400 hover:text-red-600">Remove</button>
                </form>
            </div>
            @empty
                <p class="text-sm text-gray-400 py-6 text-center">No items yet. Add your first item.</p>
            @endforelse
        </div>

    </div>

</x-layouts.admin>
