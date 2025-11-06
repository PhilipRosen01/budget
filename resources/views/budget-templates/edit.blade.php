<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Budget Template') }}
            </h2>
            <a href="{{ route('budget-templates.show', $budgetTemplate) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Template
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($budgetTemplate->is_automatic)
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Automatic Template</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>This template was automatically generated based on your salary and preferences. You can edit it, but changes may be overwritten if you regenerate automatic templates.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('budget-templates.update', $budgetTemplate) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Template Name')" />
                            <x-text-input 
                                id="name" 
                                class="block mt-1 w-full" 
                                type="text" 
                                name="name" 
                                :value="old('name', $budgetTemplate->name)" 
                                required 
                                autofocus 
                            />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Amount -->
                        <div class="mb-4">
                            <x-input-label for="amount" :value="__('Monthly Amount')" />
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <x-text-input 
                                    id="amount" 
                                    class="block w-full pl-7" 
                                    type="number" 
                                    name="amount" 
                                    step="0.01"
                                    min="0"
                                    :value="old('amount', $budgetTemplate->amount)" 
                                    required 
                                />
                            </div>
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <x-input-label for="category" :value="__('Category')" />
                            <select id="category" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select a category (optional)</option>
                                <option value="housing" {{ old('category', $budgetTemplate->category) === 'housing' ? 'selected' : '' }}>Housing</option>
                                <option value="transportation" {{ old('category', $budgetTemplate->category) === 'transportation' ? 'selected' : '' }}>Transportation</option>
                                <option value="food" {{ old('category', $budgetTemplate->category) === 'food' ? 'selected' : '' }}>Food</option>
                                <option value="savings" {{ old('category', $budgetTemplate->category) === 'savings' ? 'selected' : '' }}>Savings</option>
                                <option value="insurance" {{ old('category', $budgetTemplate->category) === 'insurance' ? 'selected' : '' }}>Insurance</option>
                                <option value="debt" {{ old('category', $budgetTemplate->category) === 'debt' ? 'selected' : '' }}>Debt Payments</option>
                                <option value="personal" {{ old('category', $budgetTemplate->category) === 'personal' ? 'selected' : '' }}>Personal/Entertainment</option>
                                <option value="utilities" {{ old('category', $budgetTemplate->category) === 'utilities' ? 'selected' : '' }}>Utilities</option>
                                <option value="miscellaneous" {{ old('category', $budgetTemplate->category) === 'miscellaneous' ? 'selected' : '' }}>Miscellaneous</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="3" 
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                placeholder="Optional description of what this budget template covers..."
                            >{{ old('description', $budgetTemplate->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Active Status -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input 
                                    id="is_active" 
                                    name="is_active" 
                                    type="checkbox" 
                                    value="1"
                                    {{ old('is_active', $budgetTemplate->is_active) ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                >
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active Template
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-600">Only active templates will be used to generate monthly budgets.</p>
                        </div>

                        <div class="flex items-center space-x-3">
                            <x-primary-button>
                                {{ __('Update Template') }}
                            </x-primary-button>

                            <a href="{{ route('budget-templates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <!-- Delete Button (separate form) -->
                    <div class="mt-4 flex justify-end">
                        <form action="{{ route('budget-templates.destroy', $budgetTemplate) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this template? This will not delete existing monthly budgets created from this template.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete Template
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Template Usage Information -->
            @if($budgetTemplate->budgets->count() > 0)
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Template Usage</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-600">Monthly budgets created:</span>
                                <span class="text-gray-900">{{ $budgetTemplate->budgets->count() }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Total amount budgeted:</span>
                                <span class="text-gray-900">${{ number_format($budgetTemplate->budgets->sum('amount'), 2) }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Total spent:</span>
                                <span class="text-gray-900">${{ number_format($budgetTemplate->budgets->sum(function($budget) { return $budget->totalSpent(); }), 2) }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Average monthly usage:</span>
                                @php
                                    $totalBudgeted = $budgetTemplate->budgets->sum('amount');
                                    $totalSpent = $budgetTemplate->budgets->sum(function($budget) { return $budget->totalSpent(); });
                                    $averageUsage = $totalBudgeted > 0 ? ($totalSpent / $totalBudgeted) * 100 : 0;
                                @endphp
                                <span class="text-gray-900">{{ number_format($averageUsage, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>