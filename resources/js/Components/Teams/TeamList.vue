<template>
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
</template>

<script>
    import JetApplicationMark from '../../Jetstream/ApplicationMark'

    export default {
        components: {
            JetApplicationMark,
        },
        name: "TeamList",
        props: {
            teams: Array,
            currentTeam: Object,
        }
    }
</script>
