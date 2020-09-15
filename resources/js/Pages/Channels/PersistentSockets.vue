<template>
    <div>
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "PersistentSockets",
    sockets: {},
    computed: {
        teams() {
            return this.$page.user.all_teams;
        },
        currentTeam() {
            return this.$page.user.current_team;
        },
        currentChannel() {
            return this.$page.currentChannel;
        },
    },
    methods: {
        disconnectFromTeamsChannels() {
            Object.values(this.$options.sockets).forEach(({ channelName }) => {
                window.Echo.leave(channelName);
            });

            this.$options.sockets = {};
        },

        connectToTeamsChannels() {
            this.teams.forEach((team) => {
                if (this.$options.sockets[`${team.id}`]) return;

                this.$options.sockets[`${team.id}`] = this.joinTeam(team);
            });
        },

        joinTeam(team) {
            const channelName = `App.Models.Team.${team.id}`;
            const channel = window.Echo.join(channelName);

            channel
                .listen('Channels\\NewMessage', ({ message }) => {
                    window.Bus.$emit('messages.new', { message });
                })
                .listen('Channels\\NewChannel', ({ channel }) => {
                    window.Bus.$emit('channels.new', { channel });
                });

            return { channelName, socket: channel };
        },
    },

    mounted() {
        this.connectToTeamsChannels();
    },

    beforeDestroy() {
        this.disconnectFromTeamsChannels();
    },
}
</script>
