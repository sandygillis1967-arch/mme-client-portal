<x-layouts.admin title="Clients" subtitle="Manage client accounts and feature access">
    <x-slot:actions>
        <a href="{{ route('admin.clients.create') }}" class="btn-primary">Add client</a>
    </x-slot:actions>

    <div class="card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Features</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td class="font-medium text-gray-900">{{ $client->name }}</td>
                    <td>{{ $client->company_name ?? '—' }}</td>
                    <td class="text-gray-500">{{ $client->email }}</td>
                    <td>
                        <div class="flex flex-wrap gap-1">
                            @if($client->feature_app_review)    <span class="badge badge-new">App review</span> @endif
                            @if($client->feature_support_tickets) <span class="badge badge-new">Tickets</span> @endif
                            @if($client->feature_document_vault) <span class="badge badge-complete">Docs</span> @endif
                            @if($client->feature_invoices)       <span class="badge badge-complete">Billing</span> @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $client->is_active ? 'badge-active' : 'badge-complete' }}">
                            {{ $client->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.clients.edit', $client) }}"
                           class="text-xs text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-gray-400 py-8">No clients yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>
