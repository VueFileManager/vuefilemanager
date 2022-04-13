<template>
    <div>
        <!-- Search field -->
        <div class="relative mb-3 flex items-center">
            <!-- Selected emoji preview -->
            <div v-if="defaultEmoji" class="mr-3 select-none">
                <Emoji :emoji="defaultEmoji" class="text-5xl" />
            </div>

            <!-- Search input -->
            <input
                @click="openList"
                v-model="query"
                class="focus-border-theme input-dark"
                type="text"
                :placeholder="$t('select_or_search_emoji')"
            />
        </div>

        <!-- Spinner -->
        <div v-if="isOpen && !isLoaded" class="relative h-20 select-none">
            <Spinner />
        </div>

        <!-- Emojis List -->
        <div
            v-if="isOpen && isLoaded && emojis"
            @scroll="checkGroupInView"
            id="group-box"
            class="relative h-96 select-none overflow-y-auto lg:h-60 2xl:h-96"
        >
            <!-- Navigation of Emojis Groups -->
            <ul
                v-if="!query"
                class="sticky top-0 z-10 flex items-center justify-between space-x-1 bg-white dark:bg-dark-background sm:dark:bg-4x-dark-foreground"
                id="group-bar"
            >
                <li
                    @click.stop="scrollToGroup(group.name)"
                    v-for="(group, i) in emojis.groups"
                    :key="i"
                    class="flex h-14 w-14 cursor-pointer items-center justify-center rounded-xl hover:bg-light-background dark:hover:bg-2x-dark-foreground"
                    :class="{
                        'bg-light-background dark:bg-2x-dark-foreground': group.name === groupInView,
                    }"
                >
                    <Emoji :emoji="group.emoji" class="text-3xl" />
                </li>
            </ul>

            <!-- All Emojis -->
            <div v-if="!query" v-for="(group, name) in allEmoji" :key="name" :id="`group-${name}`">
                <label class="mt-4 mb-2 block text-sm font-bold">
                    {{ name }}
                </label>
                <ul class="space-between grid grid-cols-7 gap-4 md:grid-cols-9">
                    <li
                        @click="setEmoji(emoji)"
                        v-for="(emoji, i) in group"
                        :key="i"
                        class="flex cursor-pointer items-center justify-center"
                    >
                        <Emoji :emoji="emoji" class="text-4xl" />
                    </li>
                </ul>
            </div>

            <!-- Searched emojis -->
            <ul v-if="query" class="space-between grid grid-cols-7 gap-4 md:grid-cols-9">
                <li
                    @click="setEmoji(emoji)"
                    v-for="(emoji, i) in filteredEmojis"
                    :key="i"
                    class="flex cursor-pointer items-center justify-center"
                >
                    <Emoji :emoji="emoji" class="text-4xl" />
                </li>
            </ul>

            <!-- Not found -->
            <span class="ml-2 text-sm font-bold" v-if="filteredEmojis.length === 0 && query !== undefined">
                {{ $t('there_is_nothing_smile') }}
            </span>
        </div>
    </div>
</template>

<script>
import Spinner from '../UI/Others/Spinner'
import Emoji from './Emoji'
import { debounce, groupBy } from 'lodash'
import { XIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'EmojiPicker',
    props: ['defaultEmoji'],
    components: {
        Spinner,
        Emoji,
        XIcon,
    },
    computed: {
        ...mapGetters(['emojis']),
        allEmoji() {
            return groupBy(this.emojis.list, 'group')
        },
    },
    data() {
        return {
            query: undefined,
            filteredEmojis: [],
            isOpen: false,
            isLoaded: false,
            groupInView: 'Smileys & Emotion',
        }
    },
    watch: {
        query: debounce(function (val) {
            // Clear search results
            this.filteredEmojis = []

            // Reset query
            if (val === '' || val === undefined) return

            // Filter emojis by query
            this.filteredEmojis = this.emojis.list.filter((emoji) => emoji.name.includes(val.toLowerCase()))

            if (this.filteredEmojis.length === 0) {
                //
            }
        }, 200),
    },
    methods: {
        checkGroupInView: _.debounce(function () {
            this.emojis.groups.forEach((group) => {
                let element = document.getElementById(`group-${group.name}`).getBoundingClientRect()
                let groupBox = document.getElementById('group-box').getBoundingClientRect()

                // Check if the group is in the viewport of group-box
                if (element.top < groupBox.top && element.bottom > groupBox.top) {
                    this.groupInView = group.name
                }
            })
        }, 300),
        scrollToGroup(name) {
            let groupBar = document.getElementById('group-bar')
            let groupBox = document.getElementById('group-box')
            let group = document.getElementById(`group-${name}`)

            groupBox.scrollTo({
                top: group.offsetTop - groupBar.clientHeight - 5,
                left: 0,
                behavior: 'smooth',
            })

            this.groupInView = name
        },
        openList() {
            // Open list if it's not opened
            if (!this.isOpen) this.isOpen = true

            // Load emojis from server just if not loaded already
            if (this.isOpen && !this.emojis) {
                axios
                    .get('/assets/emojis.json')
                    .then((response) => {
                        this.$store.commit('LOAD_EMOJIS_LIST', response.data)
                    })
                    .finally(() => (this.isLoaded = true))
            }

            // Simulate loading for the list processing
            if (this.emojis) {
                setTimeout(() => {
                    this.isLoaded = true
                }, 20)
            }

            this.groupInView = 'Smileys & Emotion'
        },
        setEmoji(value) {
            this.query = undefined
            this.isOpen = false

            this.$emit('input', value)
        },
    },
    mounted() {
        // Open list of there isn't set any emoji
        if (!this.defaultEmoji) this.openList()
    },
}
</script>
