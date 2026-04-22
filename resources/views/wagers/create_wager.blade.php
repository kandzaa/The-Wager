<div x-data="createWagerForm()">
    <div class="fixed inset-0 z-50 backdrop-blur-sm bg-black/60 dark:bg-black/80" @click="closeModal()"></div>

    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="relative w-full max-w-xl rounded-2xl bg-white dark:bg-[#0f1419] border border-slate-200 dark:border-white/[0.08] shadow-2xl max-h-[90vh] overflow-y-auto">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-white/[0.06]">
                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-xs uppercase tracking-[0.15em] text-emerald-600 dark:text-emerald-500 font-bold mb-0.5">New</p>
                        <h3 class="text-lg font-black tracking-tight text-slate-900 dark:text-white">Create Wager</h3>
                    </div>
                    <div x-show="isSubmitting" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        <span class="text-xs text-slate-500">Creating...</span>
                    </div>
                </div>
                <button @click="closeModal()" :disabled="isSubmitting"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/[0.06] transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Alerts --}}
            <div x-show="globalError" x-text="globalError" x-transition
                class="mx-6 mt-4 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-300 text-sm">
            </div>
            <div x-show="successMessage" x-text="successMessage" x-transition
                class="mx-6 mt-4 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-sm">
            </div>

            <form @submit.prevent="submitForm" class="px-6 py-5 space-y-4">

                {{-- Name --}}
                <div>
                    <input type="text" x-model="form.name" @input="validateField('name')" :class="getFieldClass('name')"
                        placeholder="Wager theme *" maxlength="255"/>
                    <div class="flex items-center justify-between mt-1">
                        <p x-show="errors.name" x-text="errors.name" class="text-xs text-red-500" x-transition></p>
                        <p class="text-xs text-slate-400 ml-auto"><span x-text="form.name.length"></span>/255</p>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <textarea x-model="form.description" @input="validateField('description')" :class="getFieldClass('description')"
                        placeholder="Description (optional)" rows="2" maxlength="1000"></textarea>
                    <div class="flex items-center justify-between mt-1">
                        <p x-show="errors.description" x-text="errors.description" class="text-xs text-red-500" x-transition></p>
                        <p class="text-xs text-slate-400 ml-auto"><span x-text="form.description.length"></span>/1000</p>
                    </div>
                </div>

                {{-- Max players + privacy --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs uppercase tracking-[0.12em] font-bold text-slate-500 dark:text-slate-400 mb-1.5">Max Players *</label>
                        <input type="number" x-model.number="form.max_players" @input="validateField('max_players')"
                            :class="getFieldClass('max_players')" placeholder="2–100" min="2" max="100"/>
                        <p x-show="errors.max_players" x-text="errors.max_players" class="text-xs text-red-500 mt-1" x-transition></p>
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-[0.12em] font-bold text-slate-500 dark:text-slate-400 mb-1.5">Visibility</label>
                        <div class="flex gap-2">
                            <button type="button" @click="form.status = 'public'"
                                :class="form.status === 'public' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 dark:bg-white/[0.04] text-slate-600 dark:text-slate-400 border-slate-200 dark:border-white/[0.08]'"
                                class="flex-1 py-2.5 text-xs font-bold rounded-xl border transition-all duration-200">
                                Public
                            </button>
                            <button type="button" @click="form.status = 'private'"
                                :class="form.status === 'private' ? 'bg-amber-500 text-white border-amber-500' : 'bg-slate-50 dark:bg-white/[0.04] text-slate-600 dark:text-slate-400 border-slate-200 dark:border-white/[0.08]'"
                                class="flex-1 py-2.5 text-xs font-bold rounded-xl border transition-all duration-200">
                                Private
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Buy-in --}}
                <div>
                    <label class="block text-xs uppercase tracking-[0.12em] font-bold text-slate-500 dark:text-slate-400 mb-1.5">Buy-in <span class="normal-case font-normal">(0 = free to join)</span></label>
                    <input type="number" x-model.number="form.buy_in" min="0" max="2147483647" placeholder="0"
                        :class="getFieldClass('buy_in')" />
                    <p class="text-xs text-slate-400 mt-1">Coins deducted from each player on join — goes straight to the pot.</p>
                </div>

                {{-- Ending time --}}
                <div>
                    <label class="block text-xs uppercase tracking-[0.12em] font-bold text-slate-500 dark:text-slate-400 mb-1.5">End Time *</label>
                    <input type="datetime-local" x-model="form.ending_time" @input="validateField('ending_time')"
                        :class="getFieldClass('ending_time')" :min="getMinDateTime()"/>
                    <p x-show="errors.ending_time" x-text="errors.ending_time" class="text-xs text-red-500 mt-1" x-transition></p>
                </div>

                {{-- Choices --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs uppercase tracking-[0.12em] font-bold text-slate-500 dark:text-slate-400">
                            Choices * <span class="normal-case font-normal">(<span x-text="getValidChoices().length"></span> valid)</span>
                        </label>
                        <button type="button" @click="addChoice()" :disabled="form.choices.length >= 10"
                            class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                            + Add (<span x-text="form.choices.length"></span>/10)
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <template x-for="(choice, index) in form.choices" :key="index">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="choice.label" @input="validateField('choices')"
                                    :class="getChoiceFieldClass(index)"
                                    :placeholder="'Choice ' + (index + 1)" maxlength="255"/>
                                <button type="button" @click="removeChoice(index)" x-show="form.choices.length > 2"
                                    class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 border border-slate-200 dark:border-white/[0.08] transition-all duration-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <p x-show="errors.choices" x-text="errors.choices" class="text-xs text-red-500 mt-1" x-transition></p>
                </div>

                <input type="hidden" x-model="form.starting_time">

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100 dark:border-white/[0.05]">
                    <button type="button" @click="closeModal()" :disabled="isSubmitting"
                        class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/[0.05] hover:bg-slate-200 dark:hover:bg-white/[0.08] border border-slate-200 dark:border-white/[0.08] rounded-xl transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" :disabled="isSubmitting || !isFormValid()"
                        class="flex-1 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="!isSubmitting"><span>Create Wager</span></template>
                        <template x-if="isSubmitting">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                Creating...
                            </span>
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function createWagerForm() {
    return {
        isSubmitting: false,
        globalError: '',
        successMessage: '',
        form: { name:'', description:'', max_players:2, status:'public', buy_in:0, starting_time:'', ending_time:'', choices:[{label:''},{label:''}] },
        errors: { name:'', description:'', max_players:'', buy_in:'', ending_time:'', choices:'' },
        init() {
            this.resetFormTimes();
            window.addEventListener('close-create-wager-modal', () => this.showModal = false);
        },
        resetFormTimes() {
            const now = new Date();
            this.form.starting_time = now.toISOString().slice(0,16);
            now.setHours(now.getHours()+1);
            this.form.ending_time = now.toISOString().slice(0,16);
        },
        validateField(field) {
            this.errors[field] = '';
            if (field==='name' && !this.form.name.trim()) this.errors.name = 'Theme is required';
            if (field==='max_players' && (this.form.max_players < 2 || this.form.max_players > 100)) this.errors.max_players = 'Must be 2–100';
            if (field==='buy_in' && (this.form.buy_in < 0 || this.form.buy_in > 2147483647)) this.errors.buy_in = 'Must be 0 or more';
            if (field==='ending_time' && (!this.form.ending_time || new Date(this.form.ending_time) <= new Date())) this.errors.ending_time = 'Must be at least 1 hour from now';
            if (field==='choices') {
                const v = this.getValidChoices();
                if (v.length < 2) this.errors.choices = 'At least 2 choices required';
                else if (this.hasDuplicateChoices()) this.errors.choices = 'Duplicate choices not allowed';
            }
        },
        getValidChoices() { return this.form.choices.map(c=>c.label.trim()).filter(l=>l!==''); },
        hasDuplicateChoices() { const v=this.getValidChoices(); return v.length !== new Set(v.map(c=>c.toLowerCase())).size; },
        getFieldClass(field) {
            const base = 'w-full px-4 py-2.5 rounded-xl text-sm border transition-all duration-200';
            const err = 'bg-red-50 dark:bg-red-950/20 border-red-300 dark:border-red-500/30 text-slate-900 dark:text-white focus:outline-none';
            const ok = 'bg-slate-50 dark:bg-black/30 border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30';
            return `${base} ${this.errors[field] ? err : ok}`;
        },
        getChoiceFieldClass(index) {
            const base = 'flex-1 px-4 py-2.5 rounded-xl text-sm border transition-all duration-200';
            const err = 'bg-red-50 dark:bg-red-950/20 border-red-300 dark:border-red-500/30 text-slate-900 dark:text-white focus:outline-none';
            const ok = 'bg-slate-50 dark:bg-black/30 border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30';
            return `${base} ${this.errors.choices && !this.form.choices[index].label.trim() ? err : ok}`;
        },
        addChoice() { if (this.form.choices.length < 10) this.form.choices.push({label:''}); },
        removeChoice(i) { if (this.form.choices.length > 2) { this.form.choices.splice(i,1); this.validateField('choices'); } },
        getMinDateTime() { return new Date().toISOString().slice(0,16); },
        isFormValid() {
            return !!this.form.name.trim() && !!this.form.ending_time && this.form.max_players>=2 && this.form.max_players<=100
                && this.getValidChoices().length>=2 && !this.hasDuplicateChoices()
                && !Object.values(this.errors).some(e=>e!=='');
        },
        async submitForm() {
            this.globalError = ''; this.successMessage = '';
            ['name','description','max_players','buy_in','ending_time','choices'].forEach(f => this.validateField(f));
            if (!this.isFormValid()) { this.globalError = 'Please fix validation errors.'; return; }
            this.isSubmitting = true;
            try {
                const fd = new FormData();
                const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
                fd.append('_token', token);
                fd.append('name', this.form.name.trim());
                fd.append('description', this.form.description.trim());
                fd.append('max_players', this.form.max_players);
                fd.append('privacy', this.form.status);
                fd.append('buy_in', this.form.buy_in ?? 0);
                fd.append('starting_time', new Date().toISOString());
                fd.append('ending_time', new Date(this.form.ending_time).toISOString());
                this.form.choices.filter(c=>c.label.trim()).forEach((c,i) => fd.append(`choices[${i}][label]`, c.label.trim()));
                const res = await fetch('/wagers', { method:'POST', body:fd, headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} });
                const result = await res.json().catch(()=>null);
                if (res.ok) {
                    this.successMessage = 'Created! Redirecting...';
                    setTimeout(() => window.location.href = '/wagers', 800);
                } else {
                    if (result?.errors) {
                        Object.keys(result.errors).forEach(k => { const key = k.includes('.')?k.split('.')[0]:k; if (this.errors.hasOwnProperty(key)) this.errors[key] = result.errors[k][0]; });
                        this.globalError = 'Please fix the validation errors.';
                    } else this.globalError = result?.message || 'Failed to create wager.';
                }
            } catch { this.globalError = 'An error occurred.'; }
            finally { this.isSubmitting = false; }
        },
        closeModal() {
            if (this.isSubmitting) return;
            this.globalError = ''; this.successMessage = '';
            window.dispatchEvent(new CustomEvent('close-create-wager-modal'));
        }
    };
}
</script>