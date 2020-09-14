<template>
    <div>
        <div class="flex divide-x divide-gray-100">
            <div class="w-16 flex-none flex flex-col min-h-screen h-screen">
                <div class="overflow-y-auto py-2">
                    <ul class="text-center space-y-2">
                        <li>
                            <inertia-link href="/dashboard" title="Settings" class="block bg-gray-100 rounded-full h-10 w-10 flex items-center justify-center text-gray-500 mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </inertia-link>
                        </li>
                        <li class="border-b border-gray-300 mx-6 mt-3"></li>
                        <li v-for="team in teams" :key="team.id">
                            <template v-if="team.id === currentTeam.id">
                                <div :title="`You are currently at ${team.name}`">
                                    <jet-application-mark class="h-10 w-auto mx-auto" />
                                </div>
                            </template>
                            <template v-else>
                                <inertia-link
                                    :href="`/switch-teams/${team.id}`"
                                    method="put"
                                    :title="`Switch to ${team.name}`"
                                >
                                    <jet-application-mark class="h-10 w-auto mx-auto" />
                                </inertia-link>
                            </template>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col min-h-screen h-screen w-full overflow-hidden">
                <div class="flex divide-x divide-gray-200 border-b border-gray-300">
                    <div class="w-56 flex-none bg-gray-100">
                        <span class="w-full flex justify-between items-center p-2 text-gray-700 border-2 border-transparent focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                            <span v-text="currentTeam.name"></span>
                            <a href="#" title="New channel">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </a>
                        </span>
                    </div>
                    <div class="p-2 text-gray-700">#{{ currentChannel.name }}</div>
                </div>
                <div class="flex-1 overflow-y-auto flex divide-x divide-gray-200">
                    <div class="w-56 flex-none flex flex-col justify-between bg-gray-100 overflow-hidden overflow-y-auto">
                        <ul class="p-4 text-gray-700 space-y-1">
                            <li class="font-semibold" v-for="channel in channels" :key="channel.id">
                                <inertia-link :href="`/channels/${channel.id}`" class="block">#{{ channel.name }}</inertia-link>
                            </li>
                        </ul>
                        <div class="border-t border-gray-200">
                            <span class="w-full flex items-center p-2 text-gray-700 border-2 border-transparent focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full" :src="$page.user.profile_photo_url" :alt="$page.user.name" />
                                </div>
                                <span class="ml-2">
                                    {{ $page.user.name }}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="w-full flex flex-col">
                        <div class="bg-white flex-1 flex flex-col divide-y divide-gray-100 overflow-hidden overflow-y-auto" ref="messagesSection" scroll-region>
                            <div class="p-2 bg-white rounded" v-for="message in messages" :key="message.id">
                                <span class="w-full flex items-start p-2 text-gray-700 border-2 border-transparent focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div class="flex-shrink-0">
                                        <img class="h-8 w-8 rounded-full" :src="message.user.profile_photo_url" :alt="message.user.name" />
                                    </div>
                                    <span class="ml-2">
                                        <span class="font-semibold">
                                            {{ message.user.name }}:
                                        </span>
                                        <span v-text="message.content"></span>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200">
                            <form
                                @submit.prevent="sendNewMessage"
                                class="w-full flex items-center p-2 text-gray-700 border-2 border-transparent focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out"
                            >
                                <input autofocus ref="newMessageInput" type="text" class="flex-1 mr-2" placeholder="Say something" v-model="newMessageForm.content" />

                                <button
                                    type="submit"
                                    class="w-10 h-10 flex items-center"
                                >
                                    <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Portal -->
        <portal-target name="modal" multiple>
        </portal-target>
    </div>
</template>

<script>
import JetApplicationLogo from '../../Jetstream/ApplicationLogo'
import JetDialogModal from '../../Jetstream/DialogModal'
import JetApplicationMark from '../../Jetstream/ApplicationMark'
import JetDropdown from '../../Jetstream/Dropdown'
import JetDropdownLink from '../../Jetstream/DropdownLink'
import JetNavLink from '../../Jetstream/NavLink'
import JetResponsiveNavLink from '../../Jetstream/ResponsiveNavLink'

export default {
    components: {
        JetApplicationLogo,
        JetApplicationMark,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        JetResponsiveNavLink,
        JetDialogModal,
    },

    props: {
        currentChannel: Object,
        channels: Array,
        messages: Array,
    },

    watch: {
        messages (newMessages, oldMessages) {
            if (newMessages.length !== oldMessages.length) {
                this.scrollBottom('smooth');
            }
        },
    },

    data() {
        return {
            showingNavigationDropdown: false,
            newMessageForm: this.$inertia.form({
                '_method': 'POST',
                content: null,
            }, {
                bag: 'channelNewMessage',
            }),
        }
    },

    methods: {
        scrollBottom(behavior = 'auto') {
            this.$nextTick(() => {
                this.$refs.messagesSection.scroll({
                    behavior: behavior,
                    top: this.$refs.messagesSection.scrollHeight,
                });
            });
        },

        focusOnMessage() {
            this.$refs.newMessageInput.focus();
        },

        switchToTeam(team) {
            this.$inertia.put('/current-team', {
                'team_id': team.id
            }, {
                preserveState: false
            })
        },

        sendNewMessage() {
            this.newMessageForm.post(`/channels/${this.currentChannel.id}/messages`, {
                preserveScroll: true,
            });
        }
    },

    computed: {
        currentTeam() {
            return this.$page.user.current_team;
        },
        teams() {
            return Object.values(this.$page.user.all_teams);
        },
        path() {
            return window.location.pathname
        }
    },

    mounted() {
        this.scrollBottom();
        this.focusOnMessage();
    },
}
</script>
