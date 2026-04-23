<x-app-layout>
<div class="select-none min-h-screen bg-slate-50 dark:bg-[#080b0f] text-slate-900 dark:text-white relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none hidden dark:block">
        <div class="absolute top-0 left-1/3 w-[700px] h-[500px] bg-emerald-900/15 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-emerald-950/20 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 py-14">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-6 fade-up rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20 p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 fade-up rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-500/20 p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Back --}}
        <div class="mb-8 fade-up">
            <a href="{{ route('wagers.index') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Lobby
            </a>
        </div>

        {{-- Header card --}}
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-6 mb-4 shadow-sm dark:shadow-none" style="animation-delay:60ms">
            <div class="flex items-start justify-between gap-4 mb-5">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white mb-2">{{ $wager->name }}</h1>
                    @if($wager->description)
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $wager->description }}</p>
                    @endif
                </div>
                @if($wager->status === 'ended')
                    <span class="shrink-0 px-3 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/20">Ended</span>
                @else
                    <span class="shrink-0 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">Active</span>
                @endif
            </div>

            @php $buyIn = (int)($wager->buy_in ?? 0); @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                {{-- Ends: live countdown --}}
                <div class="rounded-xl p-3 bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/[0.05]">
                    <p class="text-xs uppercase tracking-[0.12em] text-slate-500 font-semibold mb-1">Ends</p>
                    @if($wager->status === 'ended')
                        <p class="text-base font-black text-red-500 dark:text-red-400">Ended</p>
                    @else
                        <p class="text-base font-black text-slate-900 dark:text-white font-mono"
                           id="detail-countdown"
                           data-ends="{{ optional($wager->ending_time)?->timestamp ?? 0 }}">
                            {{ optional($wager->ending_time)?->diffForHumans() ?? 'N/A' }}
                        </p>
                    @endif
                </div>

                {{-- Players --}}
                <div class="rounded-xl p-3 bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/[0.05]">
                    <p class="text-xs uppercase tracking-[0.12em] text-slate-500 font-semibold mb-1">Players</p>
                    <p class="text-base font-black text-slate-900 dark:text-white">
                        {{ $wager->players()->count() }}<span class="text-slate-400 dark:text-slate-600 font-medium text-sm">/{{ $wager->max_players }}</span>
                    </p>
                </div>

                {{-- Buy-in --}}
                <div class="rounded-xl p-3 {{ $buyIn > 0 ? 'bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20' : 'bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/[0.05]' }}">
                    <p class="text-xs uppercase tracking-[0.12em] {{ $buyIn > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500' }} font-semibold mb-1">Buy-in</p>
                    <p class="text-base font-black {{ $buyIn > 0 ? 'text-emerald-800 dark:text-emerald-200' : 'text-slate-900 dark:text-white' }}">
                        {{ $buyIn > 0 ? number_format($buyIn) . ' coins' : 'Free' }}
                    </p>
                </div>

                {{-- Total Pot --}}
                <div class="rounded-xl p-3 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20">
                    <p class="text-xs uppercase tracking-[0.12em] text-emerald-600 dark:text-emerald-400 font-semibold mb-1">Total Pot</p>
                    <p class="text-base font-black text-emerald-800 dark:text-emerald-200" id="pot-display">{{ number_format($wager->pot, 0) }}</p>
                </div>

            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-white/[0.05] flex items-center gap-2 text-xs text-slate-500">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Created by <span class="font-semibold text-slate-700 dark:text-slate-300">{{ optional($wager->creator)->name ?? 'Unknown' }}</span>
            </div>
        </div>

        {{-- Invite section (creator only) --}}
        @if($wager->creator_id == Auth::id() && $wager->status !== 'ended' && !$wager->isFull())
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-5 mb-4 shadow-sm dark:shadow-none" style="animation-delay:100ms">
            <h3 class="text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400 mb-4">Invite Friends</h3>
            @if($friends->isNotEmpty())
                <form action="{{ route('wagers.invite', $wager) }}" method="POST" class="flex gap-2">
                    @csrf
                    <select name="friend_id" required
                        class="flex-1 px-4 py-2.5 text-sm rounded-xl bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all">
                        <option value="" disabled selected>Select a friend</option>
                        @foreach($friends as $friend)
                            <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all duration-200 active:scale-95">
                        Invite
                    </button>
                </form>
            @else
                <p class="text-sm text-slate-500">No friends to invite yet.</p>
            @endif

            @if($pendingInvitations->count() > 0)
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-white/[0.05]">
                    <p class="text-xs uppercase tracking-[0.12em] font-bold text-slate-400 mb-3">Pending</p>
                    <div class="space-y-2">
                        @foreach($pendingInvitations as $inv)
                        <div class="flex items-center justify-between py-2 px-3 rounded-xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/[0.04]">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $inv->email }}</span>
                            <span class="text-xs text-slate-400 dark:text-slate-600">{{ $inv->created_at->diffForHumans() }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        @endif

        {{-- Chart --}}
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-6 mb-4 shadow-sm dark:shadow-none" style="animation-delay:140ms">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1.5 h-5 bg-emerald-500 rounded-full"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Live Bet Distribution</h2>
                <div class="ml-auto w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            </div>
            <div class="flex justify-center">
                <div class="w-full max-w-sm">
                    <canvas id="betChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>

        {{-- Players per choice --}}
        @if($playersByChoice->isNotEmpty())
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] shadow-sm dark:shadow-none mb-4 overflow-hidden" style="animation-delay:160ms"
             x-data="{ open: false }">
            <button @click="open = !open" type="button"
                class="w-full flex items-center gap-3 px-6 py-4 hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                <div class="w-1.5 h-5 bg-slate-400 dark:bg-slate-600 rounded-full shrink-0"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400 flex-1 text-left">Who Bet What</h2>
                <span class="text-xs text-slate-400 dark:text-slate-600 font-medium mr-1" x-text="open ? 'Hide' : 'Show'"></span>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                 class="border-t border-slate-100 dark:border-white/[0.05] divide-y divide-slate-100 dark:divide-white/[0.04]">
                @foreach($wager->choices as $choice)
                @php $bettors = $playersByChoice->get($choice->id, collect()); @endphp
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $choice->label }}</span>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 dark:bg-white/[0.05] text-slate-500 dark:text-slate-400">
                            {{ $bettors->count() }} {{ Str::plural('player', $bettors->count()) }}
                        </span>
                    </div>
                    @if($bettors->isEmpty())
                        <p class="text-xs text-slate-400 dark:text-slate-600 italic">No bets yet</p>
                    @else
                        <div class="space-y-2">
                            @foreach($bettors as $bettor)
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg shrink-0 flex items-center justify-center text-xs font-black
                                    {{ $bettor->user_id === Auth::id()
                                        ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-500/30'
                                        : 'bg-slate-100 dark:bg-white/[0.05] text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/[0.07]' }}">
                                    {{ strtoupper(substr($bettor->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 flex-1 truncate">
                                    {{ $bettor->name }}
                                    @if($bettor->user_id === Auth::id())
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 ml-1">You</span>
                                    @endif
                                </span>
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 tabular-nums shrink-0">
                                    {{ number_format((int) $bettor->total) }} coins
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Betting area --}}
        @if($wager->status !== 'ended')
            @if(!$isJoined)
            <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-8 text-center shadow-sm dark:shadow-none" style="animation-delay:180ms">
                @if($wager->isFull())
                    <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-500/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <p class="font-bold text-slate-900 dark:text-white mb-1">Max Players Reached</p>
                    <p class="text-sm text-slate-500">This wager has reached its maximum capacity of {{ $wager->max_players }} players.</p>
                @else
                    @php $buyIn = (int)($wager->buy_in ?? 0); $canAfford = auth()->user()->balance >= $buyIn; @endphp
                    <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <p class="font-bold text-slate-900 dark:text-white mb-1">Join to participate</p>
                    @if($buyIn > 0)
                        <p class="text-sm text-slate-500 mb-1">Entry costs <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($buyIn) }} coins</span> — added directly to the pot.</p>
                        @if(!$canAfford)
                            <p class="text-xs text-red-500 dark:text-red-400 mb-4">You need {{ number_format($buyIn - auth()->user()->balance) }} more coins to join.</p>
                        @else
                            <p class="text-xs text-slate-400 mb-4">Your balance: {{ number_format(auth()->user()->balance) }} coins</p>
                        @endif
                    @else
                        <p class="text-sm text-slate-500 mb-5">You must join before placing a bet.</p>
                    @endif
                    <form method="POST" action="{{ route('wagers.join', $wager) }}" class="inline-block">
                        @csrf
                        @if($buyIn > 0 && !$canAfford)
                            <button type="button" disabled class="px-6 py-2.5 bg-slate-300 dark:bg-slate-700 text-slate-500 dark:text-slate-400 font-bold rounded-xl cursor-not-allowed opacity-60">
                                Can't Afford
                            </button>
                        @else
                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all duration-200 active:scale-95 inline-flex items-center gap-2">
                                @if($buyIn > 0)
                                    <img src="https://img.icons8.com/?size=100&id=59840&format=png&color=000000" alt="coins" class="w-4 h-4 shrink-0 dark:invert">
                                    Join — {{ number_format($buyIn) }} coins
                                @else
                                    Join Wager
                                @endif
                            </button>
                        @endif
                    </form>
                @endif
            </div>
            @else
            <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] p-6 shadow-sm dark:shadow-none" style="animation-delay:180ms">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1.5 h-5 bg-slate-400 dark:bg-slate-600 rounded-full"></div>
                    <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Place Your Bets</h2>
                </div>
                <form method="POST" action="{{ route('wagers.bet', $wager) }}" id="bet-form" onsubmit="return handleBetSubmit(event)" class="space-y-3">
                    @csrf
                    <div id="bets-container"></div>
                    @foreach($wager->choices as $choice)
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 dark:bg-white/[0.02] border border-slate-100 dark:border-white/[0.05]">
                        <span class="flex-1 text-sm font-semibold text-slate-900 dark:text-white">{{ $choice->label }}</span>
                        <span class="text-xs text-slate-400 dark:text-slate-600 shrink-0">{{ number_format($choice->total_bet, 0) }} bet</span>
                        <input type="number" data-choice-id="{{ $choice->id }}"
                            placeholder="Amount"
                            class="bet-input w-28 px-3 py-2 rounded-xl text-sm bg-white dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all"
                            min="1" step="1"/>
                    </div>
                    @endforeach
                    <button type="submit" id="submit-btn"
                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed mt-2">
                        <span id="submit-text">Place Bet</span>
                        <span id="submit-spinner" class="hidden ml-2">
                            <svg class="animate-spin h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </span>
                    </button>
                </form>

                @if($wager->creator_id == Auth::id())
                <div class="mt-5 pt-5 border-t border-slate-100 dark:border-white/[0.05]">
                    <p class="text-xs uppercase tracking-[0.12em] font-bold text-slate-400 dark:text-slate-600 mb-3 text-center">Creator Controls</p>
                    <div class="flex gap-3">
                        <a href="{{ route('wagers.edit', $wager) }}" class="flex-1">
                            <button type="button" class="w-full py-2.5 px-4 text-sm font-semibold rounded-xl
                                bg-slate-50 dark:bg-white/[0.04] hover:bg-emerald-50 dark:hover:bg-emerald-900/20
                                border border-slate-200 dark:border-white/[0.07] hover:border-emerald-400 dark:hover:border-emerald-500/30
                                text-slate-600 dark:text-slate-400 hover:text-emerald-700 dark:hover:text-emerald-400
                                transition-all duration-200">
                                Edit Wager
                            </button>
                        </a>
                        <button type="button" id="endWagerButton" class="flex-1 py-2.5 px-4 text-sm font-semibold rounded-xl
                            bg-slate-50 dark:bg-white/[0.04] hover:bg-red-50 dark:hover:bg-red-900/20
                            border border-slate-200 dark:border-white/[0.07] hover:border-red-400 dark:hover:border-red-500/30
                            text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400
                            transition-all duration-200">
                            End Wager
                        </button>
                    </div>
                </div>
                @endif
            </div>
            @endif
        @endif

        {{-- Chat --}}
        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] shadow-sm dark:shadow-none overflow-hidden mt-4" style="animation-delay:220ms">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 dark:border-white/[0.05]">
                <div class="w-1.5 h-5 bg-blue-500 rounded-full shrink-0"></div>
                <h2 class="text-sm uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400 flex-1">Wager Chat</h2>
                <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
            </div>

            <div id="chat-messages" class="h-72 overflow-y-auto px-4 py-4 space-y-3 flex flex-col scroll-smooth">
                <p id="chat-empty" class="text-xs text-slate-400 dark:text-slate-600 text-center my-auto">No messages yet — start the trash talk 🗑️</p>
            </div>

            @php $canChat = $isJoined || $wager->creator_id === Auth::id(); @endphp
            @if($canChat && $wager->status !== 'ended')
            <div class="border-t border-slate-100 dark:border-white/[0.05] px-4 py-3 flex gap-2">
                <input id="chat-input" type="text" maxlength="300" placeholder="Say something..."
                    class="flex-1 px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600">
                <button id="chat-send" type="button"
                    class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl transition-all duration-200 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed shrink-0">
                    Send
                </button>
            </div>
            @else
            <div class="border-t border-slate-100 dark:border-white/[0.05] px-6 py-3">
                <p class="text-xs text-slate-400 dark:text-slate-600 text-center">
                    {{ $wager->status === 'ended' ? 'Wager ended — chat is read-only.' : 'Join the wager to chat.' }}
                </p>
            </div>
            @endif
        </div>

    </div>
