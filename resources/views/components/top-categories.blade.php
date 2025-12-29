@props(['monthBudgets'])

<!-- Top 3 Spending Categories - Quick Overview -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-5">
        <h3 class="text-xl font-semibold text-gray-900">Top Categories</h3>
        <a href="{{ route('budgets.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
            Manage →
        </a>
    </div>
    
    @if($monthBudgets->count() > 0)
        @php
            // Get top 3 categories by spending
            $topBudgets = $monthBudgets->sortByDesc(function($budget) {
                return $budget->totalSpent();
            })->take(3);
        @endphp
        
        <div class="space-y-5">
            @foreach($topBudgets as $index => $budget)
                @php
                    $percentage = $budget->percentageUsed();
                    $spent = $budget->totalSpent();
                    $amount = $budget->amount;
                    $remaining = $amount - $spent;
                    
                    // Determine color
                    if ($percentage >= 100) {
                        $statusColor = 'red';
                        $statusText = 'Over Budget';
                        $statusEmoji = '⚠️';
                    } elseif ($percentage >= 90) {
                        $statusColor = 'orange';
                        $statusText = 'Almost Full';
                        $statusEmoji = '⚡';
                    } elseif ($percentage >= 75) {
                        $statusColor = 'yellow';
                        $statusText = 'Caution';
                        $statusEmoji = '⚠️';
                    } else {
                        $statusColor = 'green';
                        $statusText = 'On Track';
                        $statusEmoji = '✅';
                    }
                    
                    // Medal for top 3
                    $medals = ['🥇', '🥈', '🥉'];
                @endphp
                
                <div class="p-4 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100 hover:border-indigo-200 transition-all">
                    <!-- Header: Rank + Category -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">{{ $medals[$index] }}</span>
                            <!-- Category Icon -->
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl flex-shrink-0"
                                 style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                                <span class="text-white">{{ $budget->icon }}</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $budget->name }}</h4>
                                @if($budget->category)
                                    <p class="text-xs text-gray-500">{{ ucfirst($budget->category) }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700">
                            {{ $statusEmoji }} {{ $statusText }}
                        </span>
                    </div>
                    
                    <!-- Spending Info -->
                    <div class="flex items-baseline space-x-2 mb-2">
                        <span class="text-2xl font-bold text-gray-900">
                            ${{ number_format($spent, 2) }}
                        </span>
                        <span class="text-sm text-gray-500">
                            of ${{ number_format($amount, 2) }}
                        </span>
                        @if($remaining > 0)
                            <span class="text-xs text-green-600 ml-auto">
                                ${{ number_format($remaining, 2) }} left
                            </span>
                        @else
                            <span class="text-xs text-red-600 ml-auto">
                                ${{ number_format(abs($remaining), 2) }} over
                            </span>
                        @endif
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="relative">
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            @if($percentage >= 100)
                                <div class="h-3 rounded-full transition-all duration-500 bg-red-500"
                                     style="width: 100%"></div>
                            @elseif($percentage >= 90)
                                <div class="h-3 rounded-full transition-all duration-500 bg-orange-500"
                                     style="width: {{ min($percentage, 100) }}%"></div>
                            @elseif($percentage >= 75)
                                <div class="h-3 rounded-full transition-all duration-500 bg-yellow-500"
                                     style="width: {{ $percentage }}%"></div>
                            @else
                                <div class="h-3 rounded-full transition-all duration-500"
                                     style="width: {{ $percentage }}%; background: linear-gradient(to right, #4f46e5 0%, #7c3aed 100%)"></div>
                            @endif
                        </div>
                        <!-- Percentage Label -->
                        <div class="absolute right-0 -top-5 text-xs font-bold text-gray-600">
                            {{ number_format($percentage, 0) }}%
                        </div>
                    </div>
                    
                    <!-- Purchase Count -->
                    <div class="mt-2 text-xs text-gray-500 text-right">
                        {{ $budget->purchases()->count() }} purchase{{ $budget->purchases()->count() !== 1 ? 's' : '' }}
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Show All Link -->
        @if($monthBudgets->count() > 3)
            <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                <a href="{{ route('budgets.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 inline-flex items-center">
                    View all {{ $monthBudgets->count() }} categories
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center"
                 style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">No budget categories</h4>
            <p class="text-gray-600 mb-4">Create budget templates to track your spending</p>
            <a href="{{ route('budget-templates.index') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white transition-all transform hover:scale-105"
               style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                Create Budget Templates
            </a>
        </div>
    @endif
</div>
