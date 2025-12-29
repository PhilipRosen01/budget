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
                    <p class="text-sm text-gray-500 mt-1">{{ $selectedMonth }} (Current Month)</p>
                @else
                    <p class="text-sm text-gray-500 mt-1">{{ $selectedMonth }}</p>
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

            <!-- Empty State: No Budgets -->
            @if($monthBudgets->count() == 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-8 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        
                        @if(!$hasAnyBudgets)
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Welcome to Your Budget Dashboard!</h3>
                            <p class="text-gray-600 mb-6">You haven't created any budgets yet. Let's start by setting up your monthly budget for {{ $selectedMonth }}.</p>
                        @else
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Budget for {{ $selectedMonth }}</h3>
                            <p class="text-gray-600 mb-6">You don't have any budgets set up for this month. Create budgets to start tracking your spending.</p>
                        @endif

                        @if($activeTemplates->count() > 0)
                            <div class="flex justify-center">
                                <a href="{{ route('budgets.setup', ['month' => explode('-', $selectedValue)[0], 'year' => explode('-', $selectedValue)[1]]) }}" class="inline-flex items-center px-8 py-4 bg-indigo-600 border border-transparent rounded-lg font-semibold text-lg text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                    Set Up Your Budget
                                </a>
                            </div>

                            <div class="mt-6 text-center">
                                <p class="text-sm text-gray-500 mb-2">Advanced Options:</p>
                                <div class="flex justify-center space-x-4">
                                    <a href="{{ route('budgets.create') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">Create Individual Budget</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('budget-templates.create') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">Create Templates First</a>
                                </div>
                            </div>
                        @endif


                        @if($activeTemplates->count() == 0)
                            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">No Active Templates</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>Create budget templates first to enable auto-population of budgets. <a href="{{ route('budget-templates.create') }}" class="font-medium underline hover:text-yellow-600">Create templates now</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Spending Breakdown by Category -->
            @if($monthBudgets->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Spending Breakdown by Category</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @php
                            $categoryTotals = $monthBudgets->groupBy('category')->map(function($budgets, $category) {
                                return [
                                    'category' => $category ?: 'General',
                                    'budgeted' => $budgets->sum('amount'),
                                    'spent' => $budgets->sum(function($budget) { return $budget->totalSpent(); }),
                                    'count' => $budgets->count()
                                ];
                            })->sortByDesc('budgeted');
                        @endphp
                        
                        @foreach($categoryTotals as $categoryData)
                            @php
                                $percentage = $categoryData['budgeted'] > 0 ? ($categoryData['spent'] / $categoryData['budgeted']) * 100 : 0;
                                $isInvestmentOrSavings = in_array(strtolower($categoryData['category']), ['investments', 'savings']);
                                
                                if ($isInvestmentOrSavings) {
                                    // Investments and savings are always green (good thing to fill up)
                                    $barColor = 'green';
                                } else {
                                    // Other categories: blue → yellow → red as they fill up
                                    if ($percentage >= 100) {
                                        $barColor = 'red';
                                    } elseif ($percentage >= 75) {
                                        $barColor = 'yellow';
                                    } else {
                                        $barColor = 'blue';
                                    }
                                }
                            @endphp
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $categoryData['category'])) }}</h4>
                                    <span class="text-xs text-gray-500">{{ $categoryData['count'] }} budget{{ $categoryData['count'] !== 1 ? 's' : '' }}</span>
                                </div>
                                <div class="text-2xl font-bold text-gray-900 mb-1">
                                    ${{ number_format($categoryData['spent'], 2) }}
                                </div>
                                <div class="text-sm text-gray-600 mb-2">
                                    of ${{ number_format($categoryData['budgeted'], 2) }}
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                    <div class="bg-{{ $barColor }}-500 h-2 rounded-full transition-all duration-300" style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ number_format($percentage, 1) }}% used
                                    @if($categoryData['budgeted'] - $categoryData['spent'] >= 0)
                                        • ${{ number_format($categoryData['budgeted'] - $categoryData['spent'], 2) }} remaining
                                    @else
                                        • ${{ number_format($categoryData['spent'] - $categoryData['budgeted'], 2) }} over budget
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Monthly Comparison Chart -->
            @if($availableMonths->count() > 1)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Monthly Spending Trend</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @php
                            $monthlyStats = $availableMonths->take(4)->map(function($month) use ($user) {
                                $monthBudgets = $user->budgets()->forMonth($month['month'], $month['year'])->get();
                                $totalBudget = $monthBudgets->sum('amount');
                                $totalSpent = $monthBudgets->sum(function($budget) { return $budget->totalSpent(); });
                                return [
                                    'display' => $month['display'],
                                    'budgeted' => $totalBudget,
                                    'spent' => $totalSpent,
                                    'percentage' => $totalBudget > 0 ? ($totalSpent / $totalBudget) * 100 : 0
                                ];
                            });
                        @endphp
                        
                        @foreach($monthlyStats as $monthStat)
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-900 mb-2">{{ $monthStat['display'] }}</div>
                                <div class="relative pt-1">
                                    <div class="flex mb-2 items-center justify-between">
                                        <div>
                                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-{{ $monthStat['percentage'] > 90 ? 'red' : ($monthStat['percentage'] > 75 ? 'yellow' : 'green') }}-600 bg-{{ $monthStat['percentage'] > 90 ? 'red' : ($monthStat['percentage'] > 75 ? 'yellow' : 'green') }}-200">
                                                {{ number_format($monthStat['percentage'], 0) }}%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                                        <div style="width:{{ min($monthStat['percentage'], 100) }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-{{ $monthStat['percentage'] > 90 ? 'red' : ($monthStat['percentage'] > 75 ? 'yellow' : 'green') }}-500"></div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600">
                                    ${{ number_format($monthStat['spent'], 0) }} / ${{ number_format($monthStat['budgeted'], 0) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Current Month Budgets, Recent Purchases, and Purchase Goals -->
            @if($monthBudgets->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Current Month Budgets -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $selectedMonth }} Budgets</h3>
                            <a href="{{ route('budget-templates.index') }}" class="text-sm text-blue-600 hover:text-blue-900">Manage Templates</a>
                        </div>
                        @if($monthBudgets->count() > 0)
                            <div class="space-y-4">
                                @foreach($monthBudgets as $budget)
                                <div class="border-l-4 border-blue-400 pl-4">
                                    <div class="flex justify-between">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $budget->name }}</h4>
                                        @if($budget->category)
                                            <span class="text-sm text-gray-500">{{ $budget->category }}</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        ${{ number_format($budget->totalSpent(), 2) }} / ${{ number_format($budget->amount, 2) }}
                                    </p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                        @php
                                            $percentage = $budget->percentageUsed();
                                            $isInvestmentOrSavings = in_array(strtolower($budget->category), ['investments', 'savings']);
                                            
                                            if ($percentage >= 100) {
                                                $color = $isInvestmentOrSavings ? 'green' : 'red';
                                            } elseif ($percentage >= 75) {
                                                $color = 'yellow';
                                            } else {
                                                $color = 'blue';
                                            }
                                        @endphp
                                        <div class="bg-{{ $color }}-600 h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-500 mb-2">No budgets for {{ $selectedMonth }}</p>
                                <a href="{{ route('budget-templates.create') }}" class="text-blue-600 hover:text-blue-900 text-sm">Create Budget Template</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Purchases -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Purchases</h3>
                        @if($recentPurchases->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentPurchases as $purchase)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $purchase->name }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ $purchase->purchase_date->format('M j, Y') }}
                                            @if($purchase->category)
                                                • {{ $purchase->category }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">${{ number_format($purchase->amount, 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('purchases.index') }}" class="text-sm text-blue-600 hover:text-blue-900">View all purchases →</a>
                            </div>
                        @else
                            <p class="text-gray-500">No purchases yet. <a href="{{ route('purchases.create') }}" class="text-blue-600 hover:text-blue-900">Add your first purchase</a></p>
                        @endif
                    </div>
                </div>

                <!-- Purchase Goals & Rewards -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Goals & Rewards</h3>
                            <a href="{{ route('purchase-goals.index') }}" class="text-sm text-blue-600 hover:text-blue-900">View All</a>
                        </div>
                        @if($purchaseGoals->count() > 0)
                            <div class="space-y-4">
                                @foreach($purchaseGoals as $goal)
                                <div class="border-l-4 border-{{ $goal->is_completed ? 'green' : 'purple' }}-400 pl-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $goal->name }}</h4>
                                            <p class="text-xs text-gray-500 mb-2">
                                                Priority {{ $goal->priority }} • 
                                                ${{ number_format($goal->current_amount, 0) }} / ${{ number_format($goal->target_amount, 0) }}
                                            </p>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-{{ $goal->is_completed ? 'green' : 'purple' }}-600 h-1.5 rounded-full" 
                                                     style="width: {{ $goal->progress_percentage }}%"></div>
                                            </div>
                                        </div>
                                        @if($goal->is_completed)
                                            <span class="text-xs text-green-600 font-medium ml-2">✓ Done</span>
                                        @else
                                            <span class="text-xs text-purple-600 font-medium ml-2">{{ number_format($goal->progress_percentage, 0) }}%</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('purchase-goals.index') }}" class="text-sm text-blue-600 hover:text-blue-900">Manage all goals →</a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="text-gray-400 mb-2">
                                    <svg class="mx-auto h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm mb-2">No purchase goals yet</p>
                                <a href="{{ route('purchase-goals.create') }}" class="text-blue-600 hover:text-blue-900 text-sm">Create your first goal</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

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
