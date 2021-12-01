<template>
	<div
		v-if="isVisible"
		@keyup.esc="exitSpotlight"
		tabindex="-1"
		class="md:absolute fixed w-full h-full dark:bg-dark-foreground md:bg-transparent bg-white md:z-auto z-50"
	>
		<div class="relative w-full md:max-w-xl z-50 md:rounded-xl mx-auto 2xl:mt-20 md:mt-8 overflow-y-auto bg-white dark:bg-dark-foreground">

			<!--Query bar-->
			<div class="z-50 flex items-center px-5 py-4 mx-auto">
				<div class="relative mr-4">
					<div v-if="isLoading" class="spinner-icon transform scale-50 origin-center translate-y-2.5">
						<Spinner />
					</div>
					<search-icon :class="{'opacity-0': isLoading}" size="22" class="magnify dark-text-theme text-theme vue-feather" />
				</div>

				<!--Filter-->
				<div v-if="activeFilter" @click="removeFilter" class="bg-light-background rounded-lg px-2 py-1 mr-3 flex items-center cursor-pointer">
					<b class="font-bold pr-1.5 text-sm">
						{{ activeFilter }}
					</b>
					<x-icon size="12" />
				</div>

				<!--Text search field-->
				<input
					class="w-full border-none text-xl font-semibold placeholder-gray-700 dark:placeholder-gray-400 bg-transparent focus:outline-none"
					v-model="query"
					@keydown.delete="undoFilter"
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
				<div v-if="$isMobile()" @click="exitSpotlight" class="cursor-pointer">
					<x-icon size="22" class="close" />
				</div>
			</div>

			<!--Results-->
			<div v-if="isNotEmptyQuery" class="spotlight-results relative z-50 px-4 pb-4">

				<!--Show results-->
				<div v-if="results.length !== 0" v-for="(result, i) in results" :key="result.data.id" class="relative">

					<!--Users result-->
					<div
						v-if="activeFilter === 'users'"
						:class="{'dark:bg-2x-dark-foreground bg-light-background rounded-xl': i === index}"
						class="flex items-center px-2.5 py-3.5"
					>
						<MemberAvatar
							:is-border="false"
							:size="44"
							:member="result"
						/>
						<div class="ml-3">
							<b class="text-sm font-bold block max-w-1 overflow-hidden overflow-ellipsis whitespace-nowrap" style="max-width: 155px;">
								{{ result.data.attributes.name }}
							</b>
							<span class="block text-xs dark:text-gray-500 text-gray-600">
								{{ result.data.attributes.email }}
							</span>
						</div>
					</div>

					<!--Item result-->
					<ItemList
						v-if="! activeFilter"
						:entry="result"
						:class="{'dark:bg-2x-dark-foreground bg-light-background rounded-xl': i === index}"
						:highlight="false"
						:mobile-handler="false"
						@click.native="openItem(result)"
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
import MemberAvatar from "../FilesView/MemberAvatar"
import {SearchIcon, XIcon} from 'vue-feather-icons'
import ItemList from "../FilesView/ItemList"
import {events} from '/resources/js/bus'
import {mapGetters} from 'vuex'
import {debounce} from 'lodash'
import axios from "axios"

export default {
	name: 'Spotlight',
	components: {
		MemberAvatar,
		SearchIcon,
		ItemList,
		Spinner,
		XIcon,
	},
	computed: {
		...mapGetters([
			'user'
		]),
		isAdmin() {
			return this.user.data.attributes.role === 'admin'
		},
		metaKeyIcon() {
			return this.$isApple() ? '⌘' : 'alt'
		},
		isNotEmptyQuery() {
			return this.query !== ''
		}
	},
	watch: {
		query(query) {
			if (query === '' || typeof query === 'undefined')
				this.results = []

			// Reset selection index
			this.index = 0

			// Go for filter keyword
			let getFilterQuery = query.substr(0, 2)

			// search for the users
			if (getFilterQuery === 'u ' && this.isAdmin && ! this.activeFilter) {
				this.setFilter('users')
			}

			this.findResult(query)
		}
	},
	data() {
		return {
			activeFilter: undefined,
			backspaceHits: 0,
			isVisible: false,
			isLoading: false,
			results: [],
			query: '',
			index: 0,
		}
	},
	methods: {
		undoFilter() {
			if (this.activeFilter && this.query === '' && this.backspaceHits !== 2) {
				this.backspaceHits++
			}

			if (this.backspaceHits === 2) {
				this.removeFilter()
			}
		},
		setFilter(filter) {
			// Set active filter
			this.activeFilter = filter

			// Set default values
			this.results = []
			this.query = ''
		},
		removeFilter() {
			// Set default values
			this.activeFilter = undefined
			this.backspaceHits = 0
		},
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
			let selectedItem = this.results[this.index]

			if (this.activeFilter === 'users') {
				this.openUser(selectedItem)
			}

			if (! this.activeFilter) {
				this.openItem(selectedItem)
			}
		},
		openUser(user) {
			this.$router.push({name: 'UserDetail', params: {id: user.data.id}})

			this.exitSpotlight()
		},
		openItem(file) {

			// Show folder
			if (file.data.type === 'folder') {

				if (file.data.attributes.isTeamFolder) {

					if (file.data.relationships.owner.data.id === this.user.data.id) {
						this.$router.push({name: 'TeamFolders', params: {id: file.data.id}})
					} else {
						this.$router.push({name: 'SharedWithMe', params: {id: file.data.id}})
					}
				} else {
					this.$router.push({name: 'Files', params: {id: file.data.id}})
				}
			} else {

				// Show file
				if (['video', 'audio', 'image'].includes(file.data.type) || file.data.attributes.mimetype === 'pdf') {
					this.$store.commit('ADD_TO_FAST_PREVIEW', file)

					events.$emit('file-preview:show')
				} else {
					this.$downloadFile(file.data.attributes.file_url, file.data.attributes.name + '.' + file.data.attributes.mimetype)
				}
			}

			this.exitSpotlight()
		},
		onPageDown() {
			if (this.index < (this.results.length - 1))
				this.index++
		},
		onPageUp() {
			if (this.index > 0) this.index--
		},
		findResult: debounce(function (value) {
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
				.get(`${route}?filter=${this.activeFilter}`, {
					params: {query: value}
				})
				.then(response => {

					// Show user result
					if (this.activeFilter === 'users') {
						this.results = response.data.data
					}

					// Show file result
					if (! this.activeFilter) {
						let files = response.data.files.data
						let folders = response.data.folders.data

						this.results = folders.concat(files)
					}
				})
				.catch(() => this.$isSomethingWrong())
				.finally(() => this.isLoading = false)
		}, 150),
		exitSpotlight() {
			this.results = []
			this.query = ''
			this.isVisible = false
			events.$emit('popup:close')
		}
	},
	created() {
		events.$on('spotlight:show', filter => {
			this.isVisible = true
			this.activeFilter = filter

			this.$nextTick(() => this.$refs.searchInput.focus())
		})

		events.$on('spotlight:hide', () => this.exitSpotlight())
	}
}
</script>
