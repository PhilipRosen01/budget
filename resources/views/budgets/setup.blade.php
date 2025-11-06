<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Set Up Budget') }} - {{ $monthName }}
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Budget Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Set Up Your Budget for {{ $monthName }}</h3>
                        <p class="text-gray-600">Choose how you'd like to create your monthly budget</p>
                    </div>
                    
                    <!-- Budget Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 bg-gray-50 rounded-lg p-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">${{ number_format($totalSalary, 2) }}</div>
                            <div class="text-sm text-gray-500">Monthly Salary</div>
                        </div>
                        @if($investmentAmount > 0)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">${{ number_format($investmentAmount, 2) }}</div>
                            <div class="text-sm text-gray-500">Investment Allocation</div>
                        </div>
                        @endif
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600" id="available-budget">${{ number_format($availableBudget, 2) }}</div>
                            <div class="text-sm text-gray-500">Available for Budgets</div>
                        </div>
                    </div>

                    <!-- Setup Options -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Automatic Setup -->
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-lg p-6 border border-indigo-200">
                            <div class="text-center mb-4">
                                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">Automatic Setup</h4>
                                <p class="text-gray-600 text-sm">Let us create your budget automatically based on your preferences and active templates.</p>
                            </div>
                            
                            @if($automaticTemplates->count() > 0)
                                <div class="mb-4">
                                    <h5 class="font-medium text-gray-900 mb-2">Will create {{ $automaticTemplates->count() }} budgets:</h5>
                                    <div class="space-y-1 text-sm text-gray-600 max-h-32 overflow-y-auto">
                                        @foreach($automaticTemplates->take(5) as $template)
                                            <div class="flex justify-between">
                                                <span>{{ $template->name }}</span>
                                                <span class="font-medium">${{ number_format($template->amount, 2) }}</span>
                                            </div>
                                        @endforeach
                                        @if($automaticTemplates->count() > 5)
                                            <div class="text-xs text-gray-500">... and {{ $automaticTemplates->count() - 5 }} more</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <form method="POST" action="{{ route('budgets.create-automatic') }}">
                                    @csrf
                                    <input type="hidden" name="month" value="{{ $month }}">
                                    <input type="hidden" name="year" value="{{ $year }}">
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Create Automatic Budget
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-sm text-gray-500 mb-3">No templates match your current preferences.</p>
                                    <a href="{{ route('budget-preferences.update') }}" class="text-indigo-600 hover:text-indigo-500 font-medium text-sm">Update Preferences</a>
                                </div>
                            @endif
                        </div>

                        <!-- Manual Setup -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6 border border-green-200">
                            <div class="text-center mb-4">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">Manual Setup</h4>
                                <p class="text-gray-600 text-sm">Choose exactly which templates to use and see live budget allocation.</p>
                            </div>
                            
                            @if($templates->count() > 0)
                                <button id="start-manual-setup" class="w-full inline-flex justify-center items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    Start Manual Setup
                                </button>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-sm text-gray-500 mb-3">No active templates available.</p>
                                    <a href="{{ route('budget-templates.create') }}" class="text-green-600 hover:text-green-500 font-medium text-sm">Create Templates</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Setup Panel (Hidden by default) -->
            <div id="manual-setup-panel" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Manual Budget Setup</h3>
                        <button id="close-manual-setup" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('budgets.create-from-selected') }}" id="manual-setup-form">
                        @csrf
                        <input type="hidden" name="month" value="{{ $month }}">
                        <input type="hidden" name="year" value="{{ $year }}">

                        <!-- Budget Allocation Summary -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 bg-gray-50 rounded-lg p-4">
                            <div class="text-center">
                                <div class="text-lg font-bold text-blue-600" id="total-allocated">$0.00</div>
                                <div class="text-sm text-gray-500">Total Allocated</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-green-600" id="remaining-budget">${{ number_format($totalSalary, 2) }}</div>
                                <div class="text-sm text-gray-500">Remaining</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-purple-600" id="selected-count">0</div>
                                <div class="text-sm text-gray-500">Selected Templates</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold" id="budget-status" data-over="text-red-600" data-under="text-gray-600">
                                    <span id="budget-status-text">Within Budget</span>
                                </div>
                                <div class="text-sm text-gray-500">Status</div>
                            </div>
                        </div>

                        <!-- Template Selection -->
                        <div class="space-y-4 mb-6">
                            @foreach($templates as $template)
                                @php
                                    $hasExistingBudget = in_array($template->id, $existingBudgets);
                                @endphp
                                <div class="template-item flex items-center space-x-4 p-4 border rounded-lg {{ $hasExistingBudget ? 'bg-gray-50 border-gray-200' : 'border-gray-300 hover:border-green-300' }}" 
                                     data-template-id="{{ $template->id }}" 
                                     data-amount="{{ $template->amount }}">
                                    <div class="flex items-center h-5">
                                        <input
                                            id="manual_template_{{ $template->id }}"
                                            name="template_ids[]"
                                            type="checkbox"
                                            value="{{ $template->id }}"
                                            {{ $hasExistingBudget ? 'disabled checked' : '' }}
                                            class="template-checkbox focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded"
                                        >
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <label for="manual_template_{{ $template->id }}" class="block text-sm font-medium {{ $hasExistingBudget ? 'text-gray-500' : 'text-gray-900' }}">
                                                {{ $template->name }}
                                                @if($hasExistingBudget)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                                        Already Created
                                                    </span>
                                                @endif
                                            </label>
                                            <div class="flex items-center space-x-2">
                                                @if($template->category)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $hasExistingBudget ? 'bg-gray-100 text-gray-500' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ ucfirst($template->category) }}
                                                    </span>
                                                @endif
                                                <span class="text-lg font-semibold {{ $hasExistingBudget ? 'text-gray-400' : 'text-gray-900' }}">${{ number_format($template->amount, 2) }}</span>
                                            </div>
                                        </div>
                                        @if($template->description)
                                            <p class="mt-1 text-sm {{ $hasExistingBudget ? 'text-gray-400' : 'text-gray-500' }}">{{ $template->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <button type="button" id="select-all-manual" class="text-sm text-green-600 hover:text-green-500 font-medium">
                                    Select All Available
                                </button>
                                <span class="mx-2 text-gray-300">|</span>
                                <button type="button" id="select-none-manual" class="text-sm text-green-600 hover:text-green-500 font-medium">
                                    Select None
                                </button>
                            </div>
                            
                            <div class="flex space-x-3">
                                <button type="button" id="close-manual-setup-2" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cancel
                                </button>
                                <button type="submit" id="create-manual-budgets" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150" disabled>
                                    Create Selected Budgets
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalSalary = {{ $totalSalary }};
            let totalAllocated = 0;
            let selectedCount = 0;

            // DOM elements
            const manualSetupPanel = document.getElementById('manual-setup-panel');
            const startManualBtn = document.getElementById('start-manual-setup');
            const closeManualBtns = [document.getElementById('close-manual-setup'), document.getElementById('close-manual-setup-2')];
            const templateCheckboxes = document.querySelectorAll('.template-checkbox:not(:disabled)');
            const selectAllBtn = document.getElementById('select-all-manual');
            const selectNoneBtn = document.getElementById('select-none-manual');
            const createBtn = document.getElementById('create-manual-budgets');

            // Display elements
            const totalAllocatedEl = document.getElementById('total-allocated');
            const remainingBudgetEl = document.getElementById('remaining-budget');
            const selectedCountEl = document.getElementById('selected-count');
            const budgetStatusEl = document.getElementById('budget-status');
            const budgetStatusTextEl = document.getElementById('budget-status-text');

            // Show/hide manual setup panel
            startManualBtn.addEventListener('click', () => {
                manualSetupPanel.classList.remove('hidden');
                manualSetupPanel.scrollIntoView({ behavior: 'smooth' });
            });

            closeManualBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    manualSetupPanel.classList.add('hidden');
                });
            });

            // Update budget calculations
            function updateBudgetCalculations() {
                totalAllocated = 0;
                selectedCount = 0;

                templateCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const templateItem = checkbox.closest('.template-item');
                        const amount = parseFloat(templateItem.dataset.amount);
                        totalAllocated += amount;
                        selectedCount++;
                    }
                });

                const remaining = totalSalary - totalAllocated;
                const isOverBudget = remaining < 0;

                // Update displays
                totalAllocatedEl.textContent = '$' + totalAllocated.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                remainingBudgetEl.textContent = '$' + remaining.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                selectedCountEl.textContent = selectedCount;

                // Update status
                if (isOverBudget) {
                    budgetStatusEl.className = 'text-lg font-bold text-red-600';
                    budgetStatusTextEl.textContent = 'Over Budget';
                    remainingBudgetEl.className = 'text-lg font-bold text-red-600';
                } else if (selectedCount === 0) {
                    budgetStatusEl.className = 'text-lg font-bold text-gray-600';
                    budgetStatusTextEl.textContent = 'No Selection';
                    remainingBudgetEl.className = 'text-lg font-bold text-green-600';
                } else {
                    budgetStatusEl.className = 'text-lg font-bold text-green-600';
                    budgetStatusTextEl.textContent = 'Within Budget';
                    remainingBudgetEl.className = 'text-lg font-bold text-green-600';
                }

                // Enable/disable create button
                createBtn.disabled = selectedCount === 0;
                if (selectedCount === 0) {
                    createBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    createBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            // Attach change listeners
            templateCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBudgetCalculations);
            });

            // Select all/none functionality
            selectAllBtn.addEventListener('click', () => {
                templateCheckboxes.forEach(cb => cb.checked = true);
                updateBudgetCalculations();
            });

            selectNoneBtn.addEventListener('click', () => {
                templateCheckboxes.forEach(cb => cb.checked = false);
                updateBudgetCalculations();
            });

            // Initial calculation
            updateBudgetCalculations();
        });
    </script>
</x-app-layout>