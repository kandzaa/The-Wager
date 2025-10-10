<div>
    @foreach ($wagers as $w)
        <div class="space-y-4" x-data="{ selectedChoiceId: null, confirming: false }">
            <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ $w->name }}</h2>

            <form method="POST" action="{{ route('wagers.end', $w) }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="winning_choice_id" :value="selectedChoiceId">

                <template x-if="!confirming">
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-2">Select the winning choice:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($w->choices as $choice)
                                <button type="button"
                                    @click="selectedChoiceId = {{ $choice->id }}; confirming = true;"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700/80 shadow-sm transition">
                                    <span class="font-medium">{{ $choice->label }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </template>

                <template x-if="confirming">
                    <div class="space-y-3">
                        <p class="text-sm text-slate-700 dark:text-slate-200">Confirm this as the winning choice?</p>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-medium shadow-sm transition">
                                Confirm
                            </button>
                            <button type="button" @click="confirming = false; selectedChoiceId = null;"
                                class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 font-medium shadow-sm transition">
                                Cancel
                            </button>
                        </div>
                    </div>
                </template>
            </form>
        </div>
    @endforeach
</div>
