<x-layouts.admin title="Submissions" subtitle="All client submissions across photos, pages and service requests">

    {{-- Photo Uploads --}}
    <div class="card mb-5">
        <h2 class="text-sm font-semibold text-gray-800 mb-4">Photo uploads</h2>
        @if($photoUploads->isEmpty())
            <p class="text-sm text-gray-400 py-4 text-center">No photo uploads yet.</p>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>File</th>
                    <th>Placement</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($photoUploads as $upload)
                <tr>
                    <td>
                        <div class="font-medium text-gray-900">{{ $upload->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $upload->user->company_name }}</div>
                    </td>
                    <td class="text-gray-700 max-w-[160px] truncate">{{ $upload->original_name }}</td>
                    <td class="text-gray-500 max-w-[200px] truncate">{{ $upload->placement_description ?? '—' }}</td>
                    <td class="text-gray-400 text-xs">{{ $upload->created_at->format('M j, Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $upload->status === 'new' ? 'new' : ($upload->status === 'in_progress' ? 'pending' : 'complete') }}">
                            {{ $upload->statusLabel() }}
                        </span>
                    </td>
                    <td class="text-right">
                        <form method="POST" action="{{ route('admin.submissions.status', ['type' => 'photo', 'id' => $upload->id]) }}"
                              class="flex items-center gap-2 justify-end">
                            @csrf @method('PATCH')
                            <select name="status" class="text-xs border border-gray-200 rounded-md px-2 py-1 bg-white" onchange="this.form.submit()">
                                <option value="new"         {{ $upload->status === 'new'         ? 'selected' : '' }}>New</option>
                                <option value="in_progress" {{ $upload->status === 'in_progress' ? 'selected' : '' }}>In progress</option>
                                <option value="complete"    {{ $upload->status === 'complete'    ? 'selected' : '' }}>Complete</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Page Requests --}}
    <div class="card mb-5">
        <h2 class="text-sm font-semibold text-gray-800 mb-4">Page requests</h2>
        @if($pageRequests->isEmpty())
            <p class="text-sm text-gray-400 py-4 text-center">No page requests yet.</p>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Page title</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($pageRequests as $req)
                <tr>
                    <td>
                        <div class="font-medium text-gray-900">{{ $req->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $req->user->company_name }}</div>
                    </td>
                    <td class="font-medium text-gray-800">{{ $req->page_title }}</td>
                    <td class="text-gray-500 max-w-[200px] truncate">{{ $req->description ?? '—' }}</td>
                    <td class="text-gray-400 text-xs">{{ $req->created_at->format('M j, Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $req->status === 'new' ? 'new' : ($req->status === 'in_progress' ? 'pending' : 'complete') }}">
                            {{ $req->statusLabel() }}
                        </span>
                    </td>
                    <td class="text-right">
                        <form method="POST" action="{{ route('admin.submissions.status', ['type' => 'page', 'id' => $req->id]) }}"
                              class="flex items-center gap-2 justify-end">
                            @csrf @method('PATCH')
                            <select name="status" class="text-xs border border-gray-200 rounded-md px-2 py-1 bg-white" onchange="this.form.submit()">
                                <option value="new"         {{ $req->status === 'new'         ? 'selected' : '' }}>New</option>
                                <option value="in_progress" {{ $req->status === 'in_progress' ? 'selected' : '' }}>In progress</option>
                                <option value="complete"    {{ $req->status === 'complete'    ? 'selected' : '' }}>Complete</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Service Requests --}}
    <div class="card">
        <h2 class="text-sm font-semibold text-gray-800 mb-4">Service requests</h2>
        @if($serviceRequests->isEmpty())
            <p class="text-sm text-gray-400 py-4 text-center">No service requests yet.</p>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($serviceRequests as $req)
                <tr>
                    <td>
                        <div class="font-medium text-gray-900">{{ $req->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $req->user->company_name }}</div>
                    </td>
                    <td class="font-medium text-gray-800">{{ $req->service_name }}</td>
                    <td class="text-gray-500 max-w-[200px] truncate">{{ $req->description ?? '—' }}</td>
                    <td class="text-gray-400 text-xs">{{ $req->created_at->format('M j, Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $req->status === 'new' ? 'new' : ($req->status === 'in_progress' ? 'pending' : 'complete') }}">
                            {{ $req->statusLabel() }}
                        </span>
                    </td>
                    <td class="text-right">
                        <form method="POST" action="{{ route('admin.submissions.status', ['type' => 'service', 'id' => $req->id]) }}"
                              class="flex items-center gap-2 justify-end">
                            @csrf @method('PATCH')
                            <select name="status" class="text-xs border border-gray-200 rounded-md px-2 py-1 bg-white" onchange="this.form.submit()">
                                <option value="new"         {{ $req->status === 'new'         ? 'selected' : '' }}>New</option>
                                <option value="in_progress" {{ $req->status === 'in_progress' ? 'selected' : '' }}>In progress</option>
                                <option value="complete"    {{ $req->status === 'complete'    ? 'selected' : '' }}>Complete</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</x-layouts.admin>
