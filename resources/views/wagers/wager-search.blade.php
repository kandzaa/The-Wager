<div class="flex justify-center mb-8">
    <div class="relative w-full max-w-md">
        <input type="text" id="wager-search-input" placeholder="Search wagers by name..." autocomplete="off"
            class="w-full p-3 pl-10 bg-white dark:bg-slate-900/40 backdrop-blur border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
            onkeyup="filterWagers()">

        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 dark:text-slate-400 absolute left-3 top-3.5"
            viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd" />
        </svg>
    </div>
</div>

<script>
    function filterWagers() {
        const searchTerm = document.getElementById('wager-search-input').value.toLowerCase();
        const wagerItems = document.querySelectorAll('.wager-item');
        let hasVisibleItems = false;

        wagerItems.forEach(item => {
            const name = item.getAttribute('data-name').toLowerCase();
            const creator = item.getAttribute('data-creator').toLowerCase();
            const matches = name.includes(searchTerm) || creator.includes(searchTerm);

            if (searchTerm === '' || matches) {
                item.style.display = 'block';
                hasVisibleItems = true;
            } else {
                item.style.display = 'none';
            }
        });

        // Toggle empty state message
        const emptyState = document.querySelector('.no-wagers-message');
        const noResultsMessage = document.querySelector('.no-results-message');

        if (emptyState) {
            emptyState.style.display = searchTerm === '' && !hasVisibleItems ? 'block' : 'none';
        }

        if (noResultsMessage) {
            noResultsMessage.style.display = searchTerm !== '' && !hasVisibleItems ? 'block' : 'none';
        }
    }

    // Add debounce to prevent too many calls
    let debounceTimer;
    const searchInput = document.getElementById('wager-search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(filterWagers, 300);
        });
    }
</script>
