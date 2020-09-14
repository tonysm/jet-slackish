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
                    <div class="w-56 flex-none flex flex-col justify-between bg-gray-100 overflow-hidden overflow-y-auto">
                        <ChannelList
                            :channels="channels"
                        />
                        <CurrentUser
                            :user="$page.user"
                        />
                    </div>
                    <MessagesSection
                        :messages="messages"
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

export default {
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
        messages: Array,
    },

    data() {
        return {
            showingNavigationDropdown: false,
        }
    },

    methods: {
        switchToTeam(team) {
            this.$inertia.put('/current-team', {
                'team_id': team.id
            }, {
                preserveState: false
            })
        },
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
}
</script>
