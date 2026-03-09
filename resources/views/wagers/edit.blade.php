<x-app-layout>
<div class="select-none min-h-screen bg-slate-50 dark:bg-[#080b0f] text-slate-900 dark:text-white relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none hidden dark:block">
        <div class="absolute top-0 left-1/3 w-[600px] h-[500px] bg-emerald-900/15 rounded-full blur-[130px]"></div>
    </div>

    <div class="relative z-10 max-w-2xl mx-auto px-6 py-14">

        <div class="mb-8 fade-up">
            <a href="{{ route('wagers.show', $wager) }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Wager
            </a>
        </div>

        <div class="fade-up mb-8" style="animation-delay:40ms">
            <p class="text-xs uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-500 font-bold mb-2">Edit</p>
            <h1 class="text-3xl font-black tracking-tight">{{ $wager->name }}</h1>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="fade-up mb-4 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error') || $errors->any())
            <div class="fade-up mb-4 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-300 text-sm">
                @if(session('error')) {{ session('error') }} @endif
                @if($errors->any())
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                @endif
            </div>
        @endif

        <div class="fade-up rounded-2xl bg-white dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.07] overflow-hidden shadow-sm dark:shadow-none" style="animation-delay:80ms">
            <form id="editWagerForm" action="{{ route('wagers.update', $wager) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-5">

                    {{-- Name --}}
                    <div>
                        <label class="block text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400 mb-2">Wager Name *</label>
                        <input type="text" name="name" value="{{ old('name', $wager->name) }}" required maxlength="255"
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all"/>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400 mb-2">Description</label>
                        <textarea name="description" rows="3" maxlength="1000"
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all resize-none">{{ old('description', $wager->description) }}</textarea>
                    </div>

                    {{-- Grid fields --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400 mb-2">Max Players *</label>
                            <input type="number" name="max_players" min="2" max="100" value="{{ old('max_players', $wager->max_players) }}" required
                                class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all"/>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400 mb-2">Privacy *</label>
                            <select name="privacy" required
                                class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all">
                                <option value="public" {{ old('privacy',$wager->privacy)==='public'?'selected':'' }}>Public</option>
                                <option value="private" {{ old('privacy',$wager->privacy)==='private'?'selected':'' }}>Private</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400 mb-2">Start Time *</label>
                            <input type="datetime-local" name="starting_time" required
                                value="{{ old('starting_time', \Carbon\Carbon::parse($wager->starting_time)->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all"/>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400 mb-2">End Time *</label>
                            <input type="datetime-local" name="ending_time" required
                                value="{{ old('ending_time', \Carbon\Carbon::parse($wager->ending_time)->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all"/>
                        </div>
                    </div>

                    {{-- Choices --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-xs uppercase tracking-[0.15em] font-bold text-slate-500 dark:text-slate-400">Choices * (2–10)</label>
                            <button type="button" onclick="addChoice()" id="addChoiceBtn"
                                class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                                + Add
                            </button>
                        </div>
                        <div id="choicesContainer" class="space-y-2">
                            @php
                                $choices = old('choices', []);
                                if (empty($choices)) $choices = $wager->choices ?? [];
                                if (empty($choices) || count($choices) == 0) $choices = [null, null];
                            @endphp
                            @foreach($choices as $index => $choice)
                            @php
                                $choiceId = is_array($choice) ? ($choice['id'] ?? '') : (is_object($choice) ? $choice->id : '');
                                $choiceLabel = is_array($choice) ? ($choice['label'] ?? '') : (is_object($choice) ? $choice->label : '');
                                $totalBet = is_array($choice) ? ($choice['total_bet'] ?? 0) : (is_object($choice) ? $choice->total_bet : 0);
                            @endphp
                            <div class="flex items-center gap-2 choice-item">
                                <input type="hidden" name="choices[{{ $index }}][id]" value="{{ $choiceId }}">
                                <input type="hidden" name="choices[{{ $index }}][total_bet]" value="{{ $totalBet }}">
                                <input type="text" name="choices[{{ $index }}][label]" value="{{ $choiceLabel }}"
                                    placeholder="Choice text" required
                                    class="flex-1 px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all"/>
                                <button type="button" onclick="removeChoice(this)"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 border border-slate-200 dark:border-white/[0.08] transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-slate-50 dark:bg-white/[0.02] border-t border-slate-100 dark:border-white/[0.05] flex items-center justify-end gap-3">
                    <a href="{{ route('wagers.show', $wager) }}"
                        class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-white/[0.04] hover:bg-slate-100 dark:hover:bg-white/[0.08] border border-slate-200 dark:border-white/[0.08] rounded-xl transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl transition-all duration-200 active:scale-95">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.fade-up { animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
</style>

<script>
function getChoiceCount() { return document.querySelectorAll('.choice-item').length; }

function addChoice() {
    const container = document.getElementById('choicesContainer');
    const count = getChoiceCount();
    if (count >= 10) { alert('Maximum 10 choices'); return; }
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2 choice-item';
    div.innerHTML = `
        <input type="hidden" name="choices[${count}][id]" value="">
        <input type="hidden" name="choices[${count}][total_bet]" value="0">
        <input type="text" name="choices[${count}][label]" placeholder="Choice text" required
            class="flex-1 px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 transition-all"/>
        <button type="button" onclick="removeChoice(this)"
            class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 border border-slate-200 dark:border-white/[0.08] transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    container.appendChild(div);
    div.querySelector('input[type="text"]').focus();
    updateButtons();
}

function removeChoice(btn) {
    if (getChoiceCount() <= 2) { alert('Minimum 2 choices'); return; }
    btn.closest('.choice-item').remove();
    reindexChoices();
    updateButtons();
}

function reindexChoices() {
    document.querySelectorAll('.choice-item').forEach((item, i) => {
        const inputs = item.querySelectorAll('input');
        inputs[0].name = `choices[${i}][id]`;
        inputs[1].name = `choices[${i}][total_bet]`;
        inputs[2].name = `choices[${i}][label]`;
    });
}

function updateButtons() {
    const count = getChoiceCount();
    const addBtn = document.getElementById('addChoiceBtn');
    addBtn.disabled = count >= 10;
    addBtn.style.opacity = count >= 10 ? '0.4' : '1';
    document.querySelectorAll('.choice-item button').forEach(btn => {
        btn.disabled = count <= 2;
        btn.style.opacity = count <= 2 ? '0.3' : '1';
    });
}

document.addEventListener('DOMContentLoaded', updateButtons);
</script>

@push('scripts')
@endpush
</x-app-layout>