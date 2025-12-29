@props(['monthBudgets'])

<!-- Quick Add Expense Modal -->
<div id="quickAddModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4" onclick="closeQuickAddModal(event)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 sm:p-8 transform transition-all" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Add Expense</h3>
            <button type="button" onclick="closeQuickAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('purchases.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <!-- Amount Input -->
            <div>
                <label for="quick-amount" class="block text-sm font-semibold text-gray-700 mb-2">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-2xl sm:text-3xl font-bold text-gray-400">$</span>
                    <input type="number" 
                           id="quick-amount"
                           name="amount" 
                           step="0.01" 
                           required 
                           autofocus
                           placeholder="0.00"
                           class="w-full pl-10 sm:pl-12 pr-4 py-4 text-2xl sm:text-3xl font-bold border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>
            </div>

            <!-- Category Select -->
            <div>
                <label for="quick-budget" class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                <select id="quick-budget" 
                        name="budget_id" 
                        required
                        class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <option value="">Select category...</option>
                    @foreach($monthBudgets as $budget)
                        <option value="{{ $budget->id }}">
                            {{ $budget->name }} 
                            (${{ number_format($budget->totalSpent(), 2) }} / ${{ number_format($budget->amount, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Optional: Description -->
            <div>
                <label for="quick-description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Description <span class="text-gray-400 font-normal">(optional)</span>
                </label>
                <input type="text" 
                       id="quick-description"
                       name="description"
                       placeholder="e.g., Groceries, Gas, Coffee..."
                       class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-2">
                <button type="button" 
                        onclick="closeQuickAddModal()"
                        class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 active:bg-gray-100 transition-colors min-h-[44px]">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 rounded-xl font-semibold text-white transition-all transform hover:scale-105 active:scale-95 min-h-[44px] shadow-lg"
                        style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                    Add Expense
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openQuickAddModal() {
    const modal = document.getElementById('quickAddModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Focus on amount input after modal opens
    setTimeout(() => {
        document.getElementById('quick-amount')?.focus();
    }, 100);
}

function closeQuickAddModal(event) {
    if (!event || event.target.id === 'quickAddModal') {
        const modal = document.getElementById('quickAddModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        
        // Reset form
        const form = modal.querySelector('form');
        if (form) form.reset();
    }
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeQuickAddModal();
    }
});
</script>
