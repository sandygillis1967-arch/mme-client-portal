<x-layouts.admin title="Dashboard" subtitle="MME Digital client portal overview">

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="stat-card">
            <div class="text-xs text-gray-500 mb-1">Active clients</div>
            <div class="text-2xl font-semibold text-gray-900">{{ $clientCount }}</div>
        </div>
        <div class="stat-card">
            <div class="text-xs text-gray-500 mb-1">New submissions</div>
            <div class="text-2xl font-semibold text-gray-900">{{ $newSubmissions }}</div>
        </div>
        <div class="stat-card">
            <div class="text-xs text-gray-500 mb-1">Open tickets</div>
            <div class="text-2xl font-semibold text-gray-900">{{ $openTickets }}</div>
        </div>
        <div class="stat-card">
            <div class="text-xs text-gray-500 mb-1">Active app reviews</div>
            <div class="text-2xl font-semibold text-gray-900">{{ $activeReviews }}</div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-800">Recent clients</h2>
            <a href="{{ route('admin.clients') }}" class="text-xs text-blue-600 hover:underline">View all</a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentClients as $client)
                <tr>
                    <td class="font-medium text-gray-900">{{ $client->name }}</td>
                    <td>{{ $client->company_name ?? '—' }}</td>
                    <td class="text-gray-500">{{ $client->email }}</td>
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
                @endforeach
            </tbody>
        </table>
    </div>

</x-layouts.admin>
