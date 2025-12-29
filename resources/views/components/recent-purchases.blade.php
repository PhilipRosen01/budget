@props(['purchases', 'selectedMonth'])

<!-- Enhanced Recent Purchases - Focus on Quick Tracking -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-5">
        <h3 class="text-xl font-semibold text-gray-900">Recent Purchases</h3>
        <a href="{{ route('purchases.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
            View All →
        </a>
    </div>
    
    @if($purchases->count() > 0)
        <div class="space-y-2">
            @foreach($purchases->take(8) as $purchase)
                <div class="flex items-center justify-between p-3 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 rounded-xl transition-all duration-200 group border border-transparent hover:border-indigo-100">
                    <!-- Left: Category Icon + Details -->
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <!-- Category Icon/Emoji -->
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl flex-shrink-0"
                             style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                            @if($purchase->budget)
                                <span class="text-white">{{ $purchase->budget->icon ?? '💰' }}</span>
                            @else
                                <span class="text-white">💰</span>
                            @endif
                        </div>
                        
                        <!-- Purchase Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2">
                                <p class="font-semibold text-gray-900 truncate">
                                    {{ $purchase->budget->name ?? 'Uncategorized' }}
                                </p>
                                @if($purchase->description)
                                    <span class="text-xs text-gray-400">•</span>
                                    <p class="text-sm text-gray-600 truncate">
                                        {{ $purchase->description }}
                                    </p>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $purchase->purchase_date->format('M j, g:i A') }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Right: Amount + Actions -->
                    <div class="flex items-center space-x-3 ml-4">
                        <!-- Amount -->
                        <div class="text-right">
                            <p class="font-bold text-lg text-gray-900">${{ number_format($purchase->amount, 2) }}</p>
                        </div>
                        
                        <!-- Quick Actions (Show on hover) -->
                        <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <!-- Quick Delete -->
                            <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline" onsubmit="return confirm('Delete this ${{ number_format($purchase->amount, 2) }} purchase?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Delete purchase">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Summary Footer -->
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">
                    Showing {{ min($purchases->count(), 8) }} of {{ $purchases->count() }} purchases
                </span>
                <span class="font-semibold text-gray-900">
                    Total: ${{ number_format($purchases->sum('amount'), 2) }}
                </span>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center"
                 style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">No purchases yet</h4>
            <p class="text-gray-600 mb-4">Click the + button to add your first purchase</p>
            <div class="text-sm text-gray-500">
                💡 Tip: Use <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded">Shift + A</kbd> to quickly add expenses
            </div>
        </div>
    @endif
</div>