</div>

@include('wagers.wagers_end')

<style>
.fade-up { animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
</style>

<script>
(function () {
    function pad(n) { return String(n).padStart(2, '0'); }

    function tick() {
        const el = document.getElementById('detail-countdown');
        if (!el) return;
        const ends = parseInt(el.getAttribute('data-ends'), 10);
        const left = ends - Math.floor(Date.now() / 1000);
        if (left <= 0) { el.textContent = 'Ended'; el.classList.add('text-red-500', 'dark:text-red-400'); return; }
        const d = Math.floor(left / 86400);
        const h = Math.floor((left % 86400) / 3600);
        const m = Math.floor((left % 3600) / 60);
        const s = left % 60;
        el.textContent = d > 0 ? `${d}d ${pad(h)}h ${pad(m)}m` : `${pad(h)}h ${pad(m)}m ${pad(s)}s`;
    }

    document.addEventListener('DOMContentLoaded', () => { tick(); setInterval(tick, 1000); });
})();
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let betChart = null, initialData = [], pollInterval = null;
    const chartColors = ['#10B981','#3B82F6','#F59E0B','#EF4444','#8B5CF6','#06B6D4','#84CC16','#F97316','#EC4899','#6B7280'];

    @php
        $chartData = $wager->choices->map(fn($c) => ['id'=>$c->id,'label'=>$c->label,'total_bet'=>(float)$c->total_bet])->toArray();
    @endphp
    initialData = @json($chartData);

    function renderChart(data) {
        const canvas = document.getElementById('betChart');
        if (!canvas) return;
        let labels = data.map(d => d.label || 'Unknown');
        let values = data.map(d => parseFloat(d.total_bet) || 0);
        const total = values.reduce((a,b) => a+b, 0);
        if (total === 0) { labels = ['No bets yet']; values = [1]; }
        if (betChart) betChart.destroy();
        const isDark = document.documentElement.classList.contains('dark');
        betChart = new Chart(canvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: total === 0 ? ['#E5E7EB'] : chartColors.slice(0, values.length),
                    borderWidth: 3,
                    borderColor: isDark ? '#080b0f' : '#f8fafc',
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: true, position: 'bottom', labels: { color: isDark ? '#94a3b8' : '#475569', padding: 15, font: { size: 12, weight: '500' } } },
                    tooltip: { callbacks: { label: ctx => {
                        if (total === 0) return 'No bets yet';
                        const pct = Math.round((ctx.raw / ctx.dataset.data.reduce((a,b)=>a+b,0)) * 100);
                        return `${ctx.label}: ${ctx.raw.toLocaleString()} (${pct}%)`;
                    }}}
                }
            }
        });
    }

    function updateChartWithUserBets() {
        const userBets = {};
        document.querySelectorAll('.bet-input').forEach(i => {
            userBets[parseInt(i.dataset.choiceId)] = parseFloat(i.value) || 0;
        });
        renderChart(initialData.map(c => ({ ...c, total_bet: (parseFloat(c.total_bet)||0) + (userBets[c.id]||0) })));
    }

    async function fetchWagerStats() {
        try {
            const r = await fetch(`/wagers/{{ $wager->id }}/stats`);
            if (!r.ok) throw new Error();
            return await r.json();
        } catch { return null; }
    }

    let lastDistHash = null;

    function distHash(dist) {
        return dist.map(i => `${i.id}:${parseFloat(i.total_bet)||0}`).join('|');
    }

    function updateUIWithServerData(data) {
        if (!data) return;
        const pot = document.getElementById('pot-display');
        if (pot && data.pot !== undefined) pot.textContent = Math.round(data.pot).toLocaleString();
        if (data.distribution) {
            const newData = data.distribution.map(i => ({ id: i.id, label: i.label, total_bet: parseFloat(i.amount)||0 }));
            const hash = distHash(newData);
            if (hash !== lastDistHash) {
                lastDistHash = hash;
                initialData = newData;
                renderChart(initialData);
            }
        }
    }

    async function handleBetSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('#submit-btn');
        const text = form.querySelector('#submit-text');
        const spinner = form.querySelector('#submit-spinner');
        const container = form.querySelector('#bets-container');
        btn.disabled = true; text.textContent = 'Placing...'; spinner.classList.remove('hidden');
        container.innerHTML = '';
        const bets = [];
        document.querySelectorAll('.bet-input').forEach(input => {
            const amount = parseFloat(input.value) || 0;
            if (amount > 0) {
                const ci = document.createElement('input'); ci.type='hidden'; ci.name=`bets[${bets.length}][choice_id]`; ci.value=input.dataset.choiceId; container.appendChild(ci);
                const ai = document.createElement('input'); ai.type='hidden'; ai.name=`bets[${bets.length}][amount]`; ai.value=amount; container.appendChild(ai);
                bets.push({ choice_id: input.dataset.choiceId, amount });
                input.value = '';
            }
        });
        if (!bets.length) { btn.disabled=false; text.textContent='Place Bet'; spinner.classList.add('hidden'); return; }
        try {
            const res = await fetch(form.action, { method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}, body: new FormData(form) });
            const result = await res.json();
            if (!res.ok) throw new Error(result.message || 'Failed');
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-lg z-50 text-sm font-semibold';
            toast.textContent = 'Bets placed!';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
            const stats = await fetchWagerStats();
            if (stats) updateUIWithServerData(stats);
        } catch (err) {
            alert(err.message || 'Error placing bets');
        } finally {
            btn.disabled=false; text.textContent='Place Bet'; spinner.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        lastDistHash = distHash(initialData);
        renderChart(initialData);
        document.querySelectorAll('.bet-input').forEach(i => i.addEventListener('input', updateChartWithUserBets));
        pollInterval = setInterval(async () => { const d = await fetchWagerStats(); if (d) updateUIWithServerData(d); }, 5000);
        document.addEventListener('visibilitychange', () => { if (document.hidden) clearInterval(pollInterval); else pollInterval = setInterval(async () => { const d = await fetchWagerStats(); if (d) updateUIWithServerData(d); }, 5000); });
    });
