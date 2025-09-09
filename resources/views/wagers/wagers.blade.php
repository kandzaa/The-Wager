<x-app-layout>


    <div x-data="{ showModal: false, editId: null, form: { id: null, name: '', description: '', max_players: 2, visibility: 'public', ending_time_local: '', choices: [] } }" x-on:edit-wager.window="form = $event.detail; editId = form.id; showModal = true"
        x-effect="document.body.classList.toggle('overflow-hidden', showModal)" class="max-w-full mx-auto   ">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">



            @include('wagers.wager-card', ['wagers' => $wagers])

        </div>

        <div x-show="showModal">
            @include('wagers.create_wager', ['friends' => $friends])
        </div>
    </div>

</x-app-layout>
