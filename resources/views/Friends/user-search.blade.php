<div class="flex justify-center mb-8">
    <div class="relative w-full max-w-md">
        <input type="text" id="user-search-input"
            class="w-full p-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
            placeholder="Search other users" autocomplete="off" />
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-3.5" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd" />
        </svg>

        <div id="search-loading" class="hidden absolute right-3 top-3.5">
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

<div id="search-results" class="hidden max-w-2xl mx-auto mb-8">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Search Results</h3>
    <div id="search-results-list" class="space-y-4"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('user-search-input');
        const searchResults = document.getElementById('search-results');
        const searchResultsList = document.getElementById('search-results-list');
        const searchLoading = document.getElementById('search-loading');
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
            fetch(`/friends/search?query=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    displaySearchResults(data);
                })
                .catch(error => {
                    hideLoading();
                    console.error('Search error:', error);
                    searchResultsList.innerHTML =
                        '<p class="text-red-500">An error occurred while searching.</p>';
                    searchResults.classList.remove('hidden');
                });
        }

        function displaySearchResults(users) {
            if (users.length === 0) {
                searchResultsList.innerHTML = '<p class="text-gray-500 text-center py-4">No users found.</p>';
            } else {
                searchResultsList.innerHTML = users.map(user => `
                <div class="p-4 border rounded-lg hover:shadow-md transition-shadow bg-blue-50 border-blue-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-xl font-semibold text-gray-600">
                                    ${user.initial}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">${user.name}</h3>
                                <p class="text-gray-600">${user.email}</p>
                                <p class="text-sm text-gray-500">Joined ${user.joined}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="addFriend(${user.id})" 
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                Add Friend
                            </button>
                            <a href="/user/${user.id}" 
                               class="px-4 py-2 text-blue-600 hover:text-blue-700 transition-colors border border-blue-600 rounded hover:bg-blue-50 inline-block">
                                View Profile
                            </a>
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
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding friend.');
            });
    }
</script>
