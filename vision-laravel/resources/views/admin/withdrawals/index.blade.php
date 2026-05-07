<x-layouts.dashboard dashboardType="admin">
    <x-slot name="title">Withdrawal Requests</x-slot>
    <x-slot name="subtitle">Manage seller withdrawal requests</x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-ui.metric-card 
            title="Pending Requests" 
            :value="$stats['pending']"
            icon="clock"
            tone="warning"
        />
        <x-ui.metric-card 
            title="Today's Volume" 
            value="{{ number_format($stats['today'], 2) }}"
            icon="trending-up"
            tone="primary"
        />
        <x-ui.metric-card 
            title="Total Approved" 
            value="{{ number_format($stats['total'], 2) }}"
            icon="check-circle"
            tone="success"
        />
        <x-ui.metric-card 
            title="Approved Count" 
            :value="$stats['approved']"
            icon="users"
            tone="info"
        />
    </div>

    <!-- Filters -->
    <x-ui.panel>
        <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="flex flex-wrap gap-4 items-end">
            <x-ui.input 
                type="select" 
                name="status" 
                label="Status"
                :options="[
                    '' => 'All',
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected'
                ]"
                :value="request('status')"
            />
            <x-ui.input 
                type="date" 
                name="date_from" 
                label="From Date"
                :value="request('date_from')"
            />
            <x-ui.input 
                type="date" 
                name="date_to" 
                label="To Date"
                :value="request('date_to')"
            />
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-outline">Reset</a>
        </form>
    </x-ui.panel>

    <!-- Data Table -->
    <x-ui.panel class="mt-6">
        <x-slot name="header">
            <h3 class="text-lg font-semibold">Withdrawal Requests</h3>
        </x-slot>

        @if($withdrawals->count() > 0)
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Seller</th>
                            <th>Amount</th>
                            <th>Bank</th>
                            <th>Account</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $withdrawal)
                            <tr>
                                <td>#{{ $withdrawal->id }}</td>
                                <td>
                                    <div class="font-medium">{{ $withdrawal->seller->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $withdrawal->seller->company_name ?? 'N/A' }}</div>
                                </td>
                                <td class="font-bold">{{ number_format($withdrawal->amount, 2) }}</td>
                                <td>{{ $withdrawal->bank_name }}</td>
                                <td>{{ $withdrawal->account_number }}</td>
                                <td>
                                    <x-ui.badge :tone="$withdrawal->status === 'approved' ? 'success' : ($withdrawal->status === 'rejected' ? 'error' : 'warning')">
                                        {{ ucfirst($withdrawal->status) }}
                                    </x-ui.badge>
                                </td>
                                <td>{{ $withdrawal->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        @if($withdrawal->status === 'pending')
                                            <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this withdrawal?')">
                                                    Approve
                                                </button>
                                            </form>
                                            
                                            <button 
                                                onclick="rejectModal_{{ $withdrawal->id }}.showModal()"
                                                class="btn btn-sm btn-error"
                                            >
                                                Reject
                                            </button>

                                            <x-ui.modal :id="'rejectModal_' . $withdrawal->id" title="Reject Withdrawal">
                                                <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                                                    @csrf
                                                    <x-ui.input 
                                                        type="textarea" 
                                                        name="reason" 
                                                        label="Rejection Reason"
                                                        required="false"
                                                        placeholder="Optional reason for rejection"
                                                    />
                                                    <div class="modal-action">
                                                        <button type="button" class="btn btn-ghost" onclick="{{ 'rejectModal_' . $withdrawal->id }}.close()">Cancel</button>
                                                        <button type="submit" class="btn btn-error">Reject</button>
                                                    </div>
                                                </form>
                                            </x-ui.modal>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                        
                                        <button class="btn btn-sm btn-ghost" onclick="detailsModal_{{ $withdrawal->id }}.showModal()">
                                            View
                                        </button>

                                        <x-ui.modal :id="'detailsModal_' . $withdrawal->id" title="Withdrawal Details">
                                            <div class="space-y-3">
                                                <div><strong>ID:</strong> #{{ $withdrawal->id }}</div>
                                                <div><strong>Seller:</strong> {{ $withdrawal->seller->user->name }}</div>
                                                <div><strong>Amount:</strong> {{ number_format($withdrawal->amount, 2) }}</div>
                                                <div><strong>Bank:</strong> {{ $withdrawal->bank_name }}</div>
                                                <div><strong>Account Number:</strong> {{ $withdrawal->account_number }}</div>
                                                <div><strong>Account Name:</strong> {{ $withdrawal->account_name }}</div>
                                                <div><strong>Status:</strong> {{ ucfirst($withdrawal->status) }}</div>
                                                @if($withdrawal->rejection_reason)
                                                    <div><strong>Rejection Reason:</strong> {{ $withdrawal->rejection_reason }}</div>
                                                @endif
                                                <div><strong>Created:</strong> {{ $withdrawal->created_at->format('Y-m-d H:i:s') }}</div>
                                            </div>
                                            <div class="modal-action">
                                                <button type="button" class="btn btn-ghost" onclick="{{ 'detailsModal_' . $withdrawal->id }}.close()">Close</button>
                                            </div>
                                        </x-ui.modal>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $withdrawals->links() }}
            </div>
        @else
            <x-ui.empty-state 
                title="No Withdrawal Requests"
                description="There are no withdrawal requests matching your criteria"
                icon="inbox"
            />
        @endif
    </x-ui.panel>
</x-layouts.dashboard>
