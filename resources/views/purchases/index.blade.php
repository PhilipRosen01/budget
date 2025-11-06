<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Purchases') }}
            </h2>
            <a href="{{ route('purchases.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Purchase
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters and Sorting -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <!-- Month/Year Filter -->
                        <div class="flex items-center space-x-4">
                            <form method="GET" action="{{ route('purchases.index') }}" class="flex items-center space-x-3">
                                <label for="month" class="text-sm font-medium text-gray-700">Filter by:</label>
                                <select name="month" id="month" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                                <select name="year" id="year" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                                <input type="hidden" name="sort" value="{{ $sortBy }}">
                                <input type="hidden" name="direction" value="{{ $sortDirection }}">
                                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                                    Filter
                                </button>
                            </form>
                        </div>

                        <!-- Sort Options -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-700">Sort by:</span>
                            <div class="flex space-x-1">
                                @php
                                    $sorts = [
                                        'purchase_date' => 'Date',
                                        'amount' => 'Amount',
                                        'name' => 'Name',
                                        'category' => 'Category'
                                    ];
                                @endphp
                                @foreach($sorts as $key => $label)
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => $key, 'direction' => ($sortBy === $key && $sortDirection === 'asc') ? 'desc' : 'asc']) }}" 
                                       class="px-2 py-1 text-xs font-medium rounded {{ $sortBy === $key ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        {{ $label }}
                                        @if($sortBy === $key)
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($purchases->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Purchases for {{ Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->format('F Y') }}
                                <span class="text-sm text-gray-500">({{ $purchases->count() }} items)</span>
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => ($sortBy === 'name' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}" class="group inline-flex items-center hover:text-gray-900">
                                                Purchase
                                                @if($sortBy === 'name')
                                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'amount', 'direction' => ($sortBy === 'amount' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}" class="group inline-flex items-center hover:text-gray-900">
                                                Amount
                                                @if($sortBy === 'amount')
                                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'category', 'direction' => ($sortBy === 'category' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}" class="group inline-flex items-center hover:text-gray-900">
                                                Category
                                                @if($sortBy === 'category')
                                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'purchase_date', 'direction' => ($sortBy === 'purchase_date' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}" class="group inline-flex items-center hover:text-gray-900">
                                                Date
                                                @if($sortBy === 'purchase_date')
                                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($purchases as $purchase)
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
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($purchase->budget)
                                                    <span class="text-sm text-gray-900">{{ $purchase->budget->name }}</span>
                                                @else
                                                    <span class="text-sm text-gray-500">No budget</span>
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
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No purchases found</h3>
                        <p class="text-gray-500 mb-4">
                            No purchases found for {{ Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->format('F Y') }}.
                            Try selecting a different month or add a new purchase.
                        </p>
                        <div class="flex justify-center space-x-3">
                            <a href="{{ route('purchases.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View All Purchases
                            </a>
                            <a href="{{ route('purchases.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Add Purchase
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>