import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Ensure theme stays in sync across tabs and after client-side navigations
(() => {
    const media = window.matchMedia('(prefers-color-scheme: dark)');

    function applyThemeFromState() {
        const saved = localStorage.getItem('theme');
        const shouldDark = saved ? saved === 'dark' : media.matches;
        document.documentElement.classList.toggle('dark', shouldDark);
    }

    // Apply on JS bootstrap (useful for client-side navigations)
    applyThemeFromState();

    // Respond when theme changes in another tab/window
    window.addEventListener('storage', (e) => {
        if (e.key === 'theme') {
            applyThemeFromState();
        }
    });

    // If system theme changes and the user hasn't explicitly chosen, update
    media.addEventListener('change', () => {
        if (!localStorage.getItem('theme')) {
            applyThemeFromState();
        }
    });

    // Wager search functionality
    const searchInput = document.getElementById('wager-search-input');
    if (searchInput) {
        // Store the original wagers HTML to restore when search is cleared
        const originalWagers = document.querySelector('.wagers-list')?.innerHTML;
        
        searchInput.addEventListener('input', debounce(function(e) {
            const searchTerm = e.target.value.trim();
            const wagersContainer = document.querySelector('.wagers-list');
            
            if (!wagersContainer) return;
            
            if (searchTerm.length > 2) {  // Only search if 3 or more characters
                fetch(`/wagers/search?q=${encodeURIComponent(searchTerm)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    // Update the wagers list with search results
                    if (data.wagers && data.wagers.length > 0) {
                        wagersContainer.innerHTML = data.wagers.map(wager => `
                            <div class="wager-item mb-4" data-name="${wager.name}" data-creator="${wager.creator}">
                                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow duration-200">
                                    <h3 class="font-medium text-slate-900 dark:text-white">${wager.name}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">${wager.description || 'No description'}</p>
                                    <div class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                        <span>Created by: ${wager.creator}</span>
                                        <span class="mx-2">•</span>
                                        <span>Ends: ${new Date(wager.ending_time).toLocaleString()}</span>
                                    </div>
                                    ${wager.choices && wager.choices.length > 0 ? 
                                        `<div class="mt-3">
                                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Choices:</span>
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                ${wager.choices.map(choice => 
                                                    `<span class="px-2 py-1 text-xs rounded bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                                                        ${choice.label}
                                                    </span>`
                                                ).join('')}
                                            </div>
                                        </div>` : ''
                                    }
                                </div>
                            </div>
                        `).join('');
                    } else {
                        wagersContainer.innerHTML = `
                            <div class="rounded-lg border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30 p-12 text-center">
                                <p class="text-base text-slate-600 dark:text-slate-300 mb-2">No wagers found</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Try a different search term</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    // Restore original content on error
                    if (originalWagers) {
                        wagersContainer.innerHTML = originalWagers;
                    }
                });
            } else if (searchTerm.length === 0 && originalWagers) {
                // If search is cleared, restore original content
                wagersContainer.innerHTML = originalWagers;
            }
        }, 300));
    }

    // Simple debounce function
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
})();
