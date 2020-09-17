<template>
    <ul class="p-4 text-gray-700 space-y-1 overflow-y-auto">
        <li class="font-semibold" v-for="channel in channels" :key="channel.id">
            <inertia-link :href="`/channels/${channel.id}`" class="block">#{{ channel.name }}</inertia-link>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "ChannelList",
        props: {
            channels: Array,
        },
    };
</script>
