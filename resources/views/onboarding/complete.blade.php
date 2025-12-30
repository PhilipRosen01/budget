<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center py-6 sm:py-12">
        <div class="w-full max-w-2xl px-4 sm:px-6 lg:px-8">
            <!-- Completion Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-8 sm:p-10 text-center">
                    <!-- Success Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Success Content -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        🎉 Setup Complete!
                    </h1>
                    <p class="text-lg text-gray-600 mb-8">
                        Great job! Your account is now personalized and ready to help you manage your finances effectively.
                        We've automatically created budget templates based on your preferences to get you started!
                    </p>

                    <!-- Summary of Setup -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Setup Summary</h3>
                        <div class="space-y-3 text-left">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Monthly Salary:</span>
                                <span class="font-semibold text-green-600">${{ number_format($user->monthly_salary, 2) }}</span>
                            </div>
                            @if($user->budgetPreferences)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Monthly Investment:</span>
                                    <span class="font-semibold text-blue-600">${{ number_format($user->budgetPreferences->monthly_investment_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Available to Spend:</span>
                                    <span class="font-semibold text-purple-600">${{ number_format($user->monthly_salary - $user->budgetPreferences->monthly_investment_amount, 2) }}</span>
                                </div>
                                <hr class="my-3">
                                <div class="text-sm text-gray-600">
                                    <strong>Budget Templates Created:</strong>
                                    @php
                                        $templateCount = $user->budgetTemplates()->where('is_automatic', true)->count();
                                        $autoTemplateCount = $user->budgetTemplates()->where('is_auto_amount', true)->count();
                                    @endphp
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            {{ $templateCount }} templates created
                                        </span>
                                        @if($autoTemplateCount > 0)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 ml-2">
                                                {{ $autoTemplateCount }} with auto-calculation
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- What's Next -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">View Your Dashboard</h3>
                            <p class="text-sm text-gray-500">See your budget overview and start tracking spending</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Budget Templates Created</h3>
                            <p class="text-sm text-gray-500">We've automatically generated budget templates based on your preferences</p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <form method="POST" action="{{ route('onboarding.to-dashboard') }}" class="mb-6">
                        @csrf
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Go to Dashboard
                        </button>
                    </form>

                    <!-- Pro Tips -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
                        <h3 class="text-sm font-medium text-blue-800 mb-2">💡 Pro Tips for Getting Started:</h3>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Your budget templates have been automatically created and are ready to use</li>
                            <li>• Add purchases as you make them to track spending in real-time</li>
                            <li>• Review your monthly spending patterns to optimize your budget</li>
                            <li>• You can always adjust your salary and preferences in account settings</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Support Links -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    Need help getting started? 
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Check out our guide</a> 
                    or visit 
                    <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-800 font-medium">account settings</a>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>