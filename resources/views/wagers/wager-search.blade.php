<div class="flex justify-center mb-8">
    <div class="relative w-full max-w-md">
        <input type="text" id="wager-search-input"
            class="w-full p-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
            placeholder="Search wagers" autocomplete="off" />
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-3.5" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd" />
        </svg>

        <div id="wager-search-loading" class="hidden absolute right-3 top-3.5">
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
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
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Search Results</h3>
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
                        '<div class="text-center py-4 text-red-600">Unable to search wagers. Please try again.</div>';
                    searchResults.classList.remove('hidden');
                });
        }

        function displaySearchResults(wagers) {
            if (wagers.length === 0) {
                searchResultsList.innerHTML = '<p class="text-gray-500 text-center py-4">No wagers found.</p>';
            } else {
                searchResultsList.innerHTML = wagers.map(wager => `
                <div class="p-4 border rounded-lg hover:shadow-md transition-shadow bg-blue-50 border-blue-200" data-wager-id="${wager.id}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-xl font-semibold text-gray-600">
                                    ${wager.name?.substring(0,1)?.toUpperCase() ?? ''}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">${wager.name}</h3>
                                <p class="text-gray-600 text-sm">${wager.description ?? ''}</p>
                                <p class="text-sm text-gray-500">Ends ${wager.ends_human ?? ''}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-2 text-sm text-green-700 border border-green-700 rounded hover:bg-green-50">View</button>
                        </div>
                    </div>
                </div>
            `).join('');
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
                    addButton.classList.remove('bg-green-600', 'hover:bg-green-700');
                    addButton.classList.add('bg-green-800', 'cursor-not-allowed');

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
