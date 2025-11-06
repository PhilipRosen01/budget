<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Purchase Goals & Rewards') }}
            </h2>
            <a href="{{ route('purchase-goals.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Goal
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($purchaseGoals->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($purchaseGoals as $goal)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $goal->name }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Priority {{ $goal->priority }}
                                    </span>
                                </div>

                                @if($goal->description)
                                    <p class="text-gray-600 text-sm mb-4">{{ $goal->description }}</p>
                                @endif

                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Progress</span>
                                        <span>${{ number_format($goal->current_amount, 2) }} / ${{ number_format($goal->target_amount, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $goal->progress_percentage }}%"></div>
                                    </div>
                                    <div class="text-right text-xs text-gray-500 mt-1">
                                        {{ number_format($goal->progress_percentage, 1) }}% complete
                                    </div>
                                </div>

                                @if($goal->is_completed)
                                    <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded text-sm mb-4">
                                        🎉 Goal completed on {{ $goal->completed_at->format('M j, Y') }}!
                                    </div>
                                @else
                                    <div class="text-sm text-gray-600 mb-4">
                                        <span class="font-medium">${{ number_format($goal->target_amount - $goal->current_amount, 2) }}</span> remaining
                                    </div>
                                @endif

                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        Created {{ $goal->created_at->diffForHumans() }}
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('purchase-goals.show', $goal) }}" class="text-blue-600 hover:text-blue-900 text-sm">View</a>
                                        <a href="{{ route('purchase-goals.edit', $goal) }}" class="text-yellow-600 hover:text-yellow-900 text-sm">Edit</a>
                                        <form action="{{ route('purchase-goals.destroy', $goal) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this goal?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 text-center">
                        <div class="text-gray-500 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Purchase Goals Yet</h3>
                        <p class="text-gray-600 mb-4">Create your first purchase goal to start saving toward something special!</p>
                        <a href="{{ route('purchase-goals.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Your First Goal
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>