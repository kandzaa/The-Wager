<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wager Form with Real-time Validation</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ionicons/7.1.0/ionicons/ionicons.esm.js" type="module"></script>
    <script nomodule src="https://cdnjs.cloudflare.com/ajax/libs/ionicons/7.1.0/ionicons/ionicons.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .error-shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .success-pulse {
            animation: pulse-green 0.5s ease-in-out;
        }

        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-slate-950">

    <div x-data="wagerFormHandler()" class="fixed inset-0 z-50">
        <!-- Backdrop -->
        <div class="fixed inset-0 backdrop-blur-sm bg-slate-950/50" @click="showModal = false"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-slate-50 dark:bg-slate-900 text-left shadow-2xl transition-all sm:w-full sm:max-w-2xl max-h-[90vh] overflow-y-auto ring-1 ring-slate-300 dark:ring-slate-700 backdrop-blur-sm">

                    <div class="bg-slate-50 dark:bg-slate-900 px-4 sm:pb-4">
                        <!-- Global Error Display -->
                        <div x-show="globalError" x-text="globalError"
                            class="bg-rose-100 dark:bg-rose-900/30 border border-rose-300 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-lg relative mb-4 mt-4"
                            role="alert">
                        </div>

                        <!-- Success Message -->
                        <div x-show="successMessage" x-text="successMessage"
                            class="bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-lg relative mb-4 mt-4"
                            role="alert">
                        </div>

                        <form @submit.prevent="submitForm" class="mt-4">
                            <div class="space-y-6">

                                <!-- Theme/Name Field -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Theme
                                        *</label>
                                    <input type="text" x-model="form.name" @input="validateField('name')"
                                        @blur="validateField('name')" :class="getFieldClass('name')"
                                        placeholder="Enter wager theme"
                                        class="p-3 mt-1 block w-full rounded-lg border transition-all duration-200 text-sm">
                                    <div x-show="errors.name" x-text="errors.name"
                                        class="text-rose-600 dark:text-rose-400 text-xs mt-1"></div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        <span x-text="form.name.length"></span>/255 characters
                                    </div>
                                </div>

                                <!-- Description Field -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description</label>
                                    <textarea x-model="form.description" @input="validateField('description')" @blur="validateField('description')"
                                        :class="getFieldClass('description')" placeholder="Optional description" rows="3"
                                        class="p-3 mt-1 block w-full rounded-lg border transition-all duration-200 text-sm resize-none"></textarea>
                                    <div x-show="errors.description" x-text="errors.description"
                                        class="text-rose-600 dark:text-rose-400 text-xs mt-1"></div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        <span x-text="form.description.length"></span>/1000 characters
                                    </div>
                                </div>

                                <!-- Max Players Field -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Max
                                            Players *</label>
                                        <input type="number" x-model.number="form.max_players"
                                            @input="validateField('max_players')" @blur="validateField('max_players')"
                                            :class="getFieldClass('max_players')" placeholder="Min: 2, Max: 100"
                                            min="2" max="100"
                                            class="p-3 mt-1 block w-full rounded-lg border transition-all duration-200 text-sm">
                                        <div x-show="errors.max_players" x-text="errors.max_players"
                                            class="text-rose-600 dark:text-rose-400 text-xs mt-1"></div>
                                    </div>
                                </div>

                                <!-- Visibility Toggle -->
                                <div
                                    class="flex items-center justify-between p-4 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="text-sm font-medium text-slate-700 dark:text-slate-300">Visibility:</span>
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-2">
                                                <ion-icon name="lock-open" class="size-5 mr-2"></ion-icon>
                                                <span
                                                    :class="!form.isPrivate ?
                                                        'font-medium text-emerald-600 dark:text-emerald-400' :
                                                        'text-slate-400'"
                                                    class="text-sm transition-colors duration-200">Public</span>
                                            </div>

                                            <button
                                                @click="form.isPrivate = !form.isPrivate; validateField('visibility')"
                                                type="button"
                                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                                :class="form.isPrivate ? 'bg-rose-200 dark:bg-rose-800' :
                                                    'bg-emerald-200 dark:bg-emerald-800'">
                                                <span class="sr-only">Toggle lobby visibility</span>
                                                <span aria-hidden="true"
                                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                    :class="form.isPrivate ? 'translate-x-5' : 'translate-x-0'"></span>
                                            </button>

                                            <div class="flex items-center gap-2">
                                                <ion-icon name="lock-closed"></ion-icon>
                                                <span
                                                    :class="form.isPrivate ? 'font-medium text-rose-600 dark:text-rose-400' :
                                                        'text-slate-400'"
                                                    class="text-sm transition-colors duration-200">Private</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Choices Section -->
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <label
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Choices
                                            *</label>
                                        <span class="text-xs text-slate-500">
                                            <span x-text="form.choices.filter(c => c.trim()).length"></span> choices
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <template x-for="(choice, index) in form.choices" :key="index">
                                            <div class="flex items-center gap-2">
                                                <input type="text" x-model="form.choices[index]"
                                                    @input="validateField('choices')" @blur="validateField('choices')"
                                                    :class="getChoiceFieldClass(index)"
                                                    :placeholder="'Choice ' + (index + 1)" maxlength="255"
                                                    class="p-3 block w-full rounded-lg border transition-all duration-200 text-sm">
                                                <button type="button" @click="removeChoice(index)"
                                                    x-show="form.choices.length > 1"
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
                                        class="text-rose-600 dark:text-rose-400 text-xs"></div>
                                </div>

                                <!-- End Time Field -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">End
                                        Time *</label>
                                    <input type="datetime-local" x-model="form.ending_time"
                                        @input="validateField('ending_time')" @blur="validateField('ending_time')"
                                        :class="getFieldClass('ending_time')" :min="minDateTime"
                                        class="p-3 mt-1 block w-full rounded-lg border transition-all duration-200 text-sm">
                                    <div x-show="errors.ending_time" x-text="errors.ending_time"
                                        class="text-rose-600 dark:text-rose-400 text-xs mt-1"></div>
                                    <div x-show="form.ending_time" class="text-xs text-slate-500 mt-1">
                                        Ends <span x-text="getTimeUntilEnd()"></span>
                                    </div>
                                </div>

                                <!-- Form Validation Summary -->
                                <div x-show="!isFormValid()"
                                    class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <ion-icon name="warning-outline"
                                            class="text-amber-600 dark:text-amber-400 mt-0.5"></ion-icon>
                                        <div class="text-sm text-amber-700 dark:text-amber-300">
                                            <div class="font-medium mb-1">Please fix the following issues:</div>
                                            <ul class="list-disc list-inside space-y-1 text-xs">
                                                <li x-show="!form.name.trim()">Theme is required</li>
                                                <li x-show="form.name.length > 255">Theme is too long</li>
                                                <li x-show="form.description.length > 1000">Description is too long
                                                </li>
                                                <li
                                                    x-show="!form.max_players || form.max_players < 2 || form.max_players > 100">
                                                    Max players must be between 2-100</li>
                                                <li x-show="!form.ending_time">End time is required</li>
                                                <li
                                                    x-show="form.ending_time && new Date(form.ending_time) <= new Date()">
                                                    End time must be in the future</li>
                                                <li x-show="form.choices.filter(c => c.trim()).length < 1">At least one
                                                    choice is required</li>
                                                <li x-show="hasDuplicateChoices()">Duplicate choices are not allowed
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="bg-slate-100 dark:bg-slate-800 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-300 dark:border-slate-700">
                        <button type="submit" :disabled="isSubmitting || !isFormValid()"
                            :class="isSubmitting ? 'opacity-50 cursor-not-allowed' : ''"
                            class="inline-flex items-center w-full justify-center rounded-lg bg-emerald-600 hover:bg-emerald-500 disabled:bg-slate-400 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors sm:ml-3 sm:w-auto">
                            <span x-show="!isSubmitting" x-text="editId ? 'Save Changes' : 'Create Wager'"></span>
                            <span x-show="isSubmitting" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Submitting...
                            </span>
                        </button>
                        </form>
                        <button type="button" @click="showModal = false" :disabled="isSubmitting"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 disabled:opacity-50 px-4 py-2.5 text-sm font-semibold text-slate-900 dark:text-slate-100 shadow-sm transition-colors sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function wagerFormHandler() {
            return {
                showModal: true,
                editId: null,
                isSubmitting: false,
                globalError: '',
                successMessage: '',

                form: {
                    name: '',
                    description: '',
                    max_players: 2,
                    isPrivate: false,
                    choices: ['', ''],
                    ending_time: ''
                },

                errors: {},

                init() {
                    // Set minimum datetime to current time + 1 hour
                    const now = new Date();
                    now.setHours(now.getHours() + 1);
                    this.minDateTime = now.toISOString().slice(0, 16);

                    // Set default end time to 24 hours from now
                    const defaultEnd = new Date();
                    defaultEnd.setHours(defaultEnd.getHours() + 24);
                    this.form.ending_time = defaultEnd.toISOString().slice(0, 16);
                },

                validateField(fieldName) {
                    this.errors[fieldName] = '';

                    switch (fieldName) {
                        case 'name':
                            if (!this.form.name.trim()) {
                                this.errors.name = 'Theme is required';
                            } else if (this.form.name.length > 255) {
                                this.errors.name = 'Theme cannot exceed 255 characters';
                            }
                            break;

                        case 'description':
                            if (this.form.description.length > 1000) {
                                this.errors.description = 'Description cannot exceed 1000 characters';
                            }
                            break;

                        case 'max_players':
                            if (!this.form.max_players) {
                                this.errors.max_players = 'Max players is required';
                            } else if (this.form.max_players < 2) {
                                this.errors.max_players = 'Minimum 2 players required';
                            } else if (this.form.max_players > 100) {
                                this.errors.max_players = 'Maximum 100 players allowed';
                            }
                            break;

                        case 'ending_time':
                            if (!this.form.ending_time) {
                                this.errors.ending_time = 'End time is required';
                            } else {
                                const endTime = new Date(this.form.ending_time);
                                const now = new Date();
                                if (endTime <= now) {
                                    this.errors.ending_time = 'End time must be in the future';
                                } else if (endTime.getTime() - now.getTime() < 3600000) { // 1 hour
                                    this.errors.ending_time = 'End time must be at least 1 hour from now';
                                }
                            }
                            break;

                        case 'choices':
                            const validChoices = this.form.choices.filter(c => c.trim());
                            if (validChoices.length < 1) {
                                this.errors.choices = 'At least one choice is required';
                            } else if (this.hasDuplicateChoices()) {
                                this.errors.choices = 'Duplicate choices are not allowed';
                            } else if (validChoices.some(c => c.length > 255)) {
                                this.errors.choices = 'Each choice cannot exceed 255 characters';
                            }
                            break;
                    }
                },

                getFieldClass(fieldName) {
                    const baseClass =
                        'border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 shadow-sm focus:ring-emerald-500 transition-all duration-200';

                    if (this.errors[fieldName]) {
                        return baseClass + ' border-rose-500 focus:border-rose-500 error-shake';
                    } else if (this.isFieldValid(fieldName)) {
                        return baseClass + ' border-emerald-500 focus:border-emerald-500 success-pulse';
                    }
                    return baseClass + ' focus:border-emerald-500';
                },

                getChoiceFieldClass(index) {
                    const baseClass =
                        'border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 shadow-sm focus:ring-emerald-500 transition-all duration-200';

                    if (this.errors.choices) {
                        return baseClass + ' border-rose-500 focus:border-rose-500';
                    }
                    return baseClass + ' focus:border-emerald-500';
                },

                isFieldValid(fieldName) {
                    switch (fieldName) {
                        case 'name':
                            return this.form.name.trim() && this.form.name.length <= 255;
                        case 'description':
                            return this.form.description.length <= 1000;
                        case 'max_players':
                            return this.form.max_players >= 2 && this.form.max_players <= 100;
                        case 'ending_time':
                            if (!this.form.ending_time) return false;
                            const endTime = new Date(this.form.ending_time);
                            const now = new Date();
                            return endTime > now && (endTime.getTime() - now.getTime()) >= 3600000;
                        case 'choices':
                            const validChoices = this.form.choices.filter(c => c.trim());
                            return validChoices.length >= 1 && !this.hasDuplicateChoices();
                        default:
                            return true;
                    }
                },

                hasDuplicateChoices() {
                    const validChoices = this.form.choices.filter(c => c.trim());
                    const uniqueChoices = [...new Set(validChoices.map(c => c.trim().toLowerCase()))];
                    return validChoices.length !== uniqueChoices.length;
                },

                isFormValid() {
                    return this.isFieldValid('name') &&
                        this.isFieldValid('description') &&
                        this.isFieldValid('max_players') &&
                        this.isFieldValid('ending_time') &&
                        this.isFieldValid('choices');
                },

                addChoice() {
                    if (this.form.choices.length < 10) {
                        this.form.choices.push('');
                    }
                },

                removeChoice(index) {
                    if (this.form.choices.length > 1) {
                        this.form.choices.splice(index, 1);
                        this.validateField('choices');
                    }
                },

                getTimeUntilEnd() {
                    if (!this.form.ending_time) return '';
                    const endTime = new Date(this.form.ending_time);
                    const now = new Date();
                    const diff = endTime.getTime() - now.getTime();

                    if (diff <= 0) return 'in the past';

                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                    if (days > 0) return `in ${days} days, ${hours} hours`;
                    if (hours > 0) return `in ${hours} hours, ${minutes} minutes`;
                    return `in ${minutes} minutes`;
                },

                async submitForm() {
                    this.globalError = '';
                    this.successMessage = '';

                    // Validate all fields
                    ['name', 'description', 'max_players', 'ending_time', 'choices'].forEach(field => {
                        this.validateField(field);
                    });

                    if (!this.isFormValid()) {
                        this.globalError = 'Please fix all validation errors before submitting.';
                        return;
                    }

                    this.isSubmitting = true;

                    try {
                        // Simulate API call
                        await this.simulateApiCall();

                        this.successMessage = 'Wager created successfully!';

                        // Reset form after success
                        setTimeout(() => {
                            this.resetForm();
                        }, 2000);

                    } catch (error) {
                        this.globalError = error.message || 'Failed to create wager. Please try again.';
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                async simulateApiCall() {
                    // Simulate network delay
                    await new Promise(resolve => setTimeout(resolve, 1500));

                    // Simulate potential server-side validation errors
                    const random = Math.random();
                    if (random < 0.1) { // 10% chance of server error
                        throw new Error('Server validation failed: Theme already exists.');
                    }
                    if (random < 0.05) { // 5% chance of network error
                        throw new Error('Network error. Please check your connection and try again.');
                    }

                    return {
                        success: true
                    };
                },

                resetForm() {
                    this.form = {
                        name: '',
                        description: '',
                        max_players: 2,
                        isPrivate: false,
                        choices: ['', ''],
                        ending_time: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString().slice(0, 16)
                    };
                    this.errors = {};
                    this.globalError = '';
                    this.successMessage = '';
                }
            }
        }
    </script>
</body>

</html>
