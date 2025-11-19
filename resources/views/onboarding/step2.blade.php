<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-6 sm:py-12">
        <div class="w-full max-w-2xl px-4 sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex items-center text-sm text-green-600 mr-4">
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold mr-2">✓</div>
                        <span class="font-medium">Salary</span>
                    </div>
                    <div class="flex items-center text-sm text-blue-600">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-2">2</div>
                        <span class="font-medium">Investment</span>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div class="h-2 bg-blue-600 rounded-full" style="width: 66%"></div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">Step 2 of 3</span>
                </div>
            </div>

            <!-- Setup Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-8 sm:p-10">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            Investment & Savings Goals
                        </h1>
                        <p class="text-lg text-gray-600 mb-2">
                            How much do you want to invest or save each month?
                        </p>
                        <p class="text-sm text-gray-500">
                            This amount will be automatically set aside from your monthly salary: <strong>${{ number_format($salary, 2) }}</strong>
                        </p>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('onboarding.process-step2') }}" class="space-y-6">
                        @csrf

                        <!-- Investment Amount -->
                        <div>
                            <label for="monthly_investment_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Monthly Investment/Savings Amount
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-lg">$</span>
                                </div>
                                <input type="number" 
                                       name="monthly_investment_amount" 
                                       id="monthly_investment_amount" 
                                       step="0.01" 
                                       min="0" 
                                       max="{{ $salary }}"
                                       value="{{ old('monthly_investment_amount', $currentInvestment) }}"
                                       class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg" 
                                       placeholder="500.00"
                                       required>
                            </div>
                            @error('monthly_investment_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Quick percentage buttons -->
                            <div class="mt-3">
                                <p class="text-sm text-gray-500 mb-2">Quick select:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach([10, 15, 20, 25] as $percentage)
                                        <button type="button" 
                                                onclick="setInvestmentPercentage({{ $percentage }})"
                                                class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            {{ $percentage }}% ({{ number_format($salary * $percentage / 100, 0) }})
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Available for spending display -->
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Monthly Salary:</span>
                                    <span class="font-medium">${{ number_format($salary, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Investment Amount:</span>
                                    <span class="font-medium" id="investment-display">$0.00</span>
                                </div>
                                <hr class="my-2">
                                <div class="flex justify-between text-sm font-bold">
                                    <span class="text-blue-600">Available for Spending:</span>
                                    <span class="text-blue-600" id="available-display">${{ number_format($salary, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Auto-invest toggle -->
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" 
                                   name="auto_invest_enabled" 
                                   id="auto_invest_enabled" 
                                   value="1"
                                   {{ old('auto_invest_enabled', $autoInvestEnabled) ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <div class="flex-1">
                                <label for="auto_invest_enabled" class="text-sm font-medium text-gray-700">
                                    Enable automatic investment tracking
                                </label>
                                <p class="text-sm text-gray-500">
                                    When enabled, this amount will be automatically deducted from your available budget each month and tracked as an investment category.
                                </p>
                            </div>
                        </div>

                        <!-- Investment Tips -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Investment Tips:</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li><strong>20% Rule:</strong> Many financial experts recommend investing 10-20% of income</li>
                                            <li><strong>Start Small:</strong> Even $50/month adds up to $600+ per year</li>
                                            <li><strong>Emergency Fund:</strong> Consider saving 3-6 months of expenses first</li>
                                            <li><strong>Flexibility:</strong> You can adjust this amount anytime in settings</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between space-y-3 sm:space-y-0 sm:space-x-4 pt-6">
                            <a href="{{ route('onboarding.step1') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                </svg>
                                Back
                            </a>
                            
                            <button type="submit" 
                                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-8 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                Continue to Expense Setup
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const salaryAmount = {{ $salary }};
        const investmentInput = document.getElementById('monthly_investment_amount');
        const investmentDisplay = document.getElementById('investment-display');
        const availableDisplay = document.getElementById('available-display');
        
        function updateDisplays() {
            const investment = parseFloat(investmentInput.value) || 0;
            const available = salaryAmount - investment;
            
            investmentDisplay.textContent = '$' + investment.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            availableDisplay.textContent = '$' + available.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            // Color coding
            if (available < 0) {
                availableDisplay.className = 'text-red-600 font-bold';
            } else {
                availableDisplay.className = 'text-blue-600 font-bold';
            }
        }
        
        function setInvestmentPercentage(percentage) {
            const amount = (salaryAmount * percentage / 100).toFixed(2);
            investmentInput.value = amount;
            updateDisplays();
        }
        
        investmentInput.addEventListener('input', updateDisplays);
        
        // Initialize displays
        updateDisplays();
    </script>
</x-app-layout>