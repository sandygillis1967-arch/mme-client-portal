<x-layouts.admin title="New app review" subtitle="Send a build to a client for review">

    <div class="card max-w-lg">
        <form method="POST" action="{{ route('admin.app-reviews.store') }}" class="space-y-4">
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">App name</label>
                    <input type="text" name="app_name" class="form-input"
                           placeholder="e.g. Thompson Field Forms" value="{{ old('app_name') }}" required>
                    @error('app_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Version (optional)</label>
                    <input type="text" name="version" class="form-input"
                           placeholder="e.g. v1.2" value="{{ old('version') }}">
                </div>
            </div>

            <div>
                <label class="form-label">Staging URL</label>
                <input type="url" name="staging_url" class="form-input"
                       placeholder="https://app-staging.up.railway.app" value="{{ old('staging_url') }}" required>
                @error('staging_url') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.app-reviews') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Create review</button>
            </div>
        </form>
    </div>

</x-layouts.admin>
