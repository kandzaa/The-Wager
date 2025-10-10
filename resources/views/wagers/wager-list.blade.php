@if ($wagers->isEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-8 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700">
            <ion-icon name="time-outline" class="h-6 w-6 text-slate-400"></ion-icon>
        </div>
        <h3 class="mt-3 text-lg font-medium text-slate-900 dark:text-white">
            {{ $emptyMessage ?? 'No wagers found' }}
        </h3>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Try adjusting your search or filters
        </p>
    </div>
@else
    <div class="grid gap-6 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3">
        @foreach ($wagers as $wager)
            @include('wagers.wager-item', ['wager' => $wager, 'compact' => false])
        @endforeach
    </div>

    @if ($wagers->hasMorePages())
        <div class="mt-8 text-center" x-data="{ loading: false }" x-intersect="
            if (!loading && $el.offsetTop < (window.pageYOffset + window.innerHeight)) {
                loading = true;
                fetch(`{{ $wagers->nextPageUrl() }}&search=${window.currentSearch || ''}&type={{ $type }}`)
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('{{ $type }}-wagers-container');
                        const wrapper = container.querySelector('.wager-list-wrapper');
                        const newItems = document.createElement('div');
                        newItems.innerHTML = data.html;
                        wrapper.appendChild(newItems.querySelector('.wager-list-wrapper').innerHTML);
                        loading = false;
                    });
            }
        ">
            <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-emerald-500 border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"></div>
        </div>
    @endif
@endif
