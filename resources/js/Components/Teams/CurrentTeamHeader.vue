<template>
    <div class="flex divide-x divide-gray-200 border-b border-gray-300">
        <div class="w-56 flex-none bg-gray-100">
            <span class="w-full flex justify-between items-center p-2 text-gray-700 border-2 border-transparent focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                <span v-text="currentTeam.name"></span>
                <a href="#" title="New channel" @click.prevent="showNewChannelModal">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </a>
            </span>
        </div>
        <div class="p-2 text-gray-700">#{{ currentChannel.name }}</div>

        <!-- New Form Modal -->
        <jet-dialog-modal :show="newChannelModal" @close="newChannelModal = false">
            <template #title>
                New Channel
            </template>

            <template #content>
                What is the name of the channel you want to create? (No need to start with a hashtag)

                <div class="mt-4">
                    <jet-input type="text" class="mt-1 block w-3/4" placeholder="Channel name"
                               ref="channelNameInput"
                               v-model="channelForm.name"
                               @keyup.enter.native="createChannel" />

                    <jet-input-error :message="channelForm.error('name')" class="mt-2" />
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="newChannelModal = false">
                    Nevermind
                </jet-secondary-button>

                <jet-button class="ml-2" @click.native="createChannel" :class="{ 'opacity-25': channelForm.processing }" :disabled="channelForm.processing">
                    Create Channel
                </jet-button>
            </template>
        </jet-dialog-modal>
    </div>
</template>

<script>
import JetDialogModal from '../../Jetstream/DialogModal';
import JetButton from '../../Jetstream/Button';
import JetSecondaryButton from '../../Jetstream/SecondaryButton';
import JetInput from '../../Jetstream/Input';
import JetInputError from '../../Jetstream/InputError';

export default {
    components: {
        JetDialogModal,
        JetButton,
        JetSecondaryButton,
        JetInput,
        JetInputError,
    },
    name: "CurrentTeamHeader",
    props: {
        currentTeam: Object,
        currentChannel: Object,
        teams: Array,
    },
    data () {
        return {
            newChannelModal: false,
            channelForm: this.$inertia.form({
                name: null,
            }, { bag: 'newChannelForm', }),
        };
    },
    methods: {
        showNewChannelModal () {
            this.newChannelModal = true;

            this.$nextTick(() => {
                this.$refs.channelNameInput.focus();
            });
        },
        createChannel() {
            this.channelForm.post(`/teams/${this.currentTeam.id}/channels`)
                .then(() => {
                    if ( ! this.channelForm.hasErrors()) {
                        this.newChannelModal = false;
                    }
                });
        }
    },
}
</script>
