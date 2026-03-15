<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-extrabold leading-7 text-gray-900 sm:text-4xl sm:truncate tracking-tight">
                    Games Catalog
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Showing {{ $products->total() }} premium titles from Metacritic.
                </p>
            </div>
            
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <div class="relative rounded-md shadow-sm w-full md:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3 transition duration-150 ease-in-out" 
                        placeholder="Search by title..."
                    >
                </div>
            </div>
        </div>

        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest">Preview</th>
                            <th wire:click="sortBy('id')" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest cursor-pointer hover:text-indigo-600 transition duration-150 group">
                                <span class="flex items-center">
                                    ID
                                    <span class="ml-2">@if($sortField === 'id') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else <span class="opacity-0 group-hover:opacity-100 text-gray-300">↕</span> @endif</span>
                                </span>
                            </th>
                            <th wire:click="sortBy('price')" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest cursor-pointer hover:text-indigo-600 transition duration-150 group">
                                <span class="flex items-center">
                                    Price
                                    <span class="ml-2">@if($sortField === 'price') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else <span class="opacity-0 group-hover:opacity-100 text-gray-300">↕</span> @endif</span>
                                </span>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest">Genre / Category</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $product)
                        <tr class="hover:bg-indigo-50/30 transition duration-150 ease-in-out group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-14 w-14 flex-shrink-0">
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->url }}" alt="{{ $product->title }}" class="h-14 w-14 rounded-xl object-cover shadow-sm group-hover:scale-110 transition duration-200">
                                    @else
                                        <div class="h-14 w-14 rounded-xl bg-gray-100 flex items-center justify-center border border-gray-200">
                                            <span class="text-gray-400 text-xs font-medium">No Img</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 line-clamp-1">{{ $product->title }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">ID: #{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">
                                €{{ number_format($product->price, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(explode(',', $product->category) as $cat)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                            {{ trim($cat) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">
                                No products found matching your search...
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>