<template>
    <div>
		<!-- Search field -->
		<div class="mb-3 relative flex items-center">

			<!-- Selected emoji preview -->
			<div v-if="defaultEmoji" class="select-none mr-3">
				<Emoji :emoji="defaultEmoji" class="text-5xl" />
			</div>

			<!-- Search input -->
			<input @click="openList" v-model="query" class="focus-border-theme input-dark" type="text" :placeholder="$t('Select or search emoji icon...')">
		</div>

		<!-- Spinner -->
		<div v-if="isOpen && !isLoaded" class="relative h-20 select-none">
			<Spinner />
		</div>

        <!-- Emojis List -->
		<div v-if="isOpen && isLoaded && emojis" @scroll="checkGroupInView" id="group-box" class="2xl:h-96 lg:h-60 h-96 overflow-y-auto select-none relative">

			<!-- Navigation of Emojis Groups -->
			<ul v-if="! query" class="flex items-center justify-between space-x-1 sticky top-0 sm:dark:bg-4x-dark-foreground dark:bg-dark-background bg-white z-10" id="group-bar">
				<li @click.stop="scrollToGroup(group.name)" v-for="(group,i) in emojis.groups" :key="i" class="w-14 h-14 flex items-center justify-center rounded-xl cursor-pointer dark:hover:bg-2x-dark-foreground hover:bg-light-background" :class="{'dark:bg-2x-dark-foreground bg-light-background': group.name === groupInView}">
					<Emoji :emoji="group.emoji" class="text-3xl" />
				</li>
			</ul>

			<!-- All Emojis -->
			<div v-if="! query" v-for="(group, name) in allEmoji" :key="name" :id="`group-${name}`">
				<label class="font-bold text-sm mt-4 mb-2 block">
					{{ name }}
				</label>
				<ul class="grid md:grid-cols-9 grid-cols-7 gap-4 space-between">
					<li @click="setEmoji( emoji )" v-for="(emoji,i) in group" :key="i" class="flex items-center justify-center cursor-pointer">
						<Emoji :emoji="emoji" class="text-4xl" />
					</li>
				</ul>
			</div>

			<!-- Searched emojis -->
			<ul v-if="query" class="grid md:grid-cols-9 grid-cols-7 gap-4 space-between">
				<li @click="setEmoji( emoji )" v-for="(emoji,i) in filteredEmojis" :key="i" class="flex items-center justify-center cursor-pointer">
					<Emoji :emoji="emoji" class="text-4xl" />
				</li>
			</ul>

			<!-- Not found -->
			<span class="font-bold text-sm ml-2" v-if="filteredEmojis.length === 0 && query !== undefined">
				{{ $t('There is nothing :(') }}
			</span>
		</div>
    </div>
</template>

<script>
import Spinner from '/resources/js/components/FilesView/Spinner'
import Emoji from '/resources/js/components/Others/Emoji'
import {debounce, groupBy} from 'lodash'
import {XIcon} from 'vue-feather-icons'
import {mapGetters} from 'vuex'

export default {
    name: 'EmojiPicker',
    props: [
		'defaultEmoji',
	],
    components: {
        Spinner,
        Emoji,
        XIcon,
    },
    computed: {
        ...mapGetters([
			'emojis',
		]),
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
			this.filteredEmojis = this.emojis.list.filter(emoji => emoji.name.includes(val.toLowerCase()))

			if (this.filteredEmojis.length === 0) {
				console.log('empty');
			}
		}, 200),
	},
    methods: {
        checkGroupInView: _.debounce(function () {
            this.emojis.groups.forEach(group => {
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
				behavior: 'smooth'
			});

            this.groupInView = name
        },
        openList() {
			// Open list if it's not opened
			if (! this.isOpen) this.isOpen = true

            // Load emojis from server just if not loaded already
            if (this.isOpen && !this.emojis) {
                axios.get('/assets/emojis.json')
                    .then(response => {
                        this.$store.commit('LOAD_EMOJIS_LIST', response.data)
                    })
                .finally(() => this.isLoaded = true)
            }

            // Simulate loading for the list processing
            if (this.emojis) {
                setTimeout(() => {
                    this.isLoaded = true
                }, 20);
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
		if (! this.defaultEmoji) this.openList()
    }
}
</script>