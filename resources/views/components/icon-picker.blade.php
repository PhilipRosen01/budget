@props(['selected' => null, 'name' => 'icon'])

<div x-data="{ 
    open: false, 
    selected: '{{ $selected ?? '💰' }}',
    icons: [
        { emoji: '💰', label: 'Money/Savings' },
        { emoji: '🍔', label: 'Food' },
        { emoji: '🛒', label: 'Groceries' },
        { emoji: '🍽️', label: 'Dining Out' },
        { emoji: '🚗', label: 'Transportation' },
        { emoji: '⛽', label: 'Gas/Fuel' },
        { emoji: '💡', label: 'Utilities' },
        { emoji: '🎬', label: 'Entertainment' },
        { emoji: '🛍️', label: 'Shopping' },
        { emoji: '🏥', label: 'Healthcare' },
        { emoji: '💪', label: 'Fitness/Gym' },
        { emoji: '📚', label: 'Education' },
        { emoji: '✈️', label: 'Travel' },
        { emoji: '🏠', label: 'Rent/Housing' },
        { emoji: '🛡️', label: 'Insurance' },
        { emoji: '📈', label: 'Investments' },
        { emoji: '📱', label: 'Phone' },
        { emoji: '🌐', label: 'Internet' },
        { emoji: '👕', label: 'Clothing' },
        { emoji: '👤', label: 'Personal Care' },
        { emoji: '🐾', label: 'Pets' },
        { emoji: '🎁', label: 'Gifts' },
        { emoji: '📺', label: 'Subscriptions' },
        { emoji: '🎮', label: 'Gaming' },
        { emoji: '☕', label: 'Coffee' },
        { emoji: '🏋️', label: 'Sports' },
        { emoji: '🎵', label: 'Music' },
        { emoji: '🎨', label: 'Hobbies' },
        { emoji: '🔧', label: 'Repairs' },
        { emoji: '📦', label: 'Miscellaneous' }
    ]
}" 
@click.away="open = false"
class="relative">
    <!-- Selected Icon Display -->
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Category Icon
    </label>
    
    <button 
        type="button"
        @click="open = !open"
        class="w-full flex items-center justify-between px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm hover:border-indigo-500 dark:hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all"
    >
        <span class="flex items-center gap-3">
            <span class="text-2xl" x-text="selected">💰</span>
            <span class="text-sm text-gray-700 dark:text-gray-300">
                Click to choose icon
            </span>
        </span>
        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Hidden Input -->
    <input type="hidden" name="{{ $name }}" :value="selected">

    <!-- Icon Grid Dropdown -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-2 w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden"
        style="display: none;"
    >
        <div class="p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                Select an icon for your budget category
            </p>
            
            <!-- Icon Grid -->
            <div class="grid grid-cols-6 gap-2 max-h-64 overflow-y-auto">
                <template x-for="icon in icons" :key="icon.emoji">
                    <button
                        type="button"
                        @click="selected = icon.emoji; open = false"
                        :title="icon.label"
                        class="flex items-center justify-center p-3 text-2xl rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors border-2"
                        :class="selected === icon.emoji ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-transparent'"
                    >
                        <span x-text="icon.emoji"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
