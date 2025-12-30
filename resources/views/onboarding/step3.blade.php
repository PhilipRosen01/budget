<x-app-layout>
    <div x-data="{
        selectedCategories: [],
        defaultCategories: @js(config('budget_categories')),
        salary: {{ $salary }},
        investment: {{ $investment }},
        availableBudget: {{ $salary - $investment }},
        
        get needsTotal() {
            return this.getCategoryTotal('needs');
        },
        
        get wantsTotal() {
            return this.getCategoryTotal('wants');
        },
        
        get savingsTotal() {
            return this.getCategoryTotal('savings');
        },
        
        getCategoryTotal(group) {
            let total = 0;
            this.selectedCategories.forEach(catName => {
                const category = this.defaultCategories.find(c => c.name === catName);
                if (category && category.group === group) {
                    total += (this.availableBudget * category.default_percentage) / 100;
                }
            });
            return total;
        },
        
        get grandTotal() {
            return this.needsTotal + this.wantsTotal + this.savingsTotal;
        },
        
        toggleCategory(categoryName) {
            const index = this.selectedCategories.indexOf(categoryName);
            if (index > -1) {
                this.selectedCategories.splice(index, 1);
            } else {
                this.selectedCategories.push(categoryName);
            }
        },
        
        isCategorySelected(categoryName) {
            return this.selectedCategories.includes(categoryName);
        },
        
        getCategoryAmount(categoryName) {
            const category = this.defaultCategories.find(c => c.name === categoryName);
            if (category) {
                return (this.availableBudget * category.default_percentage) / 100;
            }
            return 0;
        },
        
        selectAllInGroup(group) {
            this.defaultCategories.forEach(category => {
                if (category.group === group && !this.selectedCategories.includes(category.name)) {
                    this.selectedCategories.push(category.name);
                }
            });
        },
        
        deselectAllInGroup(group) {
            this.selectedCategories = this.selectedCategories.filter(catName => {
                const category = this.defaultCategories.find(c => c.name === catName);
                return !category || category.group !== group;
            });
        }
    }" 
    class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-100 py-6 sm:py-12">
        <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex items-center text-sm text-green-600 mr-4">
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold mr-2">✓</div>
                        <span class="font-medium hidden sm:inline">Salary</span>
                    </div>
                    <div class="flex items-center text-sm text-green-600 mr-4">
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold mr-2">✓</div>
                        <span class="font-medium hidden sm:inline">Investment</span>
                    </div>
                    <div class="flex items-center text-sm text-purple-600">
                        <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-2">3</div>
                        <span class="font-medium hidden sm:inline">Categories</span>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div class="h-2 bg-purple-600 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">Step 3 of 3</span>
                </div>
            </div>

            <!-- Setup Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-8 sm:p-10">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            Choose Your Budget Categories
                        </h1>
                        <p class="text-lg text-gray-600 mb-2">
                            Select the budget categories that apply to your lifestyle. We'll automatically calculate recommended amounts based on the <strong>50/30/20 rule</strong>.
                        </p>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4 text-sm text-blue-800">
                            <strong>50/30/20 Rule:</strong> Spend 50% on needs, 30% on wants, and 20% on savings & debt repayment.
                        </div>
                    </div>

                    <!-- Budget Summary -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-2xl font-bold text-green-600">$<span x-text="salary.toFixed(2)"></span></div>
                                <div class="text-sm text-gray-500">Monthly Salary</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-2xl font-bold text-blue-600">$<span x-text="investment.toFixed(2)"></span></div>
                                <div class="text-sm text-gray-500">Investment</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-2xl font-bold text-purple-600">$<span x-text="availableBudget.toFixed(2)"></span></div>
                                <div class="text-sm text-gray-500">Available Budget</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm border-2 border-green-500">
                                <div class="text-2xl font-bold text-green-600">$<span x-text="grandTotal.toFixed(2)"></span></div>
                                <div class="text-sm text-gray-500">Total Selected</div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Selection Form -->
                    <form method="POST" action="{{ route('onboarding.process-step3') }}">
                        @csrf
                        
                        <!-- Hidden field to store selected categories -->
                        <input type="hidden" name="selected_categories" :value="JSON.stringify(selectedCategories)">

                        <!-- Needs (50%) -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">🏠 Needs (50% - Essential expenses)</h3>
                                    <p class="text-sm text-gray-500">Housing, transportation, groceries, utilities, insurance</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-blue-600">$<span x-text="needsTotal.toFixed(2)"></span></div>
                                    <div class="text-sm text-gray-500">Selected</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <template x-for="category in defaultCategories.filter(c => c.group === 'needs')" :key="category.name">
                                    <div @click="toggleCategory(category.name)" 
                                         :class="isCategorySelected(category.name) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50'"
                                         class="cursor-pointer p-4 border-2 rounded-lg transition-all">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex items-center">
                                                <input type="checkbox" 
                                                       :checked="isCategorySelected(category.name)"
                                                       @click.stop
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mr-2">
                                                <span class="font-medium text-gray-900" x-text="category.name"></span>
                                            </div>
                                            <span class="text-sm font-bold text-blue-600" x-text="category.default_percentage + '%'"></span>
                                        </div>
                                        <p class="text-sm text-gray-500 mb-2" x-text="category.description"></p>
                                        <div class="text-sm font-semibold text-green-600">
                                            $<span x-text="getCategoryAmount(category.name).toFixed(2)"></span>/month
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Wants (30%) -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">✨ Wants (30% - Lifestyle & entertainment)</h3>
                                    <p class="text-sm text-gray-500">Dining out, entertainment, hobbies, personal care</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-purple-600">$<span x-text="wantsTotal.toFixed(2)"></span></div>
                                    <div class="text-sm text-gray-500">Selected</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <template x-for="category in defaultCategories.filter(c => c.group === 'wants')" :key="category.name">
                                    <div @click="toggleCategory(category.name)" 
                                         :class="isCategorySelected(category.name) ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:bg-gray-50'"
                                         class="cursor-pointer p-4 border-2 rounded-lg transition-all">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex items-center">
                                                <input type="checkbox" 
                                                       :checked="isCategorySelected(category.name)"
                                                       @click.stop
                                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mr-2">
                                                <span class="font-medium text-gray-900" x-text="category.name"></span>
                                            </div>
                                            <span class="text-sm font-bold text-purple-600" x-text="category.default_percentage + '%'"></span>
                                        </div>
                                        <p class="text-sm text-gray-500 mb-2" x-text="category.description"></p>
                                        <div class="text-sm font-semibold text-green-600">
                                            $<span x-text="getCategoryAmount(category.name).toFixed(2)"></span>/month
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Savings & Debt (20%) -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">💰 Savings & Debt (20% - Future security)</h3>
                                    <p class="text-sm text-gray-500">Emergency fund, investments, debt payments</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-green-600">$<span x-text="savingsTotal.toFixed(2)"></span></div>
                                    <div class="text-sm text-gray-500">Selected</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <template x-for="category in defaultCategories.filter(c => c.group === 'savings')" :key="category.name">
                                    <div @click="toggleCategory(category.name)" 
                                         :class="isCategorySelected(category.name) ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:bg-gray-50'"
                                         class="cursor-pointer p-4 border-2 rounded-lg transition-all">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex items-center">
                                                <input type="checkbox" 
                                                       :checked="isCategorySelected(category.name)"
                                                       @click.stop
                                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded mr-2">
                                                <span class="font-medium text-gray-900" x-text="category.name"></span>
                                            </div>
                                            <span class="text-sm font-bold text-green-600" x-text="category.default_percentage + '%'"></span>
                                        </div>
                                        <p class="text-sm text-gray-500 mb-2" x-text="category.description"></p>
                                        <div class="text-sm font-semibold text-green-600">
                                            $<span x-text="getCategoryAmount(category.name).toFixed(2)"></span>/month
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 pt-8 border-t border-gray-200">
                            <a href="{{ route('onboarding.step2') }}" 
                               class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </a>
                            
                            <button type="submit" 
                                    x-bind:disabled="selectedCategories.length === 0"
                                    x-bind:class="selectedCategories.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-purple-700'"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                                <span x-show="selectedCategories.length > 0">
                                    Complete Setup & Create <span x-text="selectedCategories.length"></span> Templates
                                </span>
                                <span x-show="selectedCategories.length === 0">
                                    Select at least one category
                                </span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Text -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>💡 <strong>Tip:</strong> Don't worry about getting this perfect! You can always add, edit, or remove budget templates later from your dashboard.</p>
            </div>
        </div>
    </div>
</x-app-layout>
