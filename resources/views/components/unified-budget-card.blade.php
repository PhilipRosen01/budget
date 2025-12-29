@props(['remaining', 'totalBudget', 'totalSpent', 'percentageUsed', 'salary', 'investment'])

<!-- Unified Budget Card - Mobile First Design -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <!-- Main Focus: Remaining Amount -->
    <div class="text-center mb-6">
        <p class="text-sm text-gray-500 uppercase tracking-wide mb-2">Remaining This Month</p>
        <h2 class="text-4xl sm:text-5xl font-bold {{ $remaining >= 0 ? 'text-green-600' : 'text-red-600' }}">
            ${{ number_format(abs($remaining), 2) }}
        </h2>
        @if($remaining < 0)
            <p class="text-lg text-red-500 mt-2 font-semibold">
                Over Budget
            </p>
        @else
            <p class="text-lg text-gray-600 mt-2">
                {{ number_format($percentageUsed, 0) }}% of budget used
            </p>
        @endif
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-gray-200 rounded-full h-4 mb-6">
        <div class="h-4 rounded-full transition-all duration-500 {{ $percentageUsed > 90 ? 'bg-red-500' : ($percentageUsed > 75 ? 'bg-yellow-500' : 'bg-gradient-to-r from-indigo-600 to-purple-600') }}" 
             style="width: {{ min($percentageUsed, 100) }}%; background: {{ $percentageUsed > 90 ? '#ef4444' : ($percentageUsed > 75 ? '#eab308' : 'linear-gradient(to right, #4f46e5 0%, #7c3aed 100%)') }}">
        </div>
    </div>

    <!-- Budget Summary - 2 Column Grid -->
    <div class="grid grid-cols-2 gap-4 pb-4 mb-4 border-b border-gray-200">
        <div class="text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Available Budget</p>
            <p class="text-xl font-bold text-gray-900">${{ number_format($totalBudget, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">Salary: ${{ number_format($salary, 2) }}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Spent</p>
            <p class="text-xl font-bold text-gray-900">${{ number_format($totalSpent, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">Investment: ${{ number_format($investment, 2) }}</p>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-3">
            <p class="text-xs text-indigo-600 font-medium mb-1">Budget Status</p>
            <p class="text-sm font-bold text-indigo-900">
                {{ $percentageUsed > 90 ? '⚠️ High Usage' : ($percentageUsed > 75 ? '⚡ Caution' : '✅ On Track') }}
            </p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-3">
            <p class="text-xs text-green-600 font-medium mb-1">Available</p>
            <p class="text-sm font-bold text-green-900">
                ${{ number_format(max(0, $remaining), 2) }}
            </p>
        </div>
    </div>
</div>
