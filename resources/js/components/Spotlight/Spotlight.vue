<template>
	<div
		v-if="isVisible"
		@keyup.esc="exit"
		tabindex="-1"
		class="md:absolute fixed w-full h-full dark:bg-dark-foreground bg-white md:z-auto z-50"
	>
		<div class="relative w-full md:max-w-xl z-50 md:rounded-xl mx-auto 2xl:mt-20 md:mt-8 overflow-y-auto bg-white dark:bg-dark-foreground">

			<!--Query bar-->
			<div class="z-50 flex items-center p-5 mx-auto">
				<div class="relative mr-4">
					<div v-if="isLoading" class="spinner-icon transform scale-50 origin-center translate-y-2.5">
						<Spinner />
					</div>
					<search-icon :class="{'opacity-0': isLoading}" size="22" class="magnify dark-text-theme text-theme vue-feather" />
				</div>

				<!--Text search field-->
				<input
					class="w-full border-none text-xl font-semibold placeholder-gray-700 dark:placeholder-gray-400 bg-transparent focus:outline-none"
					v-model="query"
					@keydown.enter="showSelected"
					@keydown.meta="proceedToSelect"
					@keyup.down="onPageDown"
					@keyup.up="onPageUp"
					type="text"
					placeholder="Spotlight search..."
					ref="searchInput"
				>

				<!--Desktop searchbar hint-->
				<div v-if="! $isMobile()" class="mr-2">
					<span class="text-sm text-gray-400">esc</span>
				</div>

				<!--Mobile close icon-->
				<div v-if="$isMobile()" @click="exit" class="cursor-pointer">
					<x-icon size="22" class="close" />
				</div>
			</div>

			<!--Results-->
			<div v-if="isNotEmptyQuery" class="spotlight-results relative z-50 px-4 pb-4">

				<!--Show results-->
				<div v-if="results.length !== 0" v-for="(item, i) in results" :key="item.data.id" class="relative">

					<!--Item result-->
					<ItemList
						:entry="item"
						:class="{'dark:bg-2x-dark-foreground bg-light-background rounded-xl': i === index}"
						:highlight="false"
						:mobile-handler="false"
						@click.native="openItem(item)"
					/>

					<!--Keyboard shortcut hint-->
					<div v-if="! $isMobile()" class="absolute right-4 top-1/2 transform -translate-y-1/2">
						<span class="text-xs text-gray-400">{{ i === 0 ? '↵' : metaKeyIcon + i }}</span>
					</div>
				</div>

				<!--Show Empty message-->
				<span v-if="results.length === 0" class="fond-extrabold p-2.5 text-base">
					{{ $t('messages.nothing_was_found') }}
				</span>
			</div>
		</div>
	</div>
</template>

<script>
import Spinner from '/resources/js/components/FilesView/Spinner'
import {SearchIcon, XIcon} from 'vue-feather-icons'
import ItemList from "../FilesView/ItemList"
import {events} from '/resources/js/bus'
import {debounce} from 'lodash';
import axios from "axios";

export default {
	name: 'Spotlight',
	components: {
		SearchIcon,
		ItemList,
		Spinner,
		XIcon,
	},
	computed: {
		metaKeyIcon() {
			return this.$isApple() ? '⌘' : 'alt'
		},
		isNotEmptyQuery() {
			return this.query !== ''
		}
	},
	watch: {
		query(val) {
			if (val === '' || typeof val === 'undefined')
				this.results = []

			this.index = 0
			this.searchFiles(val)
		}
	},
	data() {
		return {
			isVisible: false,
			isLoading: false,
			results: [],
			query: '',
			index: 0,
		}
	},
	methods: {
		proceedToSelect(e) {
			// Preserve select and reload native shortcut
			if (!['a', 'r', 'v'].includes(e.key)) {
				e.preventDefault()
			}

			// Allow only numbers within result range
			if (Number.isInteger(parseInt(e.key)) && parseInt(e.key) < this.results.length) {
				this.index = parseInt(e.key)

				this.showSelected()
			}
		},
		showSelected() {
			let file = this.results[this.index]

			this.openItem(file)
		},
		openItem(file) {

			// Show folder
			if (file.data.type === 'folder') {
				this.$router.push({name: 'Files', params: {id: file.data.id}})
			} else {

				// Show file
				if (['video', 'audio', 'image'].includes(file.data.type) || file.data.attributes.mimetype === 'pdf') {
					this.$store.commit('ADD_TO_FAST_PREVIEW', file)

					events.$emit('file-preview:show')
				} else {
					this.$downloadFile(file.data.attributes.file_url, file.data.attributes.name + '.' + file.data.attributes.mimetype)
				}
			}

			this.exit()
		},
		onPageDown() {
			if (this.index < (this.results.length - 1))
				this.index++
		},
		onPageUp() {
			if (this.index > 0) this.index--
		},
		searchFiles: debounce(function (value) {
			// Prevent empty searching
			if (value === '' || typeof value === 'undefined') return

			this.isLoading = true

			// Get route
			let route = undefined

			if (this.$store.getters.sharedDetail) {
				let permission = this.$store.getters.sharedDetail.data.attributes.protected
					? 'private'
					: 'public'

				route = `/api/browse/search/${permission}/${this.$router.currentRoute.params.token}`

			} else {
				route = '/api/browse/search'
			}

			axios
				.get(route, {
					params: {query: value}
				})
				.then(response => {
					let files = response.data.files.data
					let folders = response.data.folders.data

					this.results = folders.concat(files)
				})
				.catch(() => this.$isSomethingWrong())
				.finally(() => this.isLoading = false)

		}, 150),
		exit() {
			this.results = []
			this.query = ''
			this.isVisible = false
			events.$emit('popup:close')
		}
	},
	created() {
		events.$on('spotlight:show', () => {
			this.isVisible = true

			this.$nextTick(() => this.$refs.searchInput.focus())
		})

		events.$on('spotlight:hide', () => this.exit())
	}
}
</script>
