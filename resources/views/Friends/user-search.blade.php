<div class="mb-10">
    <div class="text-center mb-8">
        <h2
            class="text-3xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-700 dark:from-emerald-400 dark:to-emerald-500 bg-clip-text text-transparent mb-2">
            Discover New Friends
        </h2>
        <p class="text-slate-600 dark:text-slate-300">
            Connect with amazing people in your community
        </p>
    </div>

    <div class="flex justify-center mb-8">
        <div class="relative w-full max-w-lg">
            <div
                class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 to-emerald-600/20 dark:from-emerald-500/10 dark:to-emerald-600/10 rounded-2xl blur-xl opacity-0 group-focus-within:opacity-100 transition-opacity duration-300">
            </div>

            <div
                class="relative bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm rounded-2xl border-2 border-slate-300/60 dark:border-slate-800 focus-within:border-emerald-500 dark:focus-within:border-emerald-500 transition-all duration-200 shadow-lg">
                <input type="text" id="user-search-input"
                    class="w-full p-4 pl-14 pr-14 bg-transparent text-slate-800 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 rounded-2xl focus:outline-none text-lg"
                    placeholder="Search for people..." autocomplete="off" />

                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400 dark:text-slate-500"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <div id="search-loading" class="hidden absolute right-4 top-1/2 transform -translate-y-1/2">
                    <svg class="animate-spin h-6 w-6 text-emerald-500 dark:text-emerald-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>

                <button id="clear-search"
                    class="hidden absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="search-results" class="hidden max-w-4xl mx-auto">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Search Results
            </h3>
        </div>
        <div id="search-results-list" class="space-y-4"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('user-search-input');
        const searchResults = document.getElementById('search-results');
        const searchResultsList = document.getElementById('search-results-list');
        const searchLoading = document.getElementById('search-loading');
        const clearButton = document.getElementById('clear-search');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(searchTimeout);

            if (query.length > 0) {
                clearButton.classList.remove('hidden');
            } else {
                clearButton.classList.add('hidden');
            }

            if (query.length === 0) {
                hideSearchResults();
                return;
            }

            showLoading();
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });

        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            clearButton.classList.add('hidden');
            hideSearchResults();
            searchInput.focus();
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
                    searchResultsList.innerHTML = `
                        <div class="text-center py-8 bg-rose-50 dark:bg-rose-900/20 rounded-xl border-2 border-rose-200 dark:border-rose-800">
                            <svg class="w-12 h-12 text-rose-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-rose-600 dark:text-rose-400 font-medium">Unable to search users. Please try again.</p>
                        </div>`;
                    searchResults.classList.remove('hidden');
                });
        }

        function displaySearchResults(users) {
            if (users.length === 0) {
                searchResultsList.innerHTML = `
                    <div class="text-center py-12 bg-slate-100 dark:bg-slate-800/50 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600">
                        <svg class="w-16 h-16 text-slate-400 dark:text-slate-500 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-slate-600 dark:text-slate-400 text-lg font-medium mb-2">No users found</p>
                        <p class="text-slate-500 dark:text-slate-500">Try a different search term</p>
                    </div>`;
            } else {
                searchResultsList.innerHTML = users.map(user => `
                    <div class="group bg-slate-50/80 dark:bg-slate-900/40 backdrop-blur-sm rounded-xl shadow-sm hover:shadow-md border border-slate-300/60 dark:border-slate-800 transition-all duration-300 overflow-hidden hover:bg-white/80 dark:hover:bg-slate-900/60 hover:border-slate-400/60 dark:hover:border-slate-700" data-user-id="${user.id}">
                        <!-- Animated background -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-emerald-50/0 to-emerald-100/0 dark:via-emerald-900/0 dark:to-emerald-800/0 group-hover:via-emerald-50/30 group-hover:to-emerald-100/30 dark:group-hover:via-emerald-900/20 dark:group-hover:to-emerald-800/20 transition-all duration-500"></div>
                        
                        <div class="relative p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-5">
                                    <!-- Avatar -->
                                    <div class="relative">
                                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 via-emerald-500 to-emerald-600 dark:from-emerald-500 dark:via-emerald-600 dark:to-emerald-700 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-200">
                                            <span class="text-2xl font-bold text-white">
                                                ${user.initial}
                                            </span>
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-400 dark:bg-emerald-500 border-2 border-white dark:border-slate-800 rounded-full"></div>
                                    </div>
                                    
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-1">${user.name}</h3>
                                        <p class="text-slate-600 dark:text-slate-300 mb-2">${user.email}</p>
                                        <div class="flex items-center text-sm text-slate-500 dark:text-slate-400">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Joined ${user.joined}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <button onclick="addFriend(${user.id})" 
                                            class="add-friend-btn flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-500 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white rounded-xl transition-all duration-200 font-semibold shadow-lg hover:shadow-xl hover:scale-105">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                        </svg>
                                        Add Friend
                                    </button>
                                    
                                    <a href="/user/${user.id}" 
                                       class="flex items-center px-5 py-3 text-slate-600 dark:text-slate-300 hover:text-white border-2 border-slate-300 dark:border-slate-700 rounded-xl hover:bg-slate-600 dark:hover:bg-slate-600 hover:border-slate-600 transition-all duration-200 font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        View Profile
                                    </a>
                                </div>
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
            clearButton.classList.add('hidden');
        }

        function hideLoading() {
            searchLoading.classList.add('hidden');
            if (searchInput.value.trim().length > 0) {
                clearButton.classList.remove('hidden');
            }
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
        const originalContent = addButton.innerHTML;

        addButton.disabled = true;
        addButton.innerHTML = `
            <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Adding...`;
        addButton.classList.add('opacity-70', 'cursor-not-allowed');

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
                    addButton.innerHTML = `
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Added!`;
                    addButton.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
                    addButton.classList.add('bg-emerald-700', 'cursor-not-allowed');

                    setTimeout(() => {
                        userCard.style.transform = 'translateY(-20px)';
                        userCard.style.opacity = '0';
                        setTimeout(() => {
                            userCard.remove();
                        }, 300);
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to add friend');
                }
            })
            .catch(error => {
                addButton.disabled = false;
                addButton.innerHTML = originalContent;
                addButton.classList.remove('opacity-70', 'cursor-not-allowed');

                const errorDiv = document.createElement('div');
                errorDiv.className =
                    'absolute top-full left-0 right-0 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 p-2 rounded-lg text-sm mt-1 z-10';
                errorDiv.textContent = 'Failed to add friend. Please try again.';
                userCard.style.position = 'relative';
                userCard.appendChild(errorDiv);

                setTimeout(() => {
                    errorDiv.remove();
                }, 3000);
            });
    }
</script>
