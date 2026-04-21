<x-app-layout>
@php
$rarityMeta = [
    'common'    => ['label' => 'Common',    'text' => 'text-slate-500 dark:text-slate-400',    'bg' => 'bg-slate-100 dark:bg-slate-800/60',    'dot' => 'bg-slate-400'],
    'uncommon'  => ['label' => 'Uncommon',  'text' => 'text-emerald-600 dark:text-emerald-400', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/30', 'dot' => 'bg-emerald-500'],
    'rare'      => ['label' => 'Rare',      'text' => 'text-blue-600 dark:text-blue-400',       'bg' => 'bg-blue-50 dark:bg-blue-900/30',       'dot' => 'bg-blue-500'],
    'epic'      => ['label' => 'Epic',      'text' => 'text-purple-600 dark:text-purple-400',   'bg' => 'bg-purple-50 dark:bg-purple-900/30',   'dot' => 'bg-purple-500'],
    'legendary' => ['label' => 'Legendary', 'text' => 'text-amber-600 dark:text-amber-400',     'bg' => 'bg-amber-50 dark:bg-amber-900/30',     'dot' => 'bg-amber-500'],
];
$typeMeta = [
    'frame' => ['label' => 'Frame', 'text' => 'text-indigo-600 dark:text-indigo-400', 'bg' => 'bg-indigo-50 dark:bg-indigo-900/30'],
    'title' => ['label' => 'Title', 'text' => 'text-sky-600 dark:text-sky-400',       'bg' => 'bg-sky-50 dark:bg-sky-900/30'],
    'theme' => ['label' => 'Theme', 'text' => 'text-violet-600 dark:text-violet-400', 'bg' => 'bg-violet-50 dark:bg-violet-900/30'],
    'charm' => ['label' => 'Charm', 'text' => 'text-rose-600 dark:text-rose-400',     'bg' => 'bg-rose-50 dark:bg-rose-900/30'],
];
$titlePresets = [
    ['label' => 'Emerald', 'color' => 'text-emerald-400', 'bg' => 'bg-emerald-500/10 border-emerald-500/30', 'dot' => '#34d399'],
    ['label' => 'Amber',   'color' => 'text-amber-400',   'bg' => 'bg-amber-500/10 border-amber-500/30',     'dot' => '#fbbf24'],
    ['label' => 'Blue',    'color' => 'text-blue-400',    'bg' => 'bg-blue-500/10 border-blue-500/30',       'dot' => '#60a5fa'],
    ['label' => 'Red',     'color' => 'text-red-400',     'bg' => 'bg-red-500/10 border-red-500/30',         'dot' => '#f87171'],
    ['label' => 'Purple',  'color' => 'text-purple-400',  'bg' => 'bg-purple-500/10 border-purple-500/30',   'dot' => '#c084fc'],
    ['label' => 'Pink',    'color' => 'text-pink-400',    'bg' => 'bg-pink-500/10 border-pink-500/30',       'dot' => '#f472b6'],
    ['label' => 'Cyan',    'color' => 'text-cyan-400',    'bg' => 'bg-cyan-500/10 border-cyan-500/30',       'dot' => '#22d3ee'],
    ['label' => 'Yellow',  'color' => 'text-yellow-400',  'bg' => 'bg-yellow-500/10 border-yellow-500/30',   'dot' => '#facc15'],
    ['label' => 'Slate',   'color' => 'text-slate-400',   'bg' => 'bg-slate-500/10 border-slate-500/30',     'dot' => '#94a3b8'],
];
@endphp

<div
    class="min-h-screen bg-slate-50 dark:bg-[#080b0f] py-10"
    x-data="{
        filter: 'all',
        open: false,
        isEdit: false,
        form: {
            id: null, name: '', type: 'frame', rarity: 'common', price: 100,
            meta_gradient: 'linear-gradient(135deg,#10b981,#059669)',
            meta_color: 'text-emerald-400', meta_bg: 'bg-emerald-500/10 border-emerald-500/30',
            meta_bg_class: '', meta_emoji: '⭐',
            meta_hex_color: '#34d399', meta_hex_bg: ''
        },
        gradAngle: 135,
        gradColor1: '#10b981',
        gradColor2: '#059669',
        gradTarget: 1,
        swatches: ['#ef4444','#f97316','#f59e0b','#eab308','#84cc16','#22c55e','#10b981','#14b8a6','#06b6d4','#0ea5e9','#3b82f6','#6366f1','#8b5cf6','#a855f7','#d946ef','#ec4899','#f43f5e','#94a3b8','#475569','#ffffff'],
        blank() {
            return {
                id: null, name: '', type: 'frame', rarity: 'common', price: 100,
                meta_gradient: 'linear-gradient(135deg,#10b981,#059669)',
                meta_color: 'text-emerald-400', meta_bg: 'bg-emerald-500/10 border-emerald-500/30',
                meta_bg_class: '', meta_emoji: '⭐',
                meta_hex_color: '#34d399', meta_hex_bg: ''
            };
        },
        buildGrad() {
            this.form.meta_gradient = `linear-gradient(${this.gradAngle}deg, ${this.gradColor1}, ${this.gradColor2})`;
        },
        syncGradFromText() {
            const m = this.form.meta_gradient.match(/linear-gradient\(\s*(\d+)deg\s*,\s*(#[\da-fA-F]{3,8})\s*,\s*(#[\da-fA-F]{3,8})\s*\)/);
            if (m) { this.gradAngle = parseInt(m[1]); this.gradColor1 = m[2]; this.gradColor2 = m[3]; }
        },
        swatchClick(color) {
            if (this.gradTarget === 1) this.gradColor1 = color; else this.gradColor2 = color;
            this.buildGrad();
        },
        openCreate() {
            this.isEdit = false; this.form = this.blank();
            this.gradAngle = 135; this.gradColor1 = '#10b981'; this.gradColor2 = '#059669';
            this.open = true;
        },
        openEdit(c) {
            this.isEdit = true;
            this.form = {
                id: c.id, name: c.name, type: c.type,
                rarity: c.rarity, price: c.price,
                meta_gradient: c.gradient || 'linear-gradient(135deg,#10b981,#059669)',
                meta_color: c.color || 'text-emerald-400',
                meta_bg: c.bg || 'bg-emerald-500/10 border-emerald-500/30',
                meta_bg_class: c.bg_class || '',
                meta_emoji: c.emoji || '⭐',
                meta_hex_color: c.hex_color || '#34d399',
                meta_hex_bg: c.hex_bg || '',
            };
            const grad = c.gradient || '';
            const m = grad.match(/linear-gradient\(\s*(\d+)deg\s*,\s*(#[\da-fA-F]{3,8})\s*,\s*(#[\da-fA-F]{3,8})\s*\)/);
            if (m) { this.gradAngle = parseInt(m[1]); this.gradColor1 = m[2]; this.gradColor2 = m[3]; }
            else { this.gradAngle = 135; this.gradColor1 = '#10b981'; this.gradColor2 = '#059669'; }
            this.open = true;
        },
        applyPreset(color, bg) { this.form.meta_color = color; this.form.meta_bg = bg; },
        get formAction() {
            return this.isEdit
                ? '{{ url('admin/Manage/customizations') }}/' + this.form.id
                : '{{ route('admin.Manage.customizations.store') }}';
        }
    }"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-[0.6rem] font-semibold tracking-[0.2em] uppercase text-emerald-500 mb-1">Admin</p>
                <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Customizations</h1>
                <p class="text-sm text-slate-500 mt-1">{{ $cosmetics->count() }} items across {{ $cosmetics->pluck('type')->unique()->count() }} types</p>
            </div>
            <button @click="openCreate()"
                class="flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all duration-200 active:scale-95 shadow-lg shadow-emerald-900/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New Item
            </button>
        </div>

        {{-- Filter tabs --}}
        <div class="flex items-center gap-1 p-1 bg-slate-100 dark:bg-white/[0.03] border border-slate-200 dark:border-white/[0.06] rounded-xl w-fit">
            @foreach([['all','All', $cosmetics->count()], ['frame','Frames',$cosmetics->where('type','frame')->count()], ['title','Titles',$cosmetics->where('type','title')->count()], ['theme','Themes',$cosmetics->where('type','theme')->count()], ['charm','Charms',$cosmetics->where('type','charm')->count()]] as [$val,$lbl,$cnt])
            <button @click="filter = '{{ $val }}'"
                :class="filter === '{{ $val }}' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 border-transparent'"
                class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-all duration-150 flex items-center gap-1.5">
                {{ $lbl }}
                <span class="tabular-nums opacity-60">{{ $cnt }}</span>
            </button>
            @endforeach
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/[0.06] rounded-2xl overflow-hidden shadow-sm dark:shadow-none">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-white/[0.06] text-[0.65rem] uppercase tracking-[0.15em] text-slate-500">
                        <th class="px-5 py-3 text-left font-semibold">Preview</th>
                        <th class="px-5 py-3 text-left font-semibold">Name</th>
                        <th class="px-4 py-3 text-left font-semibold hidden sm:table-cell">Type</th>
                        <th class="px-4 py-3 text-left font-semibold hidden md:table-cell">Rarity</th>
                        <th class="px-4 py-3 text-left font-semibold">Price</th>
                        <th class="px-5 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/[0.04]">
                    @forelse($cosmetics as $c)
                    @php $meta = is_array($c->meta) ? $c->meta : json_decode($c->meta, true); @endphp
                    <tr
                        x-show="filter === 'all' || filter === '{{ $c->type }}'"
                        class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors"
                    >
                        {{-- Preview --}}
                        <td class="px-5 py-3.5">
                            @if($c->type === 'frame')
                                <div class="w-8 h-8 rounded-full" style="background: {{ $meta['gradient'] ?? '#888' }}; padding: 2px">
                                    <div class="w-full h-full rounded-full bg-slate-100 dark:bg-[#080b0f]"></div>
                                </div>
                            @elseif($c->type === 'title')
                                @if(!empty($meta['hex_color']))
                                    <span class="px-2 py-0.5 rounded-md text-xs font-bold border"
                                        style="color: {{ $meta['hex_color'] }}; background: {{ $meta['hex_bg'] ?? 'transparent' }}; border-color: {{ $meta['hex_color'] }}40">
                                        {{ mb_strimwidth($c->name, 0, 10, '…') }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md text-xs font-bold border {{ $meta['bg'] ?? '' }} {{ $meta['color'] ?? 'text-slate-400' }}">
                                        {{ mb_strimwidth($c->name, 0, 10, '…') }}
                                    </span>
                                @endif
                            @elseif($c->type === 'theme')
                                <div class="w-8 h-8 rounded-lg" style="background: {{ $meta['gradient'] ?? '#333' }}"></div>
                            @elseif($c->type === 'charm')
                                <span class="text-xl">{{ $meta['emoji'] ?? '⭐' }}</span>
                            @endif
                        </td>
                        {{-- Name --}}
                        <td class="px-5 py-3.5">
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $c->name }}</span>
                        </td>
                        {{-- Type --}}
                        <td class="px-4 py-3.5 hidden sm:table-cell">
                            @php $t = $typeMeta[$c->type] ?? ['label'=>$c->type,'text'=>'text-slate-500','bg'=>'bg-slate-100 dark:bg-slate-800']; @endphp
                            <span class="px-2 py-0.5 rounded-md text-xs font-bold {{ $t['bg'] }} {{ $t['text'] }}">{{ $t['label'] }}</span>
                        </td>
                        {{-- Rarity --}}
                        <td class="px-4 py-3.5 hidden md:table-cell">
                            @php $r = $rarityMeta[$c->rarity] ?? ['label'=>$c->rarity,'text'=>'text-slate-500','bg'=>'bg-slate-100 dark:bg-slate-800','dot'=>'bg-slate-400']; @endphp
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-xs font-bold {{ $r['bg'] }} {{ $r['text'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $r['dot'] }}"></span>
                                {{ $r['label'] }}
                            </span>
                        </td>
                        {{-- Price --}}
                        <td class="px-4 py-3.5">
                            <span class="font-bold text-amber-600 dark:text-amber-400">{{ number_format($c->price) }}</span>
                            <span class="text-slate-400 text-xs ml-0.5">c</span>
                        </td>
                        {{-- Actions --}}
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button
                                    @click="openEdit({{ json_encode(['id'=>$c->id,'name'=>$c->name,'type'=>$c->type,'rarity'=>$c->rarity,'price'=>$c->price,'gradient'=>$meta['gradient']??'','color'=>$meta['color']??'','bg'=>$meta['bg']??'','bg_class'=>$meta['bg_class']??'','emoji'=>$meta['emoji']??'','hex_color'=>$meta['hex_color']??'','hex_bg'=>$meta['hex_bg']??'']) }})"
                                    class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/[0.07] transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('admin.Manage.customizations.destroy', $c->id) }}" method="POST"
                                    onsubmit="return confirm('Delete {{ addslashes($c->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center text-slate-400">
                            No cosmetics yet. Create your first one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══ CREATE / EDIT MODAL ═══ --}}
    <div
        x-show="open"
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        style="display:none"
    >
        <div
            x-show="open"
            x-transition:enter="transition duration-200 ease-out"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
            class="w-full max-w-lg bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-white/[0.08] rounded-2xl shadow-2xl overflow-hidden"
        >
            {{-- Modal header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-white/[0.06]">
                <div>
                    <h2 class="text-base font-black text-slate-900 dark:text-white" x-text="isEdit ? 'Edit Item' : 'New Item'"></h2>
                    <p class="text-xs text-slate-500 mt-0.5" x-text="isEdit ? 'Update cosmetic details' : 'Add a new cosmetic to the shop'"></p>
                </div>
                <button @click="open = false" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/[0.06] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Form --}}
            <form method="POST" :action="formAction" class="px-6 py-5 space-y-4 max-h-[75vh] overflow-y-auto">
                @csrf
                <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>

                {{-- Row: Type + Rarity --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500 mb-1.5">Type</label>
                        <select name="type" x-model="form.type"
                            class="w-full px-3 py-2 rounded-xl text-sm bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500/50 transition-all">
                            <option value="frame">Frame</option>
                            <option value="title">Title</option>
                            <option value="theme">Theme</option>
                            <option value="charm">Charm</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500 mb-1.5">Rarity</label>
                        <select name="rarity" x-model="form.rarity"
                            class="w-full px-3 py-2 rounded-xl text-sm bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500/50 transition-all">
                            <option value="common">Common</option>
                            <option value="uncommon">Uncommon</option>
                            <option value="rare">Rare</option>
                            <option value="epic">Epic</option>
                            <option value="legendary">Legendary</option>
                        </select>
                    </div>
                </div>

                {{-- Row: Name + Price --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500 mb-1.5">Name</label>
                        <input type="text" name="name" x-model="form.name" placeholder="Gold Frame" required
                            class="w-full px-3 py-2 rounded-xl text-sm bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500/50 transition-all"/>
                    </div>
                    <div>
                        <label class="block text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500 mb-1.5">Price (coins)</label>
                        <input type="number" name="price" x-model="form.price" min="0" required
                            class="w-full px-3 py-2 rounded-xl text-sm bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500/50 transition-all"/>
                    </div>
                </div>


                {{-- ── Meta: Frame ── --}}
                <div x-show="form.type === 'frame'" class="space-y-3">
                    {{-- Color swatches --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500">Color Swatches</label>
                            <div class="flex items-center gap-1 text-[0.6rem] font-semibold">
                                <button type="button" @click="gradTarget = 1"
                                    :class="gradTarget === 1 ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'"
                                    class="px-2 py-0.5 rounded-md uppercase tracking-wide transition-all">Start</button>
                                <button type="button" @click="gradTarget = 2"
                                    :class="gradTarget === 2 ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'"
                                    class="px-2 py-0.5 rounded-md uppercase tracking-wide transition-all">End</button>
                            </div>
                        </div>
                        <div class="grid grid-cols-10 gap-1.5 p-3 bg-slate-50 dark:bg-black/30 rounded-xl border border-slate-200 dark:border-white/[0.06]">
                            <template x-for="sw in swatches" :key="sw">
                                <button type="button" @click="swatchClick(sw)"
                                    :style="`background:${sw}`"
                                    :class="(gradTarget===1 && gradColor1===sw) || (gradTarget===2 && gradColor2===sw) ? 'ring-2 ring-offset-1 ring-slate-600 dark:ring-white scale-110' : 'ring-1 ring-black/10'"
                                    class="w-full aspect-square rounded-md transition-all duration-100 hover:scale-110">
                                </button>
                            </template>
                        </div>
                    </div>
                    {{-- Color pickers + angle --}}
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[0.6rem] uppercase tracking-widest text-slate-400 mb-1.5">Start Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="gradColor1" @input="buildGrad()"
                                    class="w-9 h-9 rounded-lg cursor-pointer border-0 bg-transparent p-0.5"/>
                                <span class="text-[0.6rem] font-mono text-slate-400 tabular-nums" x-text="gradColor1"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[0.6rem] uppercase tracking-widest text-slate-400 mb-1.5">End Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="gradColor2" @input="buildGrad()"
                                    class="w-9 h-9 rounded-lg cursor-pointer border-0 bg-transparent p-0.5"/>
                                <span class="text-[0.6rem] font-mono text-slate-400 tabular-nums" x-text="gradColor2"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[0.6rem] uppercase tracking-widest text-slate-400 mb-1.5">Angle <span x-text="gradAngle + '°'"></span></label>
                            <input type="range" x-model="gradAngle" @input="buildGrad()" min="0" max="360"
                                class="w-full accent-emerald-500 mt-2"/>
                        </div>
                    </div>
                    {{-- Raw CSS (advanced) --}}
                    <div>
                        <label class="block text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500 mb-1.5">Gradient CSS <span class="normal-case font-normal text-slate-400">(auto-updated)</span></label>
                        <input type="text" name="meta_gradient" x-model="form.meta_gradient" @change="syncGradFromText()"
                            class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white font-mono placeholder-slate-400 focus:outline-none focus:border-emerald-500/50 transition-all"/>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex-shrink-0 ring-2 ring-slate-200 dark:ring-white/10" :style="`background: ${form.meta_gradient}; padding: 3px`">
                            <div class="w-full h-full rounded-full bg-slate-100 dark:bg-[#0d1117]"></div>
                        </div>
                        <div class="h-5 flex-1 rounded-full" :style="`background: ${form.meta_gradient}`"></div>
                    </div>
                </div>

                {{-- ── Meta: Title ── --}}
                <div x-show="form.type === 'title'" class="space-y-3">
                    <input type="hidden" name="meta_hex_color" x-model="form.meta_hex_color">
                    <input type="hidden" name="meta_hex_bg" x-model="form.meta_hex_bg">
                    <div>
                        <label class="block text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500 mb-2">Quick Presets</label>
                        <div class="flex flex-wrap gap-2 p-3 bg-slate-50 dark:bg-black/30 rounded-xl border border-slate-200 dark:border-white/[0.06]">
                            @foreach($titlePresets as $p)
                            <button type="button"
                                @click="applyPreset('{{ $p['color'] }}', '{{ $p['bg'] }}'); form.meta_hex_color = '{{ $p['dot'] }}'; form.meta_hex_bg = '';"
                                :class="form.meta_color === '{{ $p['color'] }}' ? 'ring-2 ring-offset-1 ring-slate-600 dark:ring-white/60 scale-110' : 'ring-1 ring-black/10 hover:scale-105'"
                                class="w-7 h-7 rounded-full transition-all duration-150"
                                style="background: {{ $p['dot'] }}"
                                title="{{ $p['label'] }}">
                            </button>
                            @endforeach
                        </div>
                    </div>
                    {{-- Custom color pickers --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[0.6rem] uppercase tracking-widest text-slate-400 mb-1.5">Custom Text Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="form.meta_hex_color"
                                    @input="form.meta_color = 'custom'; form.meta_bg = form.meta_bg || '';"
                                    class="w-9 h-9 rounded-lg cursor-pointer border-0 bg-transparent p-0.5"/>
                                <span class="text-[0.6rem] font-mono text-slate-400 tabular-nums" x-text="form.meta_hex_color"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[0.6rem] uppercase tracking-widest text-slate-400 mb-1.5">Custom Badge BG</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="form.meta_hex_bg"
                                    @input="form.meta_color = 'custom';"
                                    class="w-9 h-9 rounded-lg cursor-pointer border-0 bg-transparent p-0.5"/>
                                <span class="text-[0.6rem] font-mono text-slate-400 tabular-nums" x-text="form.meta_hex_bg || '#—'"></span>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500 mb-1.5">Text class</label>
                            <input type="text" name="meta_color" x-model="form.meta_color" placeholder="text-emerald-400"
                                class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white font-mono placeholder-slate-400 focus:outline-none focus:border-emerald-500/50 transition-all"/>
                        </div>
                        <div>
                            <label class="block text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500 mb-1.5">Badge class</label>
                            <input type="text" name="meta_bg" x-model="form.meta_bg" placeholder="bg-emerald-500/10 border-..."
                                class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white font-mono placeholder-slate-400 focus:outline-none focus:border-emerald-500/50 transition-all"/>
                        </div>
                    </div>
                    {{-- Live preview: class-based vs hex --}}
                    <div class="flex items-center gap-3">
                        <template x-if="form.meta_color === 'custom' && form.meta_hex_color">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold border"
                                :style="`color: ${form.meta_hex_color}; background: ${form.meta_hex_bg || 'transparent'}; border-color: ${form.meta_hex_color}40`"
                                x-text="form.name || 'Preview'"></span>
                        </template>
                        <template x-if="form.meta_color !== 'custom'">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold border" :class="[form.meta_bg, form.meta_color]" x-text="form.name || 'Preview'"></span>
                        </template>
                        <p class="text-xs text-slate-400">Live title preview</p>
                    </div>
                </div>

                {{-- ── Meta: Theme ── --}}
                <div x-show="form.type === 'theme'" class="space-y-3">
                    {{-- Color swatches --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500">Color Swatches</label>
                            <div class="flex items-center gap-1 text-[0.6rem] font-semibold">
                                <button type="button" @click="gradTarget = 1"
                                    :class="gradTarget === 1 ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'"
                                    class="px-2 py-0.5 rounded-md uppercase tracking-wide transition-all">Start</button>
                                <button type="button" @click="gradTarget = 2"
                                    :class="gradTarget === 2 ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-500/30' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'"
                                    class="px-2 py-0.5 rounded-md uppercase tracking-wide transition-all">End</button>
                            </div>
                        </div>
                        <div class="grid grid-cols-10 gap-1.5 p-3 bg-slate-50 dark:bg-black/30 rounded-xl border border-slate-200 dark:border-white/[0.06]">
                            <template x-for="sw in swatches" :key="sw">
                                <button type="button" @click="swatchClick(sw)"
                                    :style="`background:${sw}`"
                                    :class="(gradTarget===1 && gradColor1===sw) || (gradTarget===2 && gradColor2===sw) ? 'ring-2 ring-offset-1 ring-slate-600 dark:ring-white scale-110' : 'ring-1 ring-black/10'"
                                    class="w-full aspect-square rounded-md transition-all duration-100 hover:scale-110">
                                </button>
                            </template>
                        </div>
                    </div>
                    {{-- Color pickers + angle --}}
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[0.6rem] uppercase tracking-widest text-slate-400 mb-1.5">Start Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="gradColor1" @input="buildGrad()"
                                    class="w-9 h-9 rounded-lg cursor-pointer border-0 bg-transparent p-0.5"/>
                                <span class="text-[0.6rem] font-mono text-slate-400 tabular-nums" x-text="gradColor1"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[0.6rem] uppercase tracking-widest text-slate-400 mb-1.5">End Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="gradColor2" @input="buildGrad()"
                                    class="w-9 h-9 rounded-lg cursor-pointer border-0 bg-transparent p-0.5"/>
                                <span class="text-[0.6rem] font-mono text-slate-400 tabular-nums" x-text="gradColor2"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[0.6rem] uppercase tracking-widest text-slate-400 mb-1.5">Angle <span x-text="gradAngle + '°'"></span></label>
                            <input type="range" x-model="gradAngle" @input="buildGrad()" min="0" max="360"
                                class="w-full accent-emerald-500 mt-2"/>
                        </div>
                    </div>
                    {{-- Raw CSS --}}
                    <div>
                        <label class="block text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500 mb-1.5">Gradient CSS <span class="normal-case font-normal text-slate-400">(auto-updated)</span></label>
                        <input type="text" name="meta_gradient" x-model="form.meta_gradient" @change="syncGradFromText()"
                            class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white font-mono placeholder-slate-400 focus:outline-none focus:border-emerald-500/50 transition-all"/>
                    </div>
                    <div>
                        <label class="block text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500 mb-1.5">BG Class <span class="normal-case font-normal text-slate-400">(CSS class for profile bg)</span></label>
                        <input type="text" name="meta_bg_class" x-model="form.meta_bg_class" placeholder="bg-midnight"
                            class="w-full px-3 py-2 rounded-xl text-sm bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white font-mono placeholder-slate-400 focus:outline-none focus:border-emerald-500/50 transition-all"/>
                    </div>
                    <div class="h-12 rounded-xl ring-1 ring-slate-200 dark:ring-white/10" :style="`background: ${form.meta_gradient}`"></div>
                </div>

                {{-- ── Meta: Charm ── --}}
                <div x-show="form.type === 'charm'" class="space-y-3">
                    <div>
                        <label class="block text-[0.65rem] uppercase tracking-[0.15em] font-bold text-slate-500 mb-1.5">Emoji</label>
                        <div class="flex items-center gap-3">
                            <input type="text" name="meta_emoji" x-model="form.meta_emoji" maxlength="4" placeholder="⭐"
                                class="w-24 px-3 py-2 rounded-xl text-xl bg-slate-50 dark:bg-black/40 border border-slate-200 dark:border-white/[0.08] text-slate-900 dark:text-white text-center focus:outline-none focus:border-emerald-500/50 transition-all"/>
                            <span class="text-4xl" x-text="form.meta_emoji"></span>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-white/[0.06]">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-500 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/[0.06] transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-all duration-200 active:scale-95">
                        <span x-text="isEdit ? 'Save Changes' : 'Create Item'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.fade-up { animation: fadeUp 0.5s cubic-bezier(0.16,1,0.3,1) both; }
@keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
</style>
</x-app-layout>
