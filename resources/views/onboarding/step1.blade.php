<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center py-6 sm:py-12">
        <div class="w-full max-w-2xl px-4 sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex items-center text-sm text-green-600">
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold mr-2">1</div>
                        <span class="font-medium">Monthly Salary</span>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div class="h-2 bg-green-600 rounded-full" style="width: 33%"></div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">Step 1 of 3</span>
                </div>
            </div>

            <!-- Setup Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-8 sm:p-10">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            What's Your Monthly Salary?
                        </h1>
                        <p class="text-lg text-gray-600 mb-2">
                            Enter your monthly take-home pay after taxes.
                        </p>
                        <p class="text-sm text-gray-500">
                            This helps us create personalized budget recommendations and track your spending patterns.
                        </p>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('onboarding.process-step1') }}" class="space-y-6">
                        @csrf

                        <!-- Salary Input -->
                        <div>
                            <label for="monthly_salary" class="block text-sm font-medium text-gray-700 mb-2">
                                Monthly Take-Home Pay ($)
                            </label>
                            <input type="number" 
                                   name="monthly_salary" 
                                   id="monthly_salary" 
                                   step="0.01" 
                                   min="0" 
                                   max="999999.99"
                                   value="{{ old('monthly_salary', $currentSalary) }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-lg @error('monthly_salary') border-red-300 @enderror" 
                                   placeholder="Enter amount (e.g., 3000.00)"
                                   required>
                            @error('monthly_salary')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">
                                💡 Include all sources of regular monthly income (salary, freelance, etc.)
                            </p>
                        </div>

                        <!-- Helpful Tips -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Why we need this information:</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>Calculate how much you can safely invest each month</li>
                                            <li>Create realistic budget categories based on your income</li>
                                            <li>Track your spending as a percentage of income</li>
                                            <li>Provide personalized financial recommendations</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Notice -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-600">
                                        <strong>Privacy:</strong> Your salary information is stored securely and is only used to personalize your budget experience. We never share your financial data.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between space-y-3 sm:space-y-0 sm:space-x-4 pt-6">
                            <a href="{{ route('onboarding.index') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                </svg>
                                Back
                            </a>
                            
                            <button type="submit" 
                                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-8 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150">
                                Continue to Investment Setup
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
</x-app-layout>