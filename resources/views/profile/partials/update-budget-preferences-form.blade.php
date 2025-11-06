<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Budget Preferences') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Set your monthly salary and configure which expenses you don\'t pay for to get personalized budget recommendations.') }}
        </p>
    </header>

    <form method="post" action="{{ route('budget-preferences.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Monthly Salary -->
        <div>
            <x-input-label for="monthly_salary" :value="__('Monthly Salary')" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">$</span>
                </div>
                <x-text-input 
                    id="monthly_salary" 
                    name="monthly_salary" 
                    type="number" 
                    step="0.01"
                    min="0"
                    max="999999.99"
                    class="pl-7" 
                    :value="old('monthly_salary', $user->monthly_salary)" 
                    placeholder="5000.00"
                />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('monthly_salary')" />
        </div>

        <!-- Expense Exemptions -->
        <div>
            <h3 class="text-base font-medium text-gray-900 mb-3">Expenses You Don't Pay For</h3>
            <p class="text-sm text-gray-600 mb-4">Check the expenses you don't have to pay for. The budget will be adjusted accordingly.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $preferences = $user->budgetPreferences ?? new \App\Models\BudgetPreference();
                    $exemptions = [
                        'no_rent' => ['label' => 'Rent/Mortgage', 'description' => 'Living with family or own property'],
                        'no_car_payment' => ['label' => 'Car Payment', 'description' => 'Paid off car or no car'],
                        'no_insurance' => ['label' => 'Insurance', 'description' => 'Covered by employer/family'],
                        'no_groceries' => ['label' => 'Groceries', 'description' => 'Meals provided or shared'],
                        'no_phone_payment' => ['label' => 'Phone Bill', 'description' => 'Family plan or employer-provided'],
                        'no_utilities' => ['label' => 'Utilities', 'description' => 'Included in rent or not responsible'],
                        'no_internet' => ['label' => 'Internet', 'description' => 'Included or shared'],
                        'no_gas' => ['label' => 'Gas/Fuel', 'description' => 'No car or employer-provided'],
                        'no_maintenance' => ['label' => 'Car Maintenance', 'description' => 'No car or covered elsewhere'],
                        'no_subscriptions' => ['label' => 'Subscriptions', 'description' => 'No streaming/software subscriptions'],
                    ];
                @endphp

                @foreach($exemptions as $field => $info)
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input 
                                id="{{ $field }}" 
                                name="{{ $field }}" 
                                type="checkbox" 
                                value="1"
                                @checked(old($field, $preferences->$field ?? false))
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                            >
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="{{ $field }}" class="font-medium text-gray-700">{{ $info['label'] }}</label>
                            <p class="text-gray-500">{{ $info['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Investment Settings -->
        <div class="border-t pt-6">
            <h3 class="text-base font-medium text-gray-900 mb-3">Automatic Investment</h3>
            <p class="text-sm text-gray-600 mb-4">Set up automatic monthly investments to build long-term wealth.</p>
            
            <div class="space-y-4">
                <!-- Enable Auto Investment -->
                <div class="flex items-center">
                    <input 
                        id="auto_invest_enabled" 
                        name="auto_invest_enabled" 
                        type="checkbox" 
                        value="1"
                        @checked(old('auto_invest_enabled', $preferences->auto_invest_enabled ?? true))
                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                    >
                    <label for="auto_invest_enabled" class="ml-2 block text-sm text-gray-900">
                        Enable automatic monthly investment
                    </label>
                </div>

                <!-- Investment Amount -->
                <div>
                    <x-input-label for="monthly_investment_amount" :value="__('Monthly Investment Amount')" />
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <x-text-input 
                            id="monthly_investment_amount" 
                            name="monthly_investment_amount" 
                            type="number" 
                            step="0.01"
                            min="0"
                            max="99999.99"
                            class="pl-7" 
                            :value="old('monthly_investment_amount', $preferences->monthly_investment_amount ?? 1000.00)" 
                            placeholder="1000.00"
                        />
                    </div>
                    <p class="mt-1 text-xs text-gray-600">This amount will be automatically allocated to investments each month and marked as spent.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('monthly_investment_amount')" />
                </div>
            </div>
        </div>

        <!-- Custom Percentages (Advanced) -->
        <div class="border-t pt-6">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-medium text-gray-900">Custom Budget Percentages</h3>
                <button type="button" onclick="toggleAdvanced()" class="text-sm text-indigo-600 hover:text-indigo-500">
                    <span id="toggleText">Show Advanced</span>
                </button>
            </div>
            <p class="text-sm text-gray-600 mt-1">Optional: Override standard percentages for specific categories</p>
            
            <div id="advancedSection" class="hidden mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $percentageFields = [
                        'housing_percentage' => 'Housing (28%)',
                        'transportation_percentage' => 'Transportation (12%)',
                        'food_percentage' => 'Food (12%)',
                        'savings_percentage' => 'Savings (15%)',
                        'insurance_percentage' => 'Insurance (7%)',
                        'debt_percentage' => 'Debt Payments (8%)',
                        'personal_percentage' => 'Personal/Entertainment (8%)',
                        'utilities_percentage' => 'Utilities (5%)',
                        'miscellaneous_percentage' => 'Miscellaneous (5%)',
                    ];
                @endphp

                @foreach($percentageFields as $field => $label)
                    <div>
                        <x-input-label for="{{ $field }}" :value="$label" />
                        <div class="relative mt-1">
                            <x-text-input 
                                id="{{ $field }}" 
                                name="{{ $field }}" 
                                type="number" 
                                step="0.01"
                                min="0"
                                max="100"
                                class="pr-8" 
                                :value="old($field, $preferences->$field ?? '')" 
                                placeholder="Leave empty for default"
                            />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Preferences') }}</x-primary-button>

            @if (session('status') === 'budget-preferences-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <!-- Auto-Generate Templates Button -->
    @if($user->hasMonthlySalary())
        <div class="mt-6 pt-6 border-t">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-medium text-gray-900">Generate Automatic Budget Templates</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Create budget templates automatically based on your salary ({{ $user->monthly_salary ? '$' . number_format($user->monthly_salary, 2) : 'Not set' }}) and preferences.
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="previewTemplates()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Preview
                    </button>
                    <form action="{{ route('budget-preferences.generate-templates') }}" method="POST" class="inline">
                        @csrf
                        <x-primary-button type="submit" onclick="return confirm('This will replace existing automatic templates. Continue?')">
                            Generate Templates
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function toggleAdvanced() {
            const section = document.getElementById('advancedSection');
            const toggleText = document.getElementById('toggleText');
            
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                toggleText.textContent = 'Hide Advanced';
            } else {
                section.classList.add('hidden');
                toggleText.textContent = 'Show Advanced';
            }
        }

        function previewTemplates() {
            fetch('{{ route('budget-preferences.preview-templates') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error: ' + data.error);
                        return;
                    }
                    
                    let message = 'Automatic Budget Templates Preview:\n\n';
                    let total = 0;
                    
                    data.templates.forEach(template => {
                        message += `${template.name}: $${template.amount}\n`;
                        total += parseFloat(template.amount);
                    });
                    
                    message += `\nTotal: $${total.toFixed(2)} (${(total / {{ $user->monthly_salary ?? 0 }} * 100).toFixed(1)}% of salary)`;
                    
                    alert(message);
                })
                .catch(error => {
                    alert('Error previewing templates: ' + error.message);
                });
        }
    </script>
</section>