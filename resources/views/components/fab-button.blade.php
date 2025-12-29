<!-- Fixed Floating Action Button -->
<div class="fixed bottom-6 right-6 z-50">
    <button onclick="openQuickAddModal()" 
            class="w-14 h-14 sm:w-16 sm:h-16 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-110 active:scale-95 transition-all duration-200 flex items-center justify-center focus:outline-none focus:ring-4 focus:ring-indigo-300"
            style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);"
            aria-label="Add expense">
        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
    </button>
    
    <!-- Tooltip for desktop -->
    <div class="hidden sm:block absolute bottom-full right-0 mb-2 px-3 py-1 bg-gray-900 text-white text-xs rounded-lg opacity-0 hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">
        Add Expense (Shift+A)
    </div>
</div>

<script>
// Keyboard shortcut: Shift + A to open modal
document.addEventListener('keydown', function(e) {
    if (e.shiftKey && e.key === 'A') {
        e.preventDefault();
        openQuickAddModal();
    }
});
</script>
