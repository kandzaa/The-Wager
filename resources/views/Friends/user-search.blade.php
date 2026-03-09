<div class="select-none mb-10 fade-up" style="animation-delay:60ms">

    {{-- Search input --}}
    <div class="relative mb-8">
        <div class="relative">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" id="user-search-input"
                placeholder="Search for people..."
                autocomplete="off"
                class="w-full pl-12 pr-12 py-3.5 rounded-2xl text-sm font-medium
                       bg-white dark:bg-white/[0.04]
                       border border-slate-200 dark:border-white/[0.08]
                       text-slate-900 dark:text-white
                       placeholder-slate-400 dark:placeholder-slate-600
                       focus:outline-none focus:border-emerald-500 dark:focus:border-emerald-500/60
                       focus:ring-2 focus:ring-emerald-500/20
                       shadow-sm dark:shadow-none
                       transition-all duration-200"/>
            <div id="search-loading" class="hidden absolute right-4 top-1/2 -translate-y-1/2">
                <svg class="animate-spin w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </div>
            <button id="clear-search" class="hidden absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Results --}}
    <div id="search-results" class="hidden">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-1.5 h-5 bg-slate-400 dark:bg-slate-600 rounded-full"></div>
            <h3 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Search Results</h3>
        </div>
        <div id="search-results-list" class="space-y-3"></div>
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
        clearButton.classList.toggle('hidden', query.length === 0);
        if (!query) { hideSearchResults(); return; }
        showLoading();
        searchTimeout = setTimeout(() => performSearch(query), 300);
    });

    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        clearButton.classList.add('hidden');
        hideSearchResults();
        searchInput.focus();
    });

    function performSearch(query) {
        fetch(`/friends/search?query=${encodeURIComponent(query)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(data => { hideLoading(); displaySearchResults(data); })
        .catch(() => {
            hideLoading();
            searchResultsList.innerHTML = `
                <div class="rounded-2xl bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-500/20 p-8 text-center">
                    <p class="text-red-600 dark:text-red-400 text-sm font-medium">Search failed. Please try again.</p>
                </div>`;
            searchResults.classList.remove('hidden');
        });
    }

    function displaySearchResults(users) {
        if (users.length === 0) {
            searchResultsList.innerHTML = `
                <div class="rounded-2xl bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/[0.05] p-10 text-center">
                    <div class="w-10 h-10 mx-auto mb-3 rounded-xl bg-slate-100 dark:bg-slate-800/60 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">No users found</p>
                    <p class="text-slate-400 dark:text-slate-600 text-xs mt-1">Try a different search term</p>
                </div>`;
        } else {
            searchResultsList.innerHTML = users.map(user => `
                <div class="group rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] hover:border-emerald-400 dark:hover:border-emerald-500/40 transition-all duration-300 p-5 shadow-sm dark:shadow-none"
                     data-user-id="${user.id}">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="relative shrink-0">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-black text-lg group-hover:scale-105 transition-transform duration-200">
                                    ${user.initial}
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-400 border-2 border-white dark:border-[#080b0f] rounded-full"></div>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-slate-900 dark:text-white truncate">${user.name}</p>
                                <p class="text-xs text-slate-500 truncate">${user.email}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-600 mt-0.5">Joined ${user.joined}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button onclick="requestFriend(${user.id})"
                                class="add-friend-btn px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all duration-200 active:scale-95 hover:shadow-lg hover:shadow-emerald-900/30">
                                + Add
                            </button>
                            <a href="/user/${user.id}"
                               class="px-3 py-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-white/[0.05] hover:bg-slate-200 dark:hover:bg-white/[0.08] border border-slate-200 dark:border-white/[0.08] rounded-xl transition-all duration-200">
                                View
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
    function showLoading() { searchLoading.classList.remove('hidden'); clearButton.classList.add('hidden'); }
    function hideLoading() {
        searchLoading.classList.add('hidden');
        if (searchInput.value.trim()) clearButton.classList.remove('hidden');
    }
});

function requestFriend(userId) {
    const card = document.querySelector(`[data-user-id="${userId}"]`);
    const btn = card.querySelector('.add-friend-btn');
    btn.disabled = true;
    btn.textContent = 'Sending...';
    btn.classList.add('opacity-60', 'cursor-not-allowed');

    fetch('/friends/request', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ recipient_id: userId })
    })
    .then(async res => {
        const data = await res.json();
        if (res.ok) {
            btn.textContent = '✓ Sent';
            btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
            btn.classList.add('bg-emerald-800', 'dark:bg-emerald-900/60');
            setTimeout(() => {
                card.style.transition = 'all 0.3s';
                card.style.opacity = '0';
                card.style.transform = 'translateY(-8px)';
                setTimeout(() => card.remove(), 300);
            }, 1200);
        } else throw new Error(data.message || 'Failed');
    })
    .catch(err => {
        btn.disabled = false;
        btn.textContent = '+ Add';
        btn.classList.remove('opacity-60', 'cursor-not-allowed');
        alert(err.message || 'Failed to send request.');
    });
}
</script>