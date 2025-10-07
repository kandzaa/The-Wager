<div class="flex justify-center mb-8">
    <div class="relative w-full max-w-md">
        <input type="text" id="wager-search-input"
            class="w-full p-3 pl-10 bg-white dark:bg-slate-900/40 backdrop-blur border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
            placeholder="Search wagers" autocomplete="off" />
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 dark:text-slate-400 absolute left-3 top-3.5"
            viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd" />
        </svg>

        <div id="wager-search-loading" class="hidden absolute right-3 top-3.5">
            <svg class="animate-spin h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </div>
    </div>
</div>

<div id="wager-search-results" class="hidden max-w-2xl mx-auto mb-8">
    <h3 class="text-lg font-semibold mb-4 text-slate-900 dark:text-slate-100">Search Results</h3>
    <div id="wager-search-results-list" class="space-y-4"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('wager-search-input');
        const searchResults = document.getElementById('wager-search-results');
        const searchResultsList = document.getElementById('wager-search-results-list');
        const searchLoading = document.getElementById('wager-search-loading');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(searchTimeout);

            if (query.length === 0) {
                hideSearchResults();
                return;
            }

            showLoading();

            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });

        function performSearch(query) {
            fetch(`/wagers/search?query=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Search failed');
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    displaySearchResults(data);
                })
                .catch(error => {
                    hideLoading();
                    searchResultsList.innerHTML =
                        '<div class="text-center py-4 bg-rose-900/40 border border-rose-800 rounded-lg text-rose-400">Unable to search wagers. Please try again.</div>';
                    searchResults.classList.remove('hidden');
                });
        }

        function displaySearchResults(wagers) {
            if (wagers.length === 0) {
                searchResultsList.innerHTML =
                    '<p class="text-slate-400 text-center py-8 bg-slate-900/40 border border-slate-800 rounded-lg">No wagers found.</p>';
            } else {
                searchResultsList.innerHTML = wagers.map(wager => `
                <div class="relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-slate-800/90 to-slate-900/90 border border-slate-700/50 shadow-md transition-all duration-300 ease-out cursor-pointer group hover:shadow-emerald-500/20 hover:-translate-y-0.5 hover:border-emerald-400/50" data-wager-id="${wager.id}" role="button" tabindex="0">
                    <!-- Glow effect on hover -->
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="relative flex items-start justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Avatar with subtle shine -->
                            <div class="relative w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-emerald-500/30 transition-shadow duration-300">
                                <span class="text-xl font-bold text-white">
                                    ${wager.name?.substring(0,1)?.toUpperCase() ?? 'W'}
                                </span>
                                <div class="absolute inset-0 rounded-full border-2 border-white/10 group-hover:border-emerald-400/30 transition-colors duration-300"></div>
                            </div>
                            
                            <!-- Content -->
                            <div class="space-y-1">
                                <h3 class="text-lg font-bold text-white group-hover:text-emerald-300 transition-colors duration-200">
                                    ${wager.name}
                                    <span class="absolute inset-0"></span>
                                </h3>
                                ${wager.description ? `
                                    <p class="text-sm text-slate-300/90 group-hover:text-slate-200 transition-colors duration-200 line-clamp-2">
                                        ${wager.description}
                                    </p>
                                ` : ''}
                                <div class="flex items-center space-x-2 text-xs text-slate-400 group-hover:text-slate-300 transition-colors duration-200">
                                    <span class="flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ${wager.ends_human ?? 'No end date'}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- CTA -->
                        <div class="hidden sm:flex items-center justify-center px-3 py-1 rounded-full bg-emerald-900/30 border border-emerald-800/50 text-emerald-300/90 text-xs font-medium group-hover:bg-emerald-800/40 group-hover:border-emerald-700/50 group-hover:text-white transition-all duration-200">
                            View
                            <svg class="ml-1 w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            `).join('');
                // Make cards clickable to open the wager
                searchResultsList.querySelectorAll('[data-wager-id]').forEach(card => {
                    const id = card.getAttribute('data-wager-id');
                    const open = () => {
                        window.location = `/wagers/${id}`;
                    };
                    card.addEventListener('click', open);
                    card.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') {
                            open();
                        }
                    });
                });
            }
            searchResults.classList.remove('hidden');
        }

        function hideSearchResults() {
            searchResults.classList.add('hidden');
            searchResultsList.innerHTML = '';
        }

        function showLoading() {
            searchLoading.classList.remove('hidden');
        }

        function hideLoading() {
            searchLoading.classList.add('hidden');
        }

        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                if (searchInput.value.trim() === '') {
                    hideSearchResults();
                }
            }
        });
    });

    function addFriend(userId) {
        const userCard = document.querySelector(`[data-user-id="${userId}"]`);
        const addButton = userCard.querySelector('.add-friend-btn');
        const originalText = addButton.textContent;

        addButton.disabled = true;
        addButton.textContent = 'Adding...';
        addButton.classList.add('opacity-60');

        fetch('/friends/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    friend_id: userId
                })
            })
            .then(async response => {
                const data = await response.json();

                if (response.ok) {
                    addButton.textContent = 'Added!';
                    addButton.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
                    addButton.classList.add('bg-emerald-700', 'cursor-not-allowed');

                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Friend added successfully!', 'success');
                    }

                    setTimeout(() => {
                        userCard.style.transform = 'translateX(-100%)';
                        userCard.style.opacity = '0';
                        setTimeout(() => userCard.remove(), 300);
                    }, 1000);
                } else {
                    addButton.disabled = false;
                    addButton.textContent = originalText;
                    addButton.classList.remove('opacity-60');

                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Failed to add friend', 'error');
                    }
                }
            })
            .catch(error => {
                addButton.disabled = false;
                addButton.textContent = originalText;
                addButton.classList.remove('opacity-60');

                if (typeof showToast === 'function') {
                    showToast('Network error occurred', 'error');
                }
            });
    }
</script>
