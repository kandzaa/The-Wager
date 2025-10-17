<div x-show="showModal" class="fixed inset-0 z-50">
    <div x-data="createWagerForm()">
        <div class="fixed inset-0 backdrop-blur-sm bg-slate-950/50" @click="closeModal()"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900 text-left shadow-2xl transition-all sm:w-full sm:max-w-2xl max-h-[90vh] overflow-y-auto ring-1 ring-slate-300 dark:ring-slate-700">
                    <!-- Rest of the form content remains unchanged -->
                    <div class="bg-slate-50 dark:bg-slate-900 px-6 pt-6 pb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Create New Wager
                                </h3>
                                <div x-show="isSubmitting" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-emerald-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Creating...</span>
                                </div>
                            </div>
                            <button @click="closeModal()"
                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300"
                                :disabled="isSubmitting">
                                <ion-icon name="close" class="w-6 h-6"></ion-icon>
                            </button>
                        </div>

                        <div x-show="globalError" x-text="globalError"
                            class="mx-6 bg-rose-100 dark:bg-rose-900/30 border border-rose-300 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-lg relative mb-4"
                            role="alert" x-transition>
                        </div>

                        <div x-show="successMessage" x-text="successMessage"
                            class="mx-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-lg relative mb-4"
                            role="alert" x-transition>
                        </div>

                        <form @submit.prevent="submitForm" class="px-6 pb-4">
                            <div class="space-y-6">

                                <div>
                                    <input type="text" x-model="form.name" @input="validateField('name')"
                                        :class="getFieldClass('name')" placeholder="Enter wager theme"
                                        class="p-3 block w-full rounded-lg border transition-all duration-200 text-sm"
                                        maxlength="255">
                                    <div x-show="errors.name" x-text="errors.name"
                                        class="text-rose-600 dark:text-rose-400 text-xs mt-1" x-transition>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        <span x-text="form.name.length"></span>/255 characters
                                    </div>
                                </div>

                                <div>
                                    <textarea x-model="form.description" @input="validateField('description')" :class="getFieldClass('description')"
                                        placeholder="Optional description" rows="3" maxlength="1000"
                                        class="p-3 block w-full rounded-lg border transition-all duration-200 text-sm resize-none">
                            </textarea>
                                    <div x-show="errors.description" x-text="errors.description"
                                        class="text-rose-600 dark:text-rose-400 text-xs mt-1" x-transition>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        <span x-text="form.description.length"></span>/1000 characters
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            Max Players *
                                        </label>
                                        <input type="number" x-model.number="form.max_players"
                                            @input="validateField('max_players')" :class="getFieldClass('max_players')"
                                            placeholder="Min: 2, Max: 100" min="2" max="100"
                                            class="p-3 block w-full rounded-lg border transition-all duration-200 text-sm">
                                        <div x-show="errors.max_players" x-text="errors.max_players"
                                            class="text-rose-600 dark:text-rose-400 text-xs mt-1" x-transition>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center justify-between p-4 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="text-sm font-medium text-slate-700 dark:text-slate-300">Visibility:</span>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="form.status = 'public'"
                                                :class="form.status === 'public' ? 'h-8 bg-emerald-600 text-white mb-2' :
                                                    'h-8 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300  mb-2'"
                                                class="px-2 py-1 rounded-md text-xs font-medium transition-colors">
                                                <ion-icon name="lock-open"
                                                    class="inline w-3 h-3 mr-1 align-middle"></ion-icon>
                                                Public
                                            </button>
                                            <button type="button" @click="form.status = 'private'"
                                                :class="form.status === 'private' ? 'h-8 bg-rose-600 text-white  mb-2' :
                                                    'h-8 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300  mb-2'"
                                                class="px-2 py-1 rounded-md text-xs font-medium transition-colors">
                                                <ion-icon name="lock-closed"
                                                    class="inline w-3 h-3 mr-1 align-middle"></ion-icon>
                                                Private
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-slate-500">
                                            <span x-text="getValidChoices().length"></span> choices
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <template x-for="(choice, index) in form.choices" :key="index">
                                            <div class="flex items-center gap-2">
                                                <!-- 🟢 FIX: Bind to choice.label to match backend validation (choices.*.label) -->
                                                <input type="text" x-model="choice.label"
                                                    @input="validateField('choices')"
                                                    :class="getChoiceFieldClass(index)"
                                                    :placeholder="'Choice ' + (index + 1)" maxlength="255"
                                                    class="p-3 block w-full rounded-lg border transition-all duration-200 text-sm">
                                                <button type="button" @click="removeChoice(index)"
                                                    x-show="form.choices.length > 2"
                                                    class="px-2 py-2 text-rose-600 dark:text-rose-400 border border-rose-300 dark:border-rose-700 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors text-sm">
                                                    ×
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <button type="button" @click="addChoice()"
                                            :disabled="form.choices.length >= 10"
                                            class="px-4 py-2 text-sm text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                            Add Choice (<span x-text="form.choices.length"></span>/10)
                                        </button>
                                    </div>

                                    <div x-show="errors.choices" x-text="errors.choices"
                                        class="text-rose-600 dark:text-rose-400 text-xs" x-transition>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Ending Time *
                                    </label>
                                    <input type="datetime-local" x-model="form.ending_time"
                                        @input="validateField('ending_time')" :class="getFieldClass('ending_time')"
                                        :min="getMinDateTime()"
                                        class="p-3 block w-full rounded-lg border transition-all duration-200 text-sm">
                                    <div x-show="errors.ending_time" x-text="errors.ending_time"
                                        class="text-rose-600 dark:text-rose-400 text-xs mt-1" x-transition>
                                    </div>
                                </div>

                                <!-- HIDDEN STARTING TIME FIELD - Required by migration -->
                                <input type="hidden" x-model="form.starting_time">

                            </div>

                            <div
                                class="bg-slate-100 dark:bg-slate-800 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 -mx-6 mt-6">
                                <button type="submit" :disabled="isSubmitting || !isFormValid()"
                                    class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                                    <template x-if="!isSubmitting">
                                        <span>Create Wager</span>
                                    </template>
                                    <template x-if="isSubmitting">
                                        <span class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            Creating...
                                        </span>
                                    </template>
                                </button>
                                <button type="button" @click="closeModal()" :disabled="isSubmitting"
                                    class="mt-3 inline-flex w-full justify-center rounded-lg bg-slate-200 dark:bg-slate-700 px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors duration-200 sm:mt-0 sm:w-auto">
                                    Cancel
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function createWagerForm() {
            return {
                isSubmitting: false,
                showModal: true,
                globalError: '',
                successMessage: '',
                form: {
                    name: '',
                    description: '',
                    max_players: 2,
                    status: 'public',
                    starting_time: '',
                    ending_time: '',
                    choices: [{
                        label: ''
                    }, {
                        label: ''
                    }]
                },
                errors: {
                    name: '',
                    description: '',
                    max_players: '',
                    starting_time: '',
                    ending_time: '',
                    choices: ''
                },
                init() {
                    this.resetFormTimes();
                    window.addEventListener('close-create-wager-modal', () => {
                        this.showModal = false;
                    });
                },
                resetFormTimes() {
                    const now = new Date();
                    this.form.starting_time = now.toISOString().slice(0, 16);
                    now.setHours(now.getHours() + 1);
                    this.form.ending_time = now.toISOString().slice(0, 16);
                },
                validateField(field) {
                    this.errors[field] = '';
                    switch (field) {
                        case 'name':
                            if (!this.form.name.trim()) {
                                this.errors.name = 'Theme is required';
                            } else if (this.form.name.length > 255) {
                                this.errors.name = 'Theme must be less than 255 characters';
                            }
                            break;
                        case 'description':
                            if (this.form.description && this.form.description.length > 1000) {
                                this.errors.description = 'Description must be less than 1000 characters';
                            }
                            break;
                        case 'max_players':
                            if (!this.form.max_players || this.form.max_players < 2 || this.form.max_players > 100) {
                                this.errors.max_players = 'Max players must be between 2 and 100';
                            }
                            break;
                        case 'ending_time':
                            if (!this.form.ending_time) {
                                this.errors.ending_time = 'End time is required';
                            } else if (new Date(this.form.ending_time) <= new Date()) {
                                this.errors.ending_time = 'Wager has to end at least one hour from now';
                            }
                            break;
                        case 'choices':
                            const validChoices = this.getValidChoices();
                            if (validChoices.length < 2) {
                                this.errors.choices = 'At least 2 valid choices are required';
                            } else if (this.hasDuplicateChoices()) {
                                this.errors.choices = 'Duplicate choices are not allowed';
                            }
                            break;
                    }
                },
                getValidChoices() {
                    return this.form.choices
                        .map(choice => choice.label.trim())
                        .filter(label => label !== '');
                },
                hasDuplicateChoices() {
                    const validChoices = this.getValidChoices();
                    const unique = [...new Set(validChoices.map(c => c.toLowerCase()))];
                    return validChoices.length !== unique.length;
                },
                getFieldClass(field) {
                    const baseClass = 'p-3 block w-full rounded-lg border transition-all duration-200 text-sm';
                    const errorClass =
                        'border-rose-300 dark:border-rose-600 bg-rose-50 dark:bg-rose-900/20 text-slate-900 dark:text-slate-100';
                    const validClass =
                        'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500';
                    if (this.errors[field]) {
                        return `${baseClass} ${errorClass}`;
                    }
                    return `${baseClass} ${validClass}`;
                },
                getChoiceFieldClass(index) {
                    const baseClass = 'p-3 block w-full rounded-lg border transition-all duration-200 text-sm';
                    const errorClass =
                        'border-rose-300 dark:border-rose-600 bg-rose-50 dark:bg-rose-900/20 text-slate-900 dark:text-slate-100';
                    const validClass =
                        'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500';
                    const choiceValue = this.form.choices[index].label;
                    if (this.errors.choices && (!choiceValue || choiceValue.trim() === '')) {
                        return `${baseClass} ${errorClass}`;
                    }
                    return `${baseClass} ${validClass}`;
                },
                addChoice() {
                    if (this.form.choices.length < 10) {
                        this.form.choices.push({
                            label: ''
                        });
                    }
                },
                removeChoice(index) {
                    if (this.form.choices.length > 2) {
                        this.form.choices.splice(index, 1);
                        this.validateField('choices');
                    }
                },
                getMinDateTime() {
                    const now = new Date();
                    return now.toISOString().slice(0, 16);
                },
                isFormValid() {
                    const validChoices = this.getValidChoices();
                    const hasName = !!this.form.name.trim();
                    const hasEndingTime = !!this.form.ending_time;
                    const validMaxPlayers = this.form.max_players >= 2 && this.form.max_players <= 100;
                    const hasEnoughChoices = validChoices.length >= 2;
                    const hasDuplicates = this.hasDuplicateChoices();
                    const hasErrors = Object.values(this.errors).some(error => error !== '');
                    return hasName && hasEndingTime && validMaxPlayers && hasEnoughChoices && !hasDuplicates && !hasErrors;
                },
                async submitForm() {
                    this.globalError = '';
                    this.successMessage = '';
                    this.validateField('name');
                    this.validateField('description');
                    this.validateField('max_players');
                    this.validateField('ending_time');
                    this.validateField('choices');
                    if (!this.isFormValid() || Object.values(this.errors).some(error => error !== '')) {
                        this.globalError = 'Please fix all validation errors before submitting.';
                        return;
                    }
                    this.isSubmitting = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        const formData = new FormData();
                        formData.append('_token', token);
                        formData.append('name', this.form.name.trim());
                        formData.append('description', this.form.description.trim());
                        formData.append('max_players', this.form.max_players);
                        formData.append('privacy', this.form.status); // ← CHANGE: status → privacy
                        formData.append('starting_time', new Date().toISOString());
                        formData.append('ending_time', new Date(this.form.ending_time).toISOString());
                        const choicesToSubmit = this.form.choices.filter(choice => choice.label.trim() !== '');
                        choicesToSubmit.forEach((choice, index) => {
                            formData.append(`choices[${index}][label]`, choice.label.trim());
                        });
                        const response = await fetch('/wagers', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        const result = await response.json().catch(() => null);
                        if (response.ok) {
                            this.successMessage = 'Wager created successfully! Redirecting...';
                            this.resetFormTimes();
                            this.form.choices = [{
                                label: ''
                            }, {
                                label: ''
                            }];
                            if (typeof window !== 'undefined' && window.location) {
                                setTimeout(() => {
                                    window.location.href = '/wagers';
                                }, 800);
                            }
                        } else {
                            if (result && result.errors) {
                                Object.keys(result.errors).forEach(field => {
                                    const key = field.includes('.') ? field.split('.')[0] : field;
                                    if (this.errors.hasOwnProperty(key)) {
                                        this.errors[key] = result.errors[field][0];
                                    }
                                });
                                this.globalError = 'Please fix the validation errors.';
                            } else {
                                this.globalError = result?.message || 'Failed to create wager. Please try again.';
                            }
                        }
                    } catch (error) {
                        this.globalError = 'An error occurred while creating the wager.';
                        console.error('Error creating wager:', error);
                    } finally {
                        this.isSubmitting = false;
                    }
                },
                closeModal() {
                    if (this.isSubmitting) {
                        return;
                    }
                    this.globalError = '';
                    this.successMessage = '';
                    this.showModal = false;
                    if (typeof window !== 'undefined') {
                        window.dispatchEvent(new CustomEvent('close-create-wager-modal'));
                    }
                }
            };
        }
    </script>
