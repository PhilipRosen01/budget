<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-6 sm:py-12">
        <div class="w-full max-w-2xl px-4 sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-8 sm:p-10">
                    <!-- Welcome Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Welcome Content -->
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            Welcome to Budget Tracker! 🎉
                        </h1>
                        <p class="text-lg text-gray-600 mb-6">
                            Let's get your account set up so you can start managing your finances effectively.
                        </p>
                        <p class="text-sm text-gray-500">
                            This quick 3-step setup will customize your budget tracking experience based on your personal financial situation.
                        </p>
                    </div>

                    <!-- Setup Steps Preview -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-green-600 font-bold">1</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Monthly Salary</h3>
                            <p class="text-sm text-gray-500">Set your monthly income</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-blue-600 font-bold">2</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Investment Goals</h3>
                            <p class="text-sm text-gray-500">Define your savings plan</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-purple-600 font-bold">3</span>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Expense Preferences</h3>
                            <p class="text-sm text-gray-500">Customize your budget categories</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('onboarding.step1') }}" 
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            Get Started
                        </a>
                        
                        <a href="{{ route('dashboard') }}" 
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                            Skip Setup
                        </a>
                    </div>

                    <!-- Setup Time Estimate -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500">
                            ⏱️ Takes about 2-3 minutes to complete
                        </p>
                    </div>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Personalized Experience</h3>
                            <p class="mt-1 text-sm text-gray-500">Get budget recommendations tailored to your lifestyle and financial goals.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Smart Automation</h3>
                            <p class="mt-1 text-sm text-gray-500">Automatically generate monthly budgets based on your preferences.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>