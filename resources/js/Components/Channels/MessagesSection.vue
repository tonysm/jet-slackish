<template>
    <div class="w-full flex flex-col">
        <div class="bg-white flex-1 flex flex-col divide-y divide-gray-100 overflow-hidden overflow-y-auto" ref="messagesSection" scroll-region>
            <span ref="loadMoreSection" v-if="hasMoreMessages" class="text-sm text-center" :class="{'text-white': !loadingMore}">Loading more...</span>
            <div class="p-2 bg-white rounded" v-for="message in messages" :key="message.id" :id="`message_${message.id}`">
                <span class="w-full flex items-start p-2 text-gray-700 border-2 border-transparent focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-8 rounded-full" :src="message.user.profile_photo_url" :alt="message.user.name" />
                    </div>
                    <span class="ml-2">
                        <span class="font-semibold">
                            {{ message.user.name }}:
                        </span>
                        <span v-text="message.content.content"></span>
                    </span>
                </span>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <form
                @submit.prevent="sendNewMessage"
                class="w-full flex items-center p-2 text-gray-700 border-2 border-transparent focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out"
            >
                <input autofocus ref="newMessageInput" type="text" class="flex-1 mr-2" placeholder="Say something" v-model="form.content.content" />

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
            loadMore: Function,
            hasMoreMessages: Boolean,
        },
        data() {
            return {
                loadingMore: false,
                form: this.$inertia.form({
                    '_method': 'POST',
                    content: {
                        content: null,
                        type: 'text_messages',
                    },
                }, {
                    bag: 'channelNewMessage',
                    preserveState: true,
                }),
            }
        },
        watch: {
            hasMoreMessages(newHasMoreMessages) {
                if (! newHasMoreMessages) {
                    this.stopObservingForMore();
                }
            },
            messages(newMessages, oldMessages) {
                if (newMessages.length !== oldMessages.length) {
                    this.scrollBottom('smooth');
                }
            },
        },
        methods: {
            async handleLoadMore() {
                this.loadingMore = true;

                await this.preserveHeight(this.loadMore);

                this.loadingMore = false;
            },
            sendNewMessage() {
                this.form.post(`/channels/${this.currentChannel.id}/messages`, {
                    preserveState: false,
                });
            },
            async preserveHeight(callback) {
                const previousHeight = this.$refs.messagesSection.scrollHeight;
                const previousScroll = this.$refs.messagesSection.scrollTop;

                const positionRelativeToBottom = previousHeight - previousScroll;

                await callback();

                this.$nextTick(() => {
                    this.$refs.messagesSection.scroll({
                        behavior: 'instant',
                        top: this.$refs.messagesSection.scrollHeight - positionRelativeToBottom,
                    });
                })
            },
            scrollBottom(behavior = 'auto') {
                return new Promise((resolve) => {
                    this.$refs.messagesSection.scroll({
                        behavior: behavior,
                        top: this.$refs.messagesSection.scrollHeight,
                    });

                    resolve();
                });
            },

            focusOnMessage() {
                this.$refs.newMessageInput.focus();
            },

            handleObserverLoadMore(entries) {
                if (this.loadingMore) return;

                entries.forEach((entry) => {
                    if (entry.intersectionRatio >= 0.75) {
                        this.handleLoadMore();
                    }
                });
            },

            observeLoadMore() {
                if (! this.hasMoreMessages) return;

                this.$options.loadMoreObserver = new IntersectionObserver(this.handleObserverLoadMore.bind(this), {
                    root: this.$refs.messagesSection,
                    rootMargin: '0px',
                    threshold: 1.0,
                });

                this.$options.loadMoreObserver.observe(this.$refs.loadMoreSection);
            },

            stopObservingForMore() {
                if (this.$options.loadMoreObserver) {
                    this.$options.loadMoreObserver.disconnect();
                    this.$options.loadMoreObserver = null;
                }
            },
        },

        loadMoreObserver: null,

        mounted() {
            this.scrollBottom().then(() => {
                this.observeLoadMore();
            });
            this.focusOnMessage();
        },

        beforeDestroy() {
            this.stopObservingForMore();
        }
    }
</script>
