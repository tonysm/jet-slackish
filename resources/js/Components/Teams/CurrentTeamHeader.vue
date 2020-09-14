<template>
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
</template>

<script>
export default {
    name: "CurrentTeamHeader",
    props: {
        currentTeam: Object,
        currentChannel: Object,
        teams: Array,
    },
}
</script>
