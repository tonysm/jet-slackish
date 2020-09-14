<template>
    <div class="border-t border-gray-200">
        <span class="w-full flex items-center p-2 text-gray-700 border-2 border-transparent focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
            <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full" :src="user.profile_photo_url" :alt="user.name" />
            </div>
            <span class="ml-2">
                {{ user.name }}
            </span>
        </span>
    </div>
</template>

<script>
    export default {
        name: "CurrentUser",
        props: {
            user: Object,
        },
    }
</script>
