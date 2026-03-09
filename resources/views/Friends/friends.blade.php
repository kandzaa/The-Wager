<x-app-layout>
<div class="select-none min-h-screen bg-slate-50 dark:bg-[#080b0f] text-slate-900 dark:text-white relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none hidden dark:block">
        <div class="absolute top-0 right-1/3 w-[700px] h-[500px] bg-emerald-900/15 rounded-full blur-[130px]"></div>
        <div class="absolute bottom-1/4 left-0 w-[400px] h-[400px] bg-emerald-950/30 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 py-16">

        {{-- Header --}}
        <div class="mb-10 fade-up">
            <p class="text-xs uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-500 font-bold mb-2">Network</p>
            <h1 class="text-4xl font-black tracking-tight text-slate-900 dark:text-white">Friends</h1>
            <div class="mt-4 h-px bg-gradient-to-r from-emerald-500/40 via-emerald-500/10 to-transparent"></div>
        </div>

        {{-- Search --}}
        @include('Friends.user-search')

        {{-- Incoming Requests --}}
        @if(isset($incomingRequests) && $incomingRequests->isNotEmpty())
        <div class="fade-up mb-10" style="animation-delay:120ms">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1.5 h-5 bg-amber-400 rounded-full"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Pending Requests</h2>
                <span class="ml-auto px-2.5 py-0.5 bg-amber-50 dark:bg-amber-900/40 border border-amber-200 dark:border-amber-500/20 text-amber-600 dark:text-amber-400 text-xs font-bold rounded-full">{{ $incomingRequests->count() }}</span>
            </div>
            <div class="space-y-3">
                @foreach($incomingRequests as $req)
                <div class="rounded-2xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-500/10 hover:border-amber-400 dark:hover:border-amber-500/30 transition-all duration-300 p-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-black text-lg shrink-0">
                                {{ strtoupper(substr($req->requester->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 dark:text-white">{{ $req->requester->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">wants to be your friend</p>
                            </div>
                        </div>
                        <button onclick="acceptRequest({{ $req->id }})"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all duration-200 active:scale-95 shrink-0">
                            Accept
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Friends List --}}
        <div class="fade-up" style="animation-delay:200ms">
            @if($friends->isEmpty())
                <div class="rounded-2xl bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/[0.05] p-16 text-center shadow-sm dark:shadow-none">
                    <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-slate-700 dark:text-slate-400 font-semibold mb-1">No friends yet</p>
                    <p class="text-slate-500 text-sm">Search above to find and add people.</p>
                </div>
            @else
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1.5 h-5 bg-emerald-500 rounded-full"></div>
                    <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Your Friends</h2>
                    <span class="ml-auto px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-full">{{ $friends->count() }}</span>
                </div>
                <div class="space-y-3">
                    @foreach($friends as $friend)
                    <div class="group rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] hover:border-emerald-400 dark:hover:border-emerald-500/40 transition-all duration-300 p-5 shadow-sm dark:shadow-none"
                         data-friend-id="{{ $friend->id }}">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="relative shrink-0">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-black text-lg group-hover:scale-105 transition-transform duration-200">
                                        {{ strtoupper(substr($friend->name, 0, 1)) }}
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-400 border-2 border-white dark:border-[#080b0f] rounded-full"></div>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 dark:text-white truncate">{{ $friend->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $friend->email }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-600 mt-0.5">Joined {{ $friend->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('user.show', $friend->id) }}"
                                   class="px-3 py-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-white/[0.05] hover:bg-slate-200 dark:hover:bg-white/[0.08] border border-slate-200 dark:border-white/[0.08] rounded-xl transition-all duration-200">
                                    View
                                </a>
                                <button onclick="removeFriend({{ $friend->id }})"
                                    class="px-3 py-2 text-sm font-semibold text-red-500 dark:text-red-500/70 hover:text-white bg-red-50 dark:bg-red-950/30 hover:bg-red-500 dark:hover:bg-red-600 border border-red-200 dark:border-red-500/20 hover:border-red-500 rounded-xl transition-all duration-200 active:scale-95">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

<style>
.fade-up { animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
</style>

<script>
function acceptRequest(requestId) {
    fetch('/friends/accept', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ request_id: requestId })
    }).then(async res => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(data.message || 'Failed');
        location.reload();
    }).catch(err => alert(err.message || 'Failed to accept request'));
}

function removeFriend(friendId) {
    if (!confirm('Remove this friend?')) return;
    const card = document.querySelector(`[data-friend-id="${friendId}"]`);
    card.style.opacity = '0.4';
    card.style.pointerEvents = 'none';
    fetch('/friends/remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ friend_id: friendId })
    }).then(res => res.json()).then(() => {
        card.style.transform = 'translateX(-20px)';
        card.style.opacity = '0';
        setTimeout(() => location.reload(), 300);
    }).catch(() => {
        card.style.opacity = '1';
        card.style.pointerEvents = 'auto';
        alert('Failed to remove friend.');
    });
}
</script>
</x-app-layout>