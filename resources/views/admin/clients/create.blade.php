<x-layouts.admin title="Add client" subtitle="Create a new client account">

    <div class="card max-w-lg">
        <form method="POST" action="{{ route('admin.clients.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Full name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Company name</label>
                    <input type="text" name="company_name" class="form-input" value="{{ old('company_name') }}">
                </div>
            </div>

            <div>
                <label class="form-label">Email address</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                    @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Confirm password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
            </div>

            <div class="pt-2 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-600 mb-3">Feature access</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach([
                        'feature_onboarding'      => 'Onboarding checklist',
                        'feature_project_status'  => 'Project status',
                        'feature_support_tickets' => 'Support tickets',
                        'feature_app_review'      => 'Web app review',
                        'feature_website_review'  => 'Website review',
                        'feature_document_vault'  => 'Document vault',
                        'feature_seo_reports'     => 'SEO reports',
                        'feature_ai_status'       => 'AI services status',
                        'feature_hosting_details' => 'Hosting details',
                        'feature_invoices'        => 'Invoices & payments',
                    ] as $flag => $label)
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="{{ $flag }}" value="1"
                               {{ in_array($flag, ['feature_onboarding','feature_project_status','feature_support_tickets']) ? 'checked' : '' }}
                               class="rounded border-gray-300">
                        {{ $label }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.clients') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Create client</button>
            </div>
        </form>
    </div>

</x-layouts.admin>
