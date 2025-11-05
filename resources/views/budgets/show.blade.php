<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $budget->name }} - Budget Details
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('budgets.edit', $budget) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Budget
                </a>
                <a href="{{ route('budgets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ← Back to Budgets
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Budget Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Budget Information</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Period</dt>
                                    <dd class="text-sm text-gray-900">{{ ucfirst($budget->period) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Amount</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($budget->amount, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $budget->start_date->format('M j, Y') }}</dd>
                                </div>
                                @if($budget->end_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">End Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $budget->end_date->format('M j, Y') }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $budget->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $budget->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Spending Summary</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Spent</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($budget->totalSpent(), 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Remaining</dt>
                                    <dd class="text-sm {{ $budget->remainingBudget() >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($budget->remainingBudget(), 2) }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Percentage Used</dt>
                                    <dd class="text-sm text-gray-900">{{ number_format($budget->percentageUsed(), 1) }}%</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Progress</h3>
                            <div class="space-y-2">
                                @php $percentage = $budget->percentageUsed() @endphp
                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    <div class="bg-{{ $percentage > 90 ? 'red' : ($percentage > 75 ? 'yellow' : 'green') }}-600 h-4 rounded-full flex items-center justify-center text-xs text-white font-medium" style="width: {{ min($percentage, 100) }}%">
                                        @if($percentage > 20)
                                            {{ number_format($percentage, 0) }}%
                                        @endif
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">
                                    @if($percentage <= 50)
                                        You're doing great! Keep it up.
                                    @elseif($percentage <= 75)
                                        You're on track but watch your spending.
                                    @elseif($percentage <= 90)
                                        Getting close to your budget limit.
                                    @else
                                        You've exceeded your budget.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($budget->description)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                            <p class="text-sm text-gray-600">{{ $budget->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Purchases -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Purchases ({{ $budget->purchases->count() }})</h3>
                        <a href="{{ route('purchases.create') }}?budget_id={{ $budget->id }}" class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add Purchase
                        </a>
                    </div>

                    @if($budget->purchases->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($budget->purchases->sortByDesc('purchase_date') as $purchase)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $purchase->name }}</div>
                                                    @if($purchase->description)
                                                        <div class="text-sm text-gray-500">{{ Str::limit($purchase->description, 50) }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900">${{ number_format($purchase->amount, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($purchase->category)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $purchase->category }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-500">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $purchase->purchase_date->format('M j, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('purchases.edit', $purchase) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                                    <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this purchase?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No purchases yet</h3>
                            <p class="text-gray-500 mb-4">Start tracking your expenses for this budget.</p>
                            <a href="{{ route('purchases.create') }}?budget_id={{ $budget->id }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Add Your First Purchase
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>