</script>
<script>
(function () {
    const WAGER_ID  = {{ $wager->id }};
    const MY_ID     = {{ Auth::id() }};
    const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
    let lastId      = 0;
    let sending     = false;
    const rendered  = new Set();

    function escHtml(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function fmtTime(ts) {
        const d = new Date(ts.replace(' ', 'T') + '+00:00');
        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function makeRow(msg) {
        const isMe = msg.user_id === MY_ID;
        const wrap = document.createElement('div');
        wrap.className = 'flex items-end gap-2 ' + (isMe ? 'flex-row-reverse' : '');
        wrap.innerHTML = `
            <div class="w-7 h-7 rounded-lg shrink-0 flex items-center justify-center text-xs font-black
                ${isMe
                    ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400'
                    : 'bg-slate-100 dark:bg-white/[0.05] text-slate-500 dark:text-slate-400'}">
                ${escHtml(msg.name.charAt(0).toUpperCase())}
            </div>
            <div class="max-w-[72%] flex flex-col gap-0.5 ${isMe ? 'items-end' : 'items-start'}">
                <span class="text-[10px] text-slate-400 dark:text-slate-600 px-1">
                    ${isMe ? 'You' : escHtml(msg.name)} · ${fmtTime(msg.created_at)}
                </span>
                <div class="px-3 py-2 rounded-2xl text-sm leading-relaxed break-words
                    ${isMe
                        ? 'bg-blue-600 text-white rounded-br-sm'
                        : 'bg-slate-100 dark:bg-white/[0.06] text-slate-800 dark:text-slate-200 rounded-bl-sm'}">
                    ${escHtml(msg.message)}
                </div>
            </div>`;
        return wrap;
    }

    async function fetchMessages() {
        try {
            const r = await fetch(`/wagers/${WAGER_ID}/chat?after=${lastId}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!r.ok) return;
            const msgs = await r.json();
            if (!msgs.length) return;

            const box   = document.getElementById('chat-messages');
            const empty = document.getElementById('chat-empty');
            const atBottom = box.scrollHeight - box.scrollTop <= box.clientHeight + 60;

            if (empty) empty.remove();

            msgs.forEach(msg => {
                if (rendered.has(msg.id)) return;
                rendered.add(msg.id);
                box.appendChild(makeRow(msg));
                if (msg.id > lastId) lastId = msg.id;
            });

            if (atBottom) box.scrollTop = box.scrollHeight;
        } catch {}
    }

    async function sendMessage() {
        if (sending) return;
        const input = document.getElementById('chat-input');
        const btn   = document.getElementById('chat-send');
        if (!input) return;
        const text = input.value.trim();
        if (!text) return;

        sending = true;
        input.disabled = true;
        btn.disabled   = true;

        try {
            const r = await fetch(`/wagers/${WAGER_ID}/chat`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: text }),
            });
            if (!r.ok) { const e = await r.json(); alert(e.error || 'Failed to send.'); return; }
            input.value = '';
            await fetchMessages();
        } catch { alert('Failed to send message.'); }
        finally {
            sending        = false;
            input.disabled = false;
            btn.disabled   = false;
            input.focus();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchMessages();
        setInterval(fetchMessages, 2000);

        const btn   = document.getElementById('chat-send');
        const input = document.getElementById('chat-input');
        if (btn)   btn.addEventListener('click', sendMessage);
        if (input) input.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
        });

        // Scroll to bottom on first load after messages render
        const box = document.getElementById('chat-messages');
        if (box) setTimeout(() => { box.scrollTop = box.scrollHeight; }, 100);
    });
})();
</script>
</x-app-layout>