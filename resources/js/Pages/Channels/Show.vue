<template>
    <div>
        <div class="flex divide-x divide-gray-100">
            <div class="w-16 flex-none flex flex-col min-h-screen h-screen">
                <div class="overflow-y-auto py-2">
                    <TeamList
                        :teams="teams"
                        :current-team="currentTeam"
                    />
                </div>
            </div>
            <div class="flex flex-col min-h-screen h-screen w-full overflow-hidden">
                <CurrentTeamHeader
                    :current-team="currentTeam"
                    :current-channel="currentChannel"
                    :teams="teams"
                />
                <div class="flex-1 overflow-y-auto flex divide-x divide-gray-200">
                    <div class="w-56 flex-none flex flex-col justify-between bg-gray-100 overflow-hidden">
                        <ChannelList
                            :channels="allChannels"
                        />
                        <CurrentUser
                            :user="$page.user"
                        />
                    </div>
                    <MessagesSection
                        :messages="allMessages"
                        :load-more="loadMore"
                        :has-more-messages="Boolean(messages.next_page_url)"
                        :current-channel="currentChannel"
                    />
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
import JetDropdown from '../../Jetstream/Dropdown'
import JetDropdownLink from '../../Jetstream/DropdownLink'
import JetNavLink from '../../Jetstream/NavLink'
import JetResponsiveNavLink from '../../Jetstream/ResponsiveNavLink'
import ChannelList from "../../Components/Channels/ChannelList";
import MessagesSection from "../../Components/Channels/MessagesSection";
import CurrentTeamHeader from "../../Components/Teams/CurrentTeamHeader";
import CurrentUser from "../../Components/Users/CurrentUser";
import TeamList from "../../Components/Teams/TeamList";
import PersistentSockets from "./PersistentSockets";

export default {
    layout: PersistentSockets,
    components: {
        TeamList,
        CurrentUser,
        CurrentTeamHeader,
        MessagesSection,
        ChannelList,
        JetApplicationLogo,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        JetResponsiveNavLink,
        JetDialogModal,
    },

    props: {
        currentChannel: Object,
        channels: Array,
        messages: Object,
    },

    data() {
        return {
            showingNavigationDropdown: false,
            messagesOverSocket: {},
            channelsOverSocket: {},
            localMessages: this.messages.data,
            paginationMeta: this.messages,
        };
    },

    methods: {
        loadMore() {
            return axios.get(`/channels/${this.currentChannel.id}?page=${this.paginationMeta.current_page + 1}`)
                .then(({ data }) => {
                    this.localMessages = [
                        ...data.data,
                        ...this.localMessages,
                    ];
                    this.paginationMeta = data;
                });
        },
        switchToTeam(team) {
            this.$inertia.put('/current-team', {
                'team_id': team.id
            }, {
                preserveState: false
            });
        },
    },

    computed: {
        allChannels() {
            return [
                ...this.channels,
                ...(this.channelsOverSocket[`${this.currentTeam.id}`] || []),
            ];
        },
        allMessages() {
            return [
                ...this.localMessages,
                ...(this.messagesOverSocket[`${this.currentChannel.id}`] || []),
            ];
        },
        channelIds() {
            return this.channels.map(({ id }) => id);
        },
        messageIds() {
            return this.localMessages.map(({ id }) => id);
        },
        currentTeam() {
            return this.$page.user.current_team;
        },
        teams() {
            return Object.values(this.$page.user.all_teams);
        },
        path() {
            return window.location.pathname
        },
    },

    mounted() {
        window.Bus.$on('messages.new', ({ message }) => {
            if (this.messageIds.includes(message.id)) return;

            if (! this.messagesOverSocket[`${message.channel_id}`]) {
                this.$set(this.messagesOverSocket, `${message.channel_id}`, []);
            }

            this.messagesOverSocket[`${message.channel_id}`].push(message);
        });

        window.Bus.$on('channels.new', ({ channel }) => {
            if (this.channelIds.includes(channel.id)) return;

            if (! this.channelsOverSocket[`${channel.team_id}`]) {
                this.$set(this.channelsOverSocket, `${channel.team_id}`, []);
            }

            this.channelsOverSocket[`${channel.team_id}`].push(channel);
        });
    },

    beforeDestroy() {
        window.Bus.$off('messages.new');
        window.Bus.$off('channels.new');
    },
}
</script>
