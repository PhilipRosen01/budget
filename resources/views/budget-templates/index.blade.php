<x-app-layout>
    <div x-data="{
        selectionMode: false,
        selectedTemplates: [],
        
        init() {
            console.log('Alpine.js initialized on templates page');
            console.log('Templates count:', {{ $templates->count() }});
        },
        
        toggleSelectionMode() {
            console.log('Toggle selection mode clicked');
            this.selectionMode = !this.selectionMode;
            console.log('Selection mode is now:', this.selectionMode);
            if (!this.selectionMode) {
                this.selectedTemplates = [];
            }
        },
        
        toggleTemplate(id) {
            const index = this.selectedTemplates.indexOf(id);
            if (index > -1) {
                this.selectedTemplates.splice(index, 1);
            } else {
                this.selectedTemplates.push(id);
            }
        },
        
        selectAll() {
            const allIds = @js($templates->pluck('id')->toArray());
            this.selectedTemplates = [...allIds];
        },
        
        deselectAll() {
            this.selectedTemplates = [];
        },
        
        deleteSelected() {
            if (this.selectedTemplates.length === 0) {
                alert('Please select at least one template to delete.');
                return;
            }
            
            if (confirm('Are you sure you want to delete ' + this.selectedTemplates.length + ' template(s)? This will not delete existing monthly budgets.')) {
                fetch('{{ route('budget-templates.bulk-delete') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    body: JSON.stringify({ ids: this.selectedTemplates })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error deleting templates: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting templates. Please try again.');
                });
            }
        },
        
        deleteAll() {
            if (confirm('Are you sure you want to delete ALL templates? This action cannot be undone. This will not delete existing monthly budgets.')) {
                const allIds = @js($templates->pluck('id')->toArray());
                fetch('{{ route('budget-templates.bulk-delete') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    body: JSON.stringify({ ids: allIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error deleting templates: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting templates. Please try again.');
                });
            }
        }
    }">
    <x-slot name="header">
        <!-- Responsive Header Layout -->
        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Budget Templates') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Define your standard monthly budgets</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('budget-templates.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="hidden xs:inline">Create Template</span>
                    <span class="xs:hidden">Create</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Select Button - Simple and Visible -->
            @if($templates->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Manage Templates</h3>
                            <p class="text-sm text-gray-500">Select multiple templates to delete them at once</p>
                        </div>
                        <button @click="toggleSelectionMode()" 
                                type="button"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
                                :class="selectionMode ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500'">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span x-text="selectionMode ? 'Cancel Selection' : 'Select Templates'">Select Templates</span>
                        </button>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <!-- Responsive Info Section -->
                    <div class="flex flex-col space-y-4 lg:flex-row lg:justify-between lg:items-center lg:space-y-0">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Budget Templates Explained</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <strong>Templates</strong> are your budget patterns that repeat each month (like rent, groceries, etc.). 
                                <strong>Monthly Budgets</strong> are the actual budgets created from your templates for specific months where you track spending.
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                💡 Think of templates as cookie cutters and monthly budgets as the actual cookies!
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('budget-templates.generate-form') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Generate Budgets
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auto-Generate Templates Section -->
            @if(Auth::user()->hasMonthlySalary())
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col space-y-4 lg:flex-row lg:justify-between lg:items-center lg:space-y-0">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Smart Template Generator
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Let us automatically create budget templates based on your ${{ number_format(Auth::user()->monthly_salary, 0) }} monthly salary and spending preferences.
                                    This replaces manual template creation with intelligent budgeting based on your lifestyle.
                                </p>
                                <p class="text-xs text-purple-600 mt-1">
                                    💡 Generated templates respect your "expenses you don't pay for" settings and allocate your money intelligently!
                                </p>
                            </div>
                            <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-3">
                                <button type="button" onclick="previewTemplates()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Preview
                                </button>
                                <form action="{{ route('budget-preferences.generate-templates') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('This will replace your existing automatic templates with new ones based on your current preferences. Continue?')" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Generate Smart Templates
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bulk Actions Bar (shown in selection mode) -->
            <div x-show="selectionMode" 
                 x-transition
                 class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-gray-700">
                            <span x-text="selectedTemplates.length"></span> template(s) selected
                        </span>
                        <div class="flex gap-2">
                            <button @click="selectAll()" 
                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Select All
                            </button>
                            <span class="text-gray-300">|</span>
                            <button @click="deselectAll()" 
                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Deselect All
                            </button>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button @click="deleteSelected()" 
                                :disabled="selectedTemplates.length === 0"
                                :class="selectedTemplates.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-red-700'"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Selected
                        </button>
                        <button @click="deleteAll()" 
                                class="inline-flex items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete All
                        </button>
                    </div>
                </div>
            </div>

            @if($templates->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($templates as $template)
                        <div @click="selectionMode && toggleTemplate({{ $template->id }})"
                             :class="{ 
                                 'ring-4 ring-blue-500 ring-opacity-50': selectionMode && selectedTemplates.includes({{ $template->id }}),
                                 'cursor-pointer': selectionMode
                             }"
                             class="bg-white overflow-hidden shadow-sm sm:rounded-lg transition-all duration-200 relative">
                            <!-- Wrapper for all content with conditional padding for checkbox -->
                            <div class="p-6" :class="selectionMode ? 'pl-14' : ''">
                                <!-- Selection Checkbox - Positioned at top-left to avoid title overlap -->
                                <div x-show="selectionMode" 
                                     class="absolute top-6 left-4 z-10"
                                     @click.stop>
                                    <input type="checkbox" 
                                           :checked="selectedTemplates.includes({{ $template->id }})"
                                           @change="toggleTemplate({{ $template->id }})"
                                           class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                </div>

                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $template->name }}</h3>
                                            @if($template->is_automatic)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Auto
                                                </span>
                                            @endif
                                        </div>
                                        @if($template->category)
                                            <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $template->category)) }}</p>
                                        @endif
                                    </div>
                                    <div x-show="!selectionMode" class="flex space-x-2">
                                        <a href="{{ route('budget-templates.edit', $template) }}" 
                                           class="text-blue-600 hover:text-blue-900"
                                           @click.stop>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('budget-templates.destroy', $template) }}" 
                                              method="POST" 
                                              class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this template? This will not delete existing monthly budgets.')"
                                              @click.stop>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    @if($template->is_auto_amount)
                                        <div class="text-2xl font-bold text-indigo-900">
                                            ${{ number_format($template->getCalculatedAmount(), 2) }}
                                        </div>
                                        <div class="text-sm text-indigo-600">
                                            {{ $template->percentage ?? 0 }}% of salary (auto-calculated)
                                        </div>
                                    @else
                                        <div class="text-2xl font-bold text-gray-900">${{ number_format($template->amount, 2) }}</div>
                                        <div class="text-sm text-gray-500">per month (fixed)</div>
                                    @endif
                                </div>

                                @if($template->default_category)
                                    @php
                                        $categoryConfig = collect(config('budget_categories.categories'))
                                            ->flatten(1)
                                            ->firstWhere('name', function($cat) use ($template) {
                                                return array_search($cat, config('budget_categories.categories.needs')) === $template->default_category ||
                                                       array_search($cat, config('budget_categories.categories.wants')) === $template->default_category ||
                                                       array_search($cat, config('budget_categories.categories.savings')) === $template->default_category ||
                                                       array_search($cat, config('budget_categories.categories.other')) === $template->default_category;
                                            });
                                        
                                        // Find the category config properly
                                        foreach(config('budget_categories.categories') as $groupCategories) {
                                            if (isset($groupCategories[$template->default_category])) {
                                                $categoryConfig = $groupCategories[$template->default_category];
                                                break;
                                            }
                                        }
                                    @endphp
                                    @if($categoryConfig)
                                        <p class="text-xs text-blue-600 mb-2">
                                            📂 {{ $categoryConfig['name'] ?? 'Category' }}
                                        </p>
                                    @endif
                                @endif

                                @if($template->description)
                                    <p class="text-sm text-gray-600 mb-4">{{ $template->description }}</p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $template->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    
                                    <div class="text-sm text-gray-500">
                                        {{ $template->budgets()->count() }} months generated
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('budget-templates.show', $template) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        View Details →
                                    </a>
                                </div>
                            </div>
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
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No budget templates yet</h3>
                        <p class="text-gray-500 mb-4">Create your first budget template to define your standard monthly budgets. Templates automatically generate budgets each month.</p>
                        <a href="{{ route('budget-templates.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create Your First Template
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function previewTemplates() {
            fetch('{{ route("budget-preferences.preview-templates") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.error);
                    return;
                }

                let previewContent = `
                    <div class="space-y-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Preview of Smart Templates</h3>
                        <p class="text-sm text-gray-600 mb-4">These templates will be created based on your preferences:</p>
                `;

                data.templates.forEach(template => {
                    previewContent += `
                        <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                            <div>
                                <span class="font-medium text-gray-900">${template.name}</span>
                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ${template.category}
                                </span>
                            </div>
                            <span class="font-semibold text-green-600">$${parseFloat(template.amount).toFixed(2)}</span>
                        </div>
                    `;
                });

                previewContent += `
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700">
                                💡 These templates will replace any existing automatic templates but keep your manually created ones.
                            </p>
                        </div>
                    </div>
                `;

                // Create modal-like alert with better formatting
                const modal = document.createElement('div');
                modal.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 1000;
                `;

                const modalContent = document.createElement('div');
                modalContent.style.cssText = `
                    background: white;
                    max-width: 500px;
                    max-height: 80vh;
                    overflow-y: auto;
                    border-radius: 8px;
                    padding: 20px;
                    margin: 20px;
                `;

                modalContent.innerHTML = previewContent + `
                    <div class="mt-6 flex justify-end">
                        <button onclick="this.closest('[style*=fixed]').remove()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                            Close
                        </button>
                    </div>
                `;

                modal.appendChild(modalContent);
                document.body.appendChild(modal);

                // Close on background click
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.remove();
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading preview. Please try again.');
            });
        }
    </script>
    </div>
</x-app-layout>