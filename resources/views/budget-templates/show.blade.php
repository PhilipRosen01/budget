<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $budgetTemplate->name }}
                </h2>
                <p class="text-sm text-gray-600">Budget Template Details</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('budget-templates.edit', $budgetTemplate) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Template
                </a>
                <a href="{{ route('budget-templates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to Templates
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Template Overview Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Amount -->
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">${{ number_format($budgetTemplate->amount, 2) }}</div>
                            <div class="text-sm text-gray-500">Monthly Amount</div>
                        </div>
                        
                        <!-- Category -->
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-700">
                                @if($budgetTemplate->category)
                                    {{ ucfirst(str_replace('_', ' ', $budgetTemplate->category)) }}
                                @else
                                    General
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">Category</div>
                        </div>
                        
                        <!-- Status -->
                        <div class="text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $budgetTemplate->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $budgetTemplate->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <div class="text-sm text-gray-500 mt-1">Status</div>
                        </div>
                        
                        <!-- Type -->
                        <div class="text-center">
                            <div class="flex items-center justify-center">
                                @if($budgetTemplate->is_automatic)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"></path>
                                        </svg>
                                        Automatic
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        Manual
                                    </span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Type</div>
                        </div>
                    </div>
                    
                    @if($budgetTemplate->description)
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-700">{{ $budgetTemplate->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Monthly Budgets Generated from this Template -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Monthly Budgets Generated</h3>
                        <span class="text-sm text-gray-500">{{ $budgetTemplate->budgets->count() }} months</span>
                    </div>

                    @if($budgetTemplate->budgets->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($budgetTemplate->budgets->sortByDesc(function($budget) { return $budget->year * 100 + $budget->month; }) as $budget)
                                <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $budget->fullMonthYear }}</h4>
                                            @if($budget->isCurrentMonth())
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Current Month
                                                </span>
                                            @endif
                                        </div>
                                        <a href="{{ route('budgets.show', $budget) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                            View →
                                        </a>
                                    </div>
                                    
                                    <div class="text-2xl font-bold text-gray-900 mb-1">
                                        ${{ number_format($budget->amount, 2) }}
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="mb-3">
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>Spent: ${{ number_format($budget->totalSpent(), 2) }}</span>
                                            <span>{{ number_format($budget->percentageUsed(), 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            @php $percentage = $budget->percentageUsed() @endphp
                                            <div class="bg-{{ $percentage > 90 ? 'red' : ($percentage > 75 ? 'yellow' : 'green') }}-600 h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">{{ $budget->purchases->count() }} purchases</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $budget->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $budget->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 4v10a1 1 0 001 1h4a1 1 0 001-1V11m-8 0H5a2 2 0 00-2 2v7a2 2 0 002 2h14a2 2 0 002-2v-7a2 2 0 00-2-2h-3" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No monthly budgets yet</h3>
                            <p class="mt-1 text-sm text-gray-500">This template hasn't been used to generate any monthly budgets yet.</p>
                            <div class="mt-6">
                                <form action="{{ route('budget-templates.generate-next-month') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="template_id" value="{{ $budgetTemplate->id }}">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Generate Budget for Current Month
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>