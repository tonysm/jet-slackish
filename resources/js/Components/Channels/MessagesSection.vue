<template>
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
                <input autofocus ref="newMessageInput" type="text" class="flex-1 mr-2" placeholder="Say something" v-model="form.content" />

                <button
                    type="submit"
                    class="w-10 h-10 flex items-center"
                >
                    <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path></svg>
                </button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MessagesSection",
        props: {
            messages: Array,
            currentChannel: Object,
        },
        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'POST',
                    content: null,
                }, {
                    bag: 'channelNewMessage',
                    preserveState: false,
                })
            }
        },
        watch: {
            messages(newMessages, oldMessages) {
                if (newMessages.length !== oldMessages.length) {
                    this.scrollBottom('smooth');
                }
            },
        },
        methods: {
            sendNewMessage() {
                this.form.post(`/channels/${this.currentChannel.id}/messages`, {
                    preserveState: false,
                })
            },
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
        },

        mounted() {
            this.scrollBottom();
            this.focusOnMessage();
        },
    }
</script>
