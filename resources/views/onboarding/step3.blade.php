<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-100 flex items-center justify-center py-6 sm:py-12">
        <div class="w-full max-w-3xl px-4 sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center">
                    <div class="flex items-center text-sm text-green-600 mr-4">
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold mr-2">✓</div>
                        <span class="font-medium">Salary</span>
                    </div>
                    <div class="flex items-center text-sm text-green-600 mr-4">
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold mr-2">✓</div>
                        <span class="font-medium">Investment</span>
                    </div>
                    <div class="flex items-center text-sm text-purple-600">
                        <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-2">3</div>
                        <span class="font-medium">Expenses</span>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            Expenses You Don't Pay For
                        </h1>
                        <p class="text-lg text-gray-600 mb-2">
                            Select any expenses that don't apply to your lifestyle.
                        </p>
                        <p class="text-sm text-gray-500">
                            This helps us create more accurate budget templates for you by excluding categories you don't need.
                        </p>
                    </div>

                    <!-- Budget Summary -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Budget Summary</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                            <div class="bg-white rounded-lg p-4">
                                <div class="text-2xl font-bold text-green-600">${{ number_format($salary, 2) }}</div>
                                <div class="text-sm text-gray-500">Monthly Salary</div>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <div class="text-2xl font-bold text-blue-600">${{ number_format($investment, 2) }}</div>
                                <div class="text-sm text-gray-500">Monthly Investment</div>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <div class="text-2xl font-bold text-purple-600">${{ number_format($salary - $investment, 2) }}</div>
                                <div class="text-sm text-gray-500">Available to Spend</div>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('onboarding.process-step3') }}" class="space-y-6">
                        @csrf

                        <!-- Expense Categories -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Select expenses you DON'T pay for:
                            </h3>
                            <p class="text-sm text-gray-500 mb-6">
                                Check all that apply to your situation. These categories won't appear in your automatic budget templates.
                            </p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Housing -->
                                <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           name="no_rent" 
                                           id="no_rent" 
                                           value="1"
                                           {{ old('no_rent', $preferences->no_rent ?? false) ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <div class="flex-1">
                                        <label for="no_rent" class="text-sm font-medium text-gray-900 cursor-pointer">
                                            🏠 Rent/Mortgage
                                        </label>
                                        <p class="text-sm text-gray-500">Live with family, own home outright, or rent is covered</p>
                                    </div>
                                </div>

                                <!-- Transportation -->
                                <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           name="no_car_payment" 
                                           id="no_car_payment" 
                                           value="1"
                                           {{ old('no_car_payment', $preferences->no_car_payment ?? false) ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <div class="flex-1">
                                        <label for="no_car_payment" class="text-sm font-medium text-gray-900 cursor-pointer">
                                            🚗 Car Payment
                                        </label>
                                        <p class="text-sm text-gray-500">Car is paid off, use public transit, or walk/bike everywhere</p>
                                    </div>
                                </div>

                                <!-- Insurance -->
                                <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           name="no_insurance" 
                                           id="no_insurance" 
                                           value="1"
                                           {{ old('no_insurance', $preferences->no_insurance ?? false) ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <div class="flex-1">
                                        <label for="no_insurance" class="text-sm font-medium text-gray-900 cursor-pointer">
                                            🛡️ Insurance
                                        </label>
                                        <p class="text-sm text-gray-500">Covered by employer, family plan, or government</p>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           name="no_phone_bill" 
                                           id="no_phone_bill" 
                                           value="1"
                                           {{ old('no_phone_bill', $preferences->no_phone_bill ?? false) ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <div class="flex-1">
                                        <label for="no_phone_bill" class="text-sm font-medium text-gray-900 cursor-pointer">
                                            📱 Phone Bill
                                        </label>
                                        <p class="text-sm text-gray-500">Family plan, work provides, or don't have a phone</p>
                                    </div>
                                </div>

                                <!-- Internet -->
                                <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           name="no_internet" 
                                           id="no_internet" 
                                           value="1"
                                           {{ old('no_internet', $preferences->no_internet ?? false) ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <div class="flex-1">
                                        <label for="no_internet" class="text-sm font-medium text-gray-900 cursor-pointer">
                                            🌐 Internet
                                        </label>
                                        <p class="text-sm text-gray-500">Included in rent, use mobile data only, or covered by work</p>
                                    </div>
                                </div>

                                <!-- Utilities -->
                                <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           name="no_utilities" 
                                           id="no_utilities" 
                                           value="1"
                                           {{ old('no_utilities', $preferences->no_utilities ?? false) ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <div class="flex-1">
                                        <label for="no_utilities" class="text-sm font-medium text-gray-900 cursor-pointer">
                                            💡 Utilities
                                        </label>
                                        <p class="text-sm text-gray-500">Electricity, water, gas included in rent or not applicable</p>
                                    </div>
                                </div>

                                <!-- Debt Payments -->
                                <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           name="no_debt" 
                                           id="no_debt" 
                                           value="1"
                                           {{ old('no_debt', $preferences->no_debt ?? false) ? 'checked' : '' }}
                                           class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <div class="flex-1">
                                        <label for="no_debt" class="text-sm font-medium text-gray-900 cursor-pointer">
                                            💳 Debt Payments
                                        </label>
                                        <p class="text-sm text-gray-500">No credit cards, loans, or other debt payments</p>
                                    </div>
                                </div>

                                <!-- Select All/None -->
                                <div class="col-span-full pt-4 border-t border-gray-200">
                                    <div class="flex space-x-4">
                                        <button type="button" onclick="selectAll(true)" class="text-sm text-purple-600 hover:text-purple-800 font-medium">Select All</button>
                                        <button type="button" onclick="selectAll(false)" class="text-sm text-gray-600 hover:text-gray-800 font-medium">Select None</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Information Box -->
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-purple-800">What happens next?</h3>
                                    <div class="mt-2 text-sm text-purple-700">
                                        <p>Based on your selections, we'll create personalized budget templates that only include expenses relevant to your lifestyle. You can always modify these settings later in your account preferences.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between space-y-3 sm:space-y-0 sm:space-x-4 pt-6">
                            <a href="{{ route('onboarding.step2') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                </svg>
                                Back
                            </a>
                            
                            <button type="submit" 
                                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-8 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition ease-in-out duration-150">
                                Complete Setup
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectAll(select) {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = select;
            });
        }
    </script>
</x-app-layout>