<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Budget History ⚠️ UPDATED VERSION - DEC 29 2025 ⚠️
                </h2>
                <p class="text-sm text-gray-500 mt-1">Overview of your monthly budgets and spending</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- ALPINE.JS TEST - Should show "Alpine Works!" if Alpine is loaded -->
                <div x-data="{ test: 'Alpine Works!' }" class="bg-green-500 text-white px-4 py-2 rounded font-bold">
                    <span x-text="test"></span>
                </div>
                
                <a href="{{ route('budget-templates.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Manage Templates
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{
            selectionMode: false,
            selectedMonths: [],
            init() {
                console.log('Alpine.js initialized on budgets index page');
            },
            toggleSelectionMode() {
                this.selectionMode = !this.selectionMode;
                if (!this.selectionMode) {
                    this.selectedMonths = [];
                }
            },
            toggleMonth(monthKey) {
                if (this.selectedMonths.includes(monthKey)) {
                    this.selectedMonths = this.selectedMonths.filter(m => m !== monthKey);
                } else {
                    this.selectedMonths.push(monthKey);
                }
            },
            selectAll() {
                this.selectedMonths = @json(collect($monthlyStats)->map(function($stat) { return $stat['month'] . '-' . $stat['year']; })->toArray());
            },
            deselectAll() {
                this.selectedMonths = [];
            },
            async deleteSelected() {
                if (this.selectedMonths.length === 0) {
                    alert('Please select at least one month to delete');
                    return;
                }
                
                if (!confirm(`Are you sure you want to delete ${this.selectedMonths.length} month(s) of budgets? This will delete all budgets within these months and cannot be undone.`)) {
                    return;
                }
                
                try {
                    const response = await fetch('{{ route('budgets.bulk-delete-months') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ months: this.selectedMonths })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error deleting months: ' + (data.message || 'Unknown error'));
                    }
                } catch (error) {
                    alert('Error deleting months: ' + error.message);
                }
            }
        }">
            <!-- Selection Mode Actions Bar -->
            <div x-show="selectionMode" class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Bulk Actions</h3>
                <div class="flex flex-wrap gap-3 items-center justify-between">
                    <div class="flex flex-wrap gap-2">
                        <button @click="selectAll()" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition shadow">
                            Select All
                        </button>
                        <button @click="deselectAll()" class="px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition shadow">
                            Deselect All
                        </button>
                        <button @click="deleteSelected()" class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition shadow">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Selected
                        </button>
                    </div>
                    <div class="text-sm font-semibold text-gray-700 bg-white px-4 py-2 rounded-lg shadow">
                        <span x-text="selectedMonths.length"></span> month(s) selected
                    </div>
                </div>
            </div>
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- STATIC TEST BUTTON - Should ALWAYS be visible -->
            <div class="bg-red-500 text-white p-6 rounded-lg mb-6 text-center">
                <h2 class="text-2xl font-bold mb-2">STATIC TEST - If you see this, the file is loading correctly</h2>
                <button onclick="alert('Button works!')" class="bg-white text-red-500 px-6 py-3 rounded-lg font-bold text-lg">
                    CLICK ME - Static Test Button
                </button>
            </div>

            <!-- Information Box -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded">
                <!-- Debug: Alpine State Display -->
                <div class="mb-2 text-xs text-gray-600" x-show="true">
                    <span>Alpine.js Active | Selection Mode: </span>
                    <span x-text="selectionMode ? 'ON' : 'OFF'" class="font-bold"></span>
                    <span> | Selected: </span>
                    <span x-text="selectedMonths.length" class="font-bold"></span>
                </div>
                
                <div class="flex justify-between items-start">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Budget History Overview</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>This page shows your financial overview for each month. Click on any month card to view detailed budgets and track spending for that specific month.</p>
                            </div>
                        </div>
                    </div>
                    <button @click="toggleSelectionMode()" 
                            class="ml-4 px-4 py-2 text-sm font-semibold rounded-lg transition-all shadow-sm border-2"
                            :class="selectionMode ? 'bg-red-500 text-white border-red-600 hover:bg-red-600' : 'bg-blue-500 text-white border-blue-600 hover:bg-blue-600'">
                        <span x-text="selectionMode ? 'Cancel Selection' : 'Select Months'"></span>
                    </button>
                </div>
            </div>

            @if(count($monthlyStats) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($monthlyStats as $stats)
                        @php
                            $monthKey = $stats['month'] . '-' . $stats['year'];
                        @endphp
                        <div class="relative" x-data="{}">
                            <!-- Selection Checkbox -->
                            <div x-show="selectionMode" 
                                 class="absolute top-6 left-6 z-10 p-2 bg-white rounded-lg shadow-md border-2 border-blue-300">
                                <input type="checkbox" 
                                       :checked="selectedMonths.includes('{{ $monthKey }}')"
                                       @click.stop="toggleMonth('{{ $monthKey }}')"
                                       class="w-6 h-6 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                            </div>
                            
                            <a href="{{ route('budgets.month', ['month' => $stats['month'], 'year' => $stats['year']]) }}" 
                               :class="selectionMode ? 'pointer-events-none' : ''"
                               class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                                <div class="p-6" :class="selectionMode ? 'pl-20 pt-12' : ''">
                                <!-- Month Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['display'] }}</h3>
                                        <p class="text-sm text-gray-500">{{ $stats['budget_count'] }} budget{{ $stats['budget_count'] !== 1 ? 's' : '' }}</p>
                                    </div>
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>

                                <!-- Financial Summary -->
                                <div class="space-y-3 mb-4">
                                    <!-- Monthly Salary -->
                                    @if($stats['monthly_salary'] > 0)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Monthly Income:</span>
                                            <span class="text-lg font-semibold text-green-600">${{ number_format($stats['monthly_salary'], 2) }}</span>
                                        </div>
                                    @endif

                                    <!-- Total Budgeted -->
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Total Budgeted:</span>
                                        <span class="text-lg font-semibold text-blue-600">${{ number_format($stats['total_budgeted'], 2) }}</span>
                                    </div>

                                    <!-- Total Spent -->
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Total Spent:</span>
                                        <span class="text-lg font-semibold text-red-600">${{ number_format($stats['total_spent'], 2) }}</span>
                                    </div>
                                </div>

                                <!-- Budget Status -->
                                <div class="pt-4 border-t border-gray-200">
                                    @if($stats['over_under'] >= 0)
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Under Budget
                                            </span>
                                            <span class="text-sm font-semibold text-green-600">
                                                ${{ number_format($stats['over_under'], 2) }} saved
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                Over Budget
                                            </span>
                                            <span class="text-sm font-semibold text-red-600">
                                                ${{ number_format(abs($stats['over_under']), 2) }} over
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Savings/Investment Info -->
                                @if($stats['monthly_salary'] > 0)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Net Savings:</span>
                                            <span class="text-lg font-bold {{ $stats['savings'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                ${{ number_format($stats['savings'], 2) }}
                                            </span>
                                        </div>
                                        @if($stats['monthly_salary'] > 0)
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ number_format(($stats['savings'] / $stats['monthly_salary']) * 100, 1) }}% of income saved
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                <!-- Progress Bar -->
                                <div class="mt-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        @php
                                            $percentage = $stats['total_budgeted'] > 0 ? ($stats['total_spent'] / $stats['total_budgeted']) * 100 : 0;
                                            $percentage = min($percentage, 100);
                                        @endphp
                                        <div class="h-2 rounded-full {{ $percentage >= 100 ? 'bg-red-500' : ($percentage >= 80 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format($percentage, 1) }}% of budget spent
                                    </p>
                                </div>
                            </div>
                        </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Budget History Yet</h3>
                        <p class="text-gray-500 mb-4">Start tracking your finances by creating budget templates and generating monthly budgets.</p>
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('budget-templates.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Manage Templates
                            </a>
                            <a href="{{ route('budget-templates.generate-form') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Generate Budgets
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
