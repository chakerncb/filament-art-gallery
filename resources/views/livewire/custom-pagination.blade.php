@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between bg-white p-4 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center space-x-1">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white dark:bg-gray-800 cursor-default rounded-lg leading-5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 rounded-lg leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-emerald-300 focus:border-emerald-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @endif

            @foreach ($elements as $element)
            @if (is_string($element))
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white dark:bg-gray-800 cursor-default leading-5 rounded-lg">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-emerald-600 border-emerald-600 cursor-default leading-5 rounded-lg">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white dark:bg-gray-800 leading-5 rounded-lg hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-emerald-300 focus:border-emerald-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 rounded-lg leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-emerald-300 focus:border-emerald-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @else
                <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white dark:bg-gray-800 cursor-default rounded-lg leading-5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>

        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{__('layouts.custom-pagination.showing')}} {{ $paginator->firstItem() }} {{__('layouts.custom-pagination.to')}} {{ $paginator->lastItem() }} {{__('layouts.custom-pagination.of')}} {{ $paginator->total() }} {{__('layouts.custom-pagination.results')}}
        </div>
    </nav>
@endif
