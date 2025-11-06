<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Purchase Goal') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('purchase-goals.show', $purchaseGoal) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    View Goal
                </a>
                <a href="{{ route('purchase-goals.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Goals
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Current Progress Display -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                        <h3 class="text-sm font-medium text-blue-800 mb-2">Current Progress</h3>
                        <div class="flex justify-between text-sm text-blue-700 mb-1">
                            <span>${{ number_format($purchaseGoal->current_amount, 2) }} saved</span>
                            <span>{{ number_format($purchaseGoal->progress_percentage, 1) }}% complete</span>
                        </div>
                        <div class="w-full bg-blue-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $purchaseGoal->progress_percentage }}%"></div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('purchase-goals.update', $purchaseGoal) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Goal Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $purchaseGoal->name) }}" 
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="e.g., New MacBook Pro, Vacation to Europe, Emergency Fund" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                      placeholder="Describe what you're saving for and why it matters to you...">{{ old('description', $purchaseGoal->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-2">Target Amount ($)</label>
                            <input type="number" name="target_amount" id="target_amount" value="{{ old('target_amount', $purchaseGoal->target_amount) }}" 
                                   step="0.01" min="0.01"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="1000.00" required>
                            @if($purchaseGoal->current_amount > 0)
                                <p class="mt-1 text-sm text-yellow-600">
                                    ⚠️ Note: You currently have ${{ number_format($purchaseGoal->current_amount, 2) }} saved toward this goal.
                                    Setting the target below this amount will mark the goal as completed.
                                </p>
                            @endif
                            @error('target_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                            <select name="priority" id="priority" 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">Select Priority...</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('priority', $purchaseGoal->priority) == $i ? 'selected' : '' }}>
                                        {{ $i }} - {{ $i == 1 ? 'Highest Priority' : ($i == 10 ? 'Lowest Priority' : 'Priority ' . $i) }}
                                    </option>
                                @endfor
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Higher priority goals (1-3) receive extra savings first when you have leftover budget.</p>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($purchaseGoal->is_completed)
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="font-semibold">🎉 This goal is already completed!</p>
                                        <p class="text-sm">Completed on {{ $purchaseGoal->completed_at->format('F j, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('purchase-goals.show', $purchaseGoal) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Update Goal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>