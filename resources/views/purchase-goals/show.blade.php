<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $purchaseGoal->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('purchase-goals.edit', $purchaseGoal) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit Goal
                </a>
                <a href="{{ route('purchase-goals.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Goals
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Goal Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Goal Details</h3>
                            
                            @if($purchaseGoal->description)
                                <div class="mb-6">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                                    <p class="text-gray-600">{{ $purchaseGoal->description }}</p>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-1">Target Amount</h4>
                                    <p class="text-2xl font-bold text-green-600">${{ number_format($purchaseGoal->target_amount, 2) }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-1">Current Amount</h4>
                                    <p class="text-2xl font-bold text-blue-600">${{ number_format($purchaseGoal->current_amount, 2) }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-1">Remaining</h4>
                                    <p class="text-2xl font-bold text-gray-600">${{ number_format($purchaseGoal->target_amount - $purchaseGoal->current_amount, 2) }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-1">Priority Level</h4>
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Priority {{ $purchaseGoal->priority }}
                                    </span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-6">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Progress</span>
                                    <span>{{ number_format($purchaseGoal->progress_percentage, 1) }}% complete</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-4 rounded-full transition-all duration-500" 
                                         style="width: {{ $purchaseGoal->progress_percentage }}%"></div>
                                </div>
                            </div>

                            @if($purchaseGoal->is_completed)
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <p class="font-semibold">🎉 Congratulations! Goal completed!</p>
                                            <p class="text-sm">Completed on {{ $purchaseGoal->completed_at->format('F j, Y \a\t g:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="text-sm text-gray-500">
                                <p>Created {{ $purchaseGoal->created_at->format('F j, Y \a\t g:i A') }}</p>
                                @if($purchaseGoal->updated_at != $purchaseGoal->created_at)
                                    <p>Last updated {{ $purchaseGoal->updated_at->format('F j, Y \a\t g:i A') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions and Stats -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Progress:</span>
                                    <span class="text-sm font-medium">{{ number_format($purchaseGoal->progress_percentage, 1) }}%</span>
                                </div>
                                
                                @if(!$purchaseGoal->is_completed)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Still needed:</span>
                                        <span class="text-sm font-medium text-red-600">
                                            ${{ number_format($purchaseGoal->target_amount - $purchaseGoal->current_amount, 2) }}
                                        </span>
                                    </div>
                                @endif

                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Priority:</span>
                                    <span class="text-sm font-medium">{{ $purchaseGoal->priority }}/10</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                            
                            <div class="space-y-3">
                                <a href="{{ route('purchase-goals.edit', $purchaseGoal) }}" 
                                   class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-center block">
                                    Edit Goal
                                </a>
                                
                                <form action="{{ route('purchase-goals.destroy', $purchaseGoal) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this goal? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Delete Goal
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- How it Works -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">💡 How Purchase Goals Work</h4>
                        <p class="text-xs text-blue-700">
                            At the end of each month, any leftover money from your budget is automatically allocated to your purchase goals based on priority. Higher priority goals get funded first!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>