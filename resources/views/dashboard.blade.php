<x-app-layout>
<div class="select-none min-h-screen bg-slate-50 dark:bg-[#080b0f] text-slate-900 dark:text-white relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none hidden dark:block">
        <div class="absolute -top-20 left-1/4 w-[800px] h-[500px] bg-emerald-900/20 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-950/30 rounded-full blur-[120px]"></div>
    </div>

    {{-- Activity banner (hidden until polling detects new activity) --}}
    <div id="activity-banner" style="display:none" class="fixed top-0 left-0 right-0 z-50 justify-center pt-4 px-4 pointer-events-none">
        <div class="pointer-events-auto flex items-center gap-3 px-5 py-3 rounded-2xl shadow-2xl border
                    bg-white dark:bg-[#0d1117] border-emerald-400/40 dark:border-emerald-500/30
                    text-slate-900 dark:text-white"
             style="animation: bannerIn .4s cubic-bezier(.16,1,.3,1) both">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
            <p class="text-sm font-semibold" id="activity-banner-text">You have new activity</p>
            <button onclick="window.location.reload()"
                class="ml-1 px-3 py-1 text-xs font-bold rounded-lg bg-emerald-500 hover:bg-emerald-400 text-white transition-colors">
                Refresh
            </button>
            <button onclick="document.getElementById('activity-banner').style.display='none'"
                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-14">

        {{-- Greeting --}}
        <div class="mb-12 fade-up">
            <p class="text-xs uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-500 font-bold mb-1">Welcome back</p>
            <h1 class="text-5xl font-black tracking-tight text-slate-900 dark:text-white">
                {{ Auth::user()->name }}<span class="text-emerald-500">.</span>
            </h1>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            @php
                $statCards = [
                    ['label' => 'Total Users',  'value' => $usersCount ?? 0,  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['label' => 'Total Wagers', 'value' => $wagersCount ?? 0, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['label' => 'Coins Wagered', 'value' => number_format($betsCount ?? 0), 'icon' => null, 'img' => 'https://img.icons8.com/?size=100&id=59840&format=png&color=000000'],
                ];
            @endphp
            @foreach($statCards as $i => $card)
            <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-5 hover:border-emerald-400 dark:hover:border-emerald-500/30 transition-all duration-300 shadow-sm dark:shadow-none" style="animation-delay:{{ ($i+1)*80 }}ms">
                <div class="flex items-start justify-between mb-4">
                    <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-semibold">{{ $card['label'] }}</p>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center">
                        @if(!empty($card['img']))
                            <img src="{{ $card['img'] }}" alt="icon" class="w-4 h-4 dark:invert">
                        @else
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <p class="text-4xl font-black text-slate-900 dark:text-white">{{ $card['value'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Incoming coin transfer requests --}}
        @if(isset($incomingTransfers) && $incomingTransfers->isNotEmpty())
        <div class="fade-up mb-8" style="animation-delay:180ms">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1.5 h-5 bg-emerald-400 rounded-full"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Coin Requests</h2>
                <span class="ml-auto px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-full">{{ $incomingTransfers->count() }}</span>
            </div>
            <div class="space-y-3">
                @foreach($incomingTransfers as $transfer)
                <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-500/10 hover:border-emerald-400 dark:hover:border-emerald-500/30 transition-all duration-300 p-4" id="transfer-{{ $transfer->id }}">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-black text-lg shrink-0">
                                {{ strtoupper(substr($transfer->sender->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 dark:text-white">
                                    {{ $transfer->sender->name }}
                                    <span class="text-emerald-600 dark:text-emerald-400 font-black">+{{ number_format($transfer->amount) }}</span>
                                    <span class="text-slate-500 font-normal text-sm">coins</span>
                                </p>
                                @if($transfer->message)
                                <p class="text-xs text-slate-500 mt-0.5 italic">"{{ $transfer->message }}"</p>
                                @else
                                <p class="text-xs text-slate-500 mt-0.5">sent you coins · {{ $transfer->created_at->diffForHumans() }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <form action="{{ route('transfers.accept', $transfer) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all duration-200 active:scale-95">
                                    Accept
                                </button>
                            </form>
                            <form action="{{ route('transfers.decline', $transfer) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-slate-100 dark:bg-white/[0.05] hover:bg-red-50 dark:hover:bg-red-900/20 border border-slate-200 dark:border-white/[0.08] hover:border-red-300 dark:hover:border-red-500/30 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 text-sm font-bold rounded-xl transition-all duration-200 active:scale-95">
                                    Decline
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($pendingFriendRequests) && $pendingFriendRequests->isNotEmpty())
        <div class="fade-up mb-8" style="animation-delay:200ms">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1.5 h-5 bg-blue-400 rounded-full"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Friend Requests</h2>
                <span class="ml-auto px-2.5 py-0.5 bg-blue-50 dark:bg-blue-900/40 border border-blue-200 dark:border-blue-500/20 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-full">{{ $pendingFriendRequests->count() }}</span>
            </div>
            <div class="space-y-3">
                @foreach($pendingFriendRequests as $req)
                <div class="rounded-2xl bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-500/10 hover:border-blue-400 dark:hover:border-blue-500/30 transition-all duration-300 p-4" id="friend-req-{{ $req->id }}">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-black text-lg shrink-0">
                                {{ strtoupper(substr($req->requester->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 dark:text-white">{{ $req->requester->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">wants to be your friend</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button onclick="acceptFriendRequest({{ $req->id }})"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all duration-200 active:scale-95">
                                Accept
                            </button>
                            <button onclick="declineFriendRequest({{ $req->id }})"
                                class="px-4 py-2 bg-slate-100 dark:bg-white/[0.05] hover:bg-red-50 dark:hover:bg-red-900/20 border border-slate-200 dark:border-white/[0.08] hover:border-red-300 dark:hover:border-red-500/30 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 text-sm font-bold rounded-xl transition-all duration-200 active:scale-95">
                                Decline
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($pendingInvitations) && $pendingInvitations->isNotEmpty())
        <div class="fade-up mb-8" style="animation-delay:240ms">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1.5 h-5 bg-amber-400 rounded-full"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Wager Invitations</h2>
                <span class="ml-auto px-2.5 py-0.5 bg-amber-50 dark:bg-amber-900/40 border border-amber-200 dark:border-amber-500/20 text-amber-600 dark:text-amber-400 text-xs font-bold rounded-full">{{ $pendingInvitations->count() }}</span>
            </div>
            <div class="space-y-3">
                @foreach($pendingInvitations as $invitation)
                <div class="rounded-2xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-500/10 hover:border-amber-400 dark:hover:border-amber-500/25 transition-all duration-300 p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.12em] font-bold text-amber-600/70 dark:text-amber-500/60 mb-0.5">Wager Invitation</p>
                            <h4 class="font-bold text-slate-900 dark:text-white truncate">{{ $invitation->wager->name }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">
                                From <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $invitation->wager->creator->name }}</span>
                                · Expires {{ $invitation->expires_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('invitations.accept', $invitation->token) }}"
                               class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all duration-200 active:scale-95">
                                Accept
                            </a>
                            <a href="{{ route('invitations.decline', $invitation->token) }}"
                               class="px-4 py-2 bg-slate-100 dark:bg-white/[0.05] hover:bg-slate-200 dark:hover:bg-white/[0.08] border border-slate-200 dark:border-white/[0.08] text-slate-600 dark:text-slate-400 text-sm font-bold rounded-xl transition-all duration-200 active:scale-95">
                                Decline
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($joinedWagers) && $joinedWagers->isNotEmpty())
        <div class="fade-up" style="animation-delay:320ms">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1.5 h-5 bg-emerald-500 rounded-full"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Active Wagers</h2>
                <span class="ml-auto px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-full">{{ $joinedWagers->count() }}</span>
            </div>
            <div class="space-y-3">
                @foreach($joinedWagers as $player)
                    @php $wager = $player->wager; @endphp
                    <a href="{{ route('wagers.show', $wager) }}" class="group block">
                        <div class="rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] group-hover:border-emerald-400 dark:group-hover:border-emerald-500/40 transition-all duration-300 p-5 shadow-sm dark:shadow-none">
                            <div class="flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-300 transition-colors truncate">{{ $wager->name }}</h4>
                                    <div class="flex items-center gap-4 mt-1.5 flex-wrap">
                                        <span class="text-xs text-slate-600 dark:text-slate-400">{{ $wager->creator->name }}</span>
                                        <span class="text-xs text-slate-300 dark:text-slate-600">·</span>
                                        <span class="text-xs text-slate-500">{{ $wager->players_count }}/{{ $wager->max_players }} players</span>
                                        <span class="text-xs text-slate-300 dark:text-slate-600">·</span>
                                        <span class="text-xs text-slate-500">Ends {{ $wager->ending_time->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="shrink-0 w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center group-hover:bg-emerald-600 group-hover:border-emerald-500 transition-all duration-300">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        @if(
            (!isset($joinedWagers) || $joinedWagers->isEmpty()) &&
            (!isset($pendingInvitations) || $pendingInvitations->isEmpty()) &&
            (!isset($pendingFriendRequests) || $pendingFriendRequests->isEmpty()) &&
            (!isset($incomingTransfers) || $incomingTransfers->isEmpty())
        )
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/[0.05] p-16 text-center shadow-sm dark:shadow-none" style="animation-delay:240ms">
            <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center">
                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <p class="text-slate-700 dark:text-slate-400 font-semibold mb-1">Nothing going on yet</p>
            <p class="text-slate-500 text-sm">Join a wager or create one to get started.</p>
        </div>
        @endif

    </div>
</div>

{{-- Toast notification stack --}}
@if(isset($resolvedToasts) && $resolvedToasts->isNotEmpty())
<div id="toast-stack" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 pointer-events-none" style="max-width:340px">
    @foreach($resolvedToasts as $toast)
    <div class="toast-item pointer-events-auto flex items-start gap-3 px-4 py-3.5 rounded-2xl shadow-xl border
        {{ $toast->status === 'accepted'
            ? 'bg-emerald-600 border-emerald-500/40 text-white'
            : 'bg-slate-800 border-white/[0.08] text-white' }}"
        data-index="{{ $loop->index }}">
        <div class="mt-0.5 shrink-0">
            @if($toast->status === 'accepted')
            <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            @else
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            @if($toast->status === 'accepted')
            <p class="text-sm font-bold">{{ $toast->recipient->name }} accepted your transfer!</p>
            <p class="text-xs text-emerald-200 mt-0.5">{{ number_format($toast->amount) }} coins delivered.</p>
            @else
            <p class="text-sm font-bold">{{ $toast->recipient->name }} declined your transfer.</p>
            <p class="text-xs text-slate-400 mt-0.5">{{ number_format($toast->amount) }} coins refunded to you.</p>
            @endif
        </div>
        <button onclick="dismissToast(this.parentElement)" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endforeach
</div>
@endif

<style>
.fade-up { animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
.toast-item { animation: toastIn 0.4s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes toastIn { from{opacity:0;transform:translateX(40px)} to{opacity:1;transform:translateX(0)} }
@keyframes bannerIn { from{opacity:0;transform:translateY(-16px)} to{opacity:1;transform:translateY(0)} }
</style>

<script>
// Auto-dismiss toasts after 6 seconds
document.querySelectorAll('.toast-item').forEach((el, i) => {
    el.style.animationDelay = (i * 120) + 'ms';
    setTimeout(() => dismissToast(el), 6000 + i * 120);
});

function dismissToast(el) {
    if (!el || el._dismissing) return;
    el._dismissing = true;
    el.style.transition = 'opacity 0.3s, transform 0.3s';
    el.style.opacity = '0';
    el.style.transform = 'translateX(40px)';
    setTimeout(() => {
        el.remove();
        const stack = document.getElementById('toast-stack');
        if (stack && !stack.children.length) stack.remove();
    }, 300);
}

const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function acceptFriendRequest(requestId) {
    fetch('/friends/accept', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ request_id: requestId })
    }).then(async res => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(data.message || 'Failed');
        const card = document.getElementById('friend-req-' + requestId);
        if (card) {
            card.style.transition = 'all 0.3s';
            card.style.opacity = '0';
            card.style.transform = 'translateY(-8px)';
            setTimeout(() => {
                card.remove();
                checkEmptySections();
            }, 300);
        }
    }).catch(err => alert(err.message || 'Failed to accept request'));
}

function declineFriendRequest(requestId) {
    fetch('/friends/decline', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ request_id: requestId })
    }).then(res => res.json()).then(() => {
        const card = document.getElementById('friend-req-' + requestId);
        if (card) {
            card.style.transition = 'all 0.3s';
            card.style.opacity = '0';
            card.style.transform = 'translateY(-8px)';
            setTimeout(() => {
                card.remove();
                checkEmptySections();
            }, 300);
        }
    }).catch(() => alert('Failed to decline request.'));
}

function checkEmptySections() {
    document.querySelectorAll('[id^="friend-req-"]').length === 0 && location.reload();
}

// ── Activity polling ─────────────────────────────────────────────────────────
const baseline = {
    friend_requests: {{ $pendingFriendRequests->count() }},
    transfers:       {{ $incomingTransfers->count() }},
    invitations:     {{ $pendingInvitations->count() }},
    resolved:        0,
};

const banner     = document.getElementById('activity-banner');
const bannerText = document.getElementById('activity-banner-text');
let bannerShown  = false;

function showBanner(label) {
    if (bannerShown) return;
    bannerShown = true;
    bannerText.textContent = label;
    banner.style.display = 'flex';
    banner.firstElementChild.style.animation = 'bannerIn .4s cubic-bezier(.16,1,.3,1) both';
}

async function pollActivity() {
    try {
        const res  = await fetch('/dashboard/activity', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) return;
        const data = await res.json();

        const labels = [];
        if (data.friend_requests > baseline.friend_requests) labels.push('friend request');
        if (data.transfers       > baseline.transfers)       labels.push('coin transfer');
        if (data.invitations     > baseline.invitations)     labels.push('wager invitation');
        if (data.resolved        > baseline.resolved)        labels.push('transfer response');

        if (labels.length) {
            const noun = labels.length === 1 ? `a new ${labels[0]}` : `${labels.length} new notifications`;
            showBanner(`You have ${noun} — refresh to see it`);
        }
    } catch (_) {}
}

setInterval(pollActivity, 30_000);
</script>
</x-app-layout>