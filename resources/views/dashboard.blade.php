<x-app-layout>
    <x-slot name="header">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        <!-- Simplified Header: Mobile First -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Budget Dashboard
                </h2>
                @if($isCurrentMonth)
                    <p class="text-sm text-gray-500 mt-1">{{ $selectedMonthDisplay }} (Current Month)</p>
                @else
                    <p class="text-sm text-gray-500 mt-1">{{ $selectedMonthDisplay }}</p>
                @endif
            </div>
            
            <!-- Month Selector Only -->
            @if($availableMonths->count() > 0)
                <div class="flex items-center space-x-3">
                    <label for="month-selector" class="text-sm font-medium text-gray-700 hidden sm:block">Month:</label>
                    <form method="GET" action="{{ route('dashboard') }}" class="inline">
                        <select id="month-selector" 
                                name="month-year" 
                                onchange="this.form.submit()" 
                                class="block w-full sm:w-56 px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm shadow-sm">
                            @foreach($availableMonths as $month)
                                <option value="{{ $month['value'] }}" {{ $month['value'] === $selectedValue ? 'selected' : '' }}>
                                    {{ $month['display'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Unified Budget Overview Card -->
            @if($budgetStats['total_budget'] > 0)
                <x-unified-budget-card 
                    :remaining="$budgetStats['remaining']"
                    :totalBudget="$budgetStats['available_budget']"
                    :totalSpent="$budgetStats['total_spent']"
                    :percentageUsed="$budgetStats['percentage_used']"
                    :salary="$budgetStats['total_salary']"
                    :investment="$budgetStats['investment_amount']"
                />
            @endif

            <!-- Progress Bar (kept for compatibility) -->
            @if($budgetStats['total_budget'] > 0 && false)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Budget Progress</span>
                        <span>{{ number_format($budgetStats['percentage_used'], 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-{{ $budgetStats['percentage_used'] > 90 ? 'red' : ($budgetStats['percentage_used'] > 75 ? 'yellow' : 'green') }}-600 h-2.5 rounded-full" style="width: {{ min($budgetStats['percentage_used'], 100) }}%"></div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Empty State: No Budgets - Simplified -->
            @if($monthBudgets->count() == 0)
                <div class="bg-white rounded-xl shadow-lg p-8 sm:p-12 text-center mb-8">
                    <!-- Icon -->
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                         style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    
                    @if(!$hasAnyBudgets)
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Welcome to Your Budget Tracker! 🎉</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            Start tracking your spending in seconds. Create your budget categories and begin adding purchases right away.
                        </p>
                    @else
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">No Budget for {{ $selectedMonthDisplay }}</h3>
                        <p class="text-gray-600 mb-8">Set up your budget to start tracking spending this month.</p>
                    @endif

                    <!-- Primary CTA -->
                    @if($activeTemplates->count() > 0)
                        <a href="{{ route('budgets.setup', ['month' => explode('-', $selectedValue)[0], 'year' => explode('-', $selectedValue)[1]]) }}" 
                           class="inline-flex items-center px-8 py-4 rounded-xl font-bold text-lg text-white transition-all transform hover:scale-105 shadow-lg mb-4"
                           style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Quick Setup (30 seconds)
                        </a>
                        
                        <div class="text-sm text-gray-500">
                            or <a href="{{ route('budget-templates.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">customize templates</a>
                        </div>
                    @else
                        <!-- No Templates - Create First -->
                        <a href="{{ route('budget-templates.create') }}" 
                           class="inline-flex items-center px-8 py-4 rounded-xl font-bold text-lg text-white transition-all transform hover:scale-105 shadow-lg mb-6"
                           style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Your First Budget Category
                        </a>
                        
                        <!-- Quick Guide -->
                        <div class="bg-indigo-50 rounded-xl p-6 max-w-lg mx-auto text-left">
                            <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                <span class="text-xl mr-2">💡</span>
                                Getting Started (3 steps):
                            </h4>
                            <ol class="space-y-2 text-sm text-gray-700">
                                <li class="flex items-start">
                                    <span class="font-bold text-indigo-600 mr-2">1.</span>
                                    <span>Create budget categories (e.g., Groceries, Gas, Entertainment)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold text-indigo-600 mr-2">2.</span>
                                    <span>Set monthly amounts for each category</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-bold text-indigo-600 mr-2">3.</span>
                                    <span>Start adding purchases and track your spending!</span>
                                </li>
                            </ol>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Recent Purchases & Top Categories - Focused on Quick Tracking -->
            @if($monthBudgets->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Purchases - Primary Focus -->
                <div class="lg:col-span-1">
                    <x-recent-purchases :purchases="$recentPurchases" :selectedMonth="$selectedMonthDisplay" />
                </div>

                <!-- Top Categories - Secondary -->
                <div class="lg:col-span-1">
                    <x-top-categories :monthBudgets="$monthBudgets" :month="$selectedMonth" :year="$selectedYear" />
                </div>
            </div>
            @endif

            <!-- Goals Section (Compact) -->
            @if($purchaseGoals->count() > 0)
            <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Goals & Rewards</h3>
                    <a href="{{ route('purchase-goals.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                        View All →
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($purchaseGoals->take(3) as $goal)
                        <div class="p-4 bg-gradient-to-br from-{{ $goal->is_completed ? 'green' : 'purple' }}-50 to-white rounded-xl border border-{{ $goal->is_completed ? 'green' : 'purple' }}-100">
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-gray-900">{{ $goal->name }}</h4>
                                @if($goal->is_completed)
                                    <span class="text-xl">🎉</span>
                                @else
                                    <span class="text-xl">🎯</span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-gray-600 mb-3">
                                <span class="font-bold text-lg text-gray-900">${{ number_format($goal->current_amount, 0) }}</span>
                                <span class="text-xs">of ${{ number_format($goal->target_amount, 0) }}</span>
                            </div>
                            
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                <div class="h-2 rounded-full transition-all duration-500 {{ $goal->is_completed ? 'bg-green-500' : 'bg-gradient-to-r from-indigo-600 to-purple-600' }}"
                                     style="width: {{ min($goal->progress_percentage, 100) }}%"></div>
                            </div>
                            
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500">Priority {{ $goal->priority }}</span>
                                <span class="font-bold {{ $goal->is_completed ? 'text-green-600' : 'text-purple-600' }}">
                                    {{ number_format($goal->progress_percentage, 0) }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Generate Other Month Modal -->
    <div id="generateMonthModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl p-6 w-80">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Generate Budget</h3>
            
            <form method="POST" action="{{ route('budgets.create-from-templates') }}" id="generateMonthForm">
                @csrf
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label for="generate_month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                        <select name="month" id="generate_month" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                            @php
                                $months = [
                                    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
                                    7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
                                ];
                            @endphp
                            @foreach($months as $num => $month)
                                <option value="{{ $num }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="generate_year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <select name="year" id="generate_year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                            @for($year = now()->year; $year <= now()->year + 3; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeGenerateMonthModal()" class="px-3 py-2 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-3 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Generate
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <script>
    function openGenerateMonthModal() {
        // Set default to next month
        const now = new Date();
        let nextMonth = now.getMonth() + 2; // +2 because getMonth() is 0-based and we want next month
        let nextYear = now.getFullYear();
        
        if (nextMonth > 12) {
            nextMonth = 1;
            nextYear++;
        }
        
        document.getElementById('generate_month').value = nextMonth;
        document.getElementById('generate_year').value = nextYear;
        document.getElementById('generateMonthModal').classList.remove('hidden');
    }

    function closeGenerateMonthModal() {
        document.getElementById('generateMonthModal').classList.add('hidden');
    }

    // Validation to ensure only future dates can be selected
    document.getElementById('generateMonthForm').addEventListener('submit', function(e) {
        const selectedMonth = parseInt(document.getElementById('generate_month').value);
        const selectedYear = parseInt(document.getElementById('generate_year').value);
        const now = new Date();
        const currentMonth = now.getMonth() + 1; // Convert to 1-based
        const currentYear = now.getFullYear();
        
        // Check if selected date is in the past
        if (selectedYear < currentYear || (selectedYear === currentYear && selectedMonth <= currentMonth)) {
            e.preventDefault();
            alert('Please select a future month. You can only generate budgets for upcoming months.');
            return false;
        }
    });

    // Close modal when clicking outside of it
    document.getElementById('generateMonthModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeGenerateMonthModal();
        }
    });
    </script>

    <!-- Floating Action Button and Quick Add Modal -->
    @if($monthBudgets->count() > 0)
        <x-fab-button />
        <x-quick-add-modal :monthBudgets="$monthBudgets" />
    @endif
</x-app-layout>
