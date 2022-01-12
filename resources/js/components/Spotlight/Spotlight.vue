<template>
	<div
		v-if="isVisible"
		@keyup.esc="exitSpotlight"
		tabindex="-1"
		class="md:absolute fixed w-full h-full dark:bg-dark-foreground md:bg-transparent bg-white md:z-auto z-50"
	>
		<div class="relative w-full md:max-w-xl z-50 md:rounded-xl mx-auto 2xl:mt-20 md:mt-8 overflow-y-auto bg-white dark:bg-2x-dark-foreground">

			<!--Query bar-->
			<div class="z-50 flex items-center px-5 py-4 mx-auto">
				<div class="relative mr-4">
					<div v-if="isLoading" class="spinner-icon transform scale-50 origin-center translate-y-2.5">
						<Spinner />
					</div>
					<search-icon :class="{'opacity-0': isLoading}" size="22" class="magnify dark-text-theme text-theme vue-feather" />
				</div>

				<!--Filter-->
				<div v-if="activeFilter" @click="removeFilter" class="dark:bg-4x-dark-foreground bg-light-background rounded-lg px-2 py-1 mr-3 flex items-center cursor-pointer">
					<b class="font-bold pr-1.5 text-sm dark-text-theme">
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
					@keydown.meta="showByShortcut"
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

			<!--Show tips-->
			<div v-if="isEmptyQuery && ! activeFilter" class="relative z-50 px-4 pb-4">

				<CategoryName>
					{{ $t('Suggested Filters') }}
				</CategoryName>

				<FilterSuggestion
					v-for="filter in filters"
					:key="filter.slug"
					:keyword="filter.keyword"
					:description="filter.description"
					@click.native="setFilter(filter.slug)"
				/>
			</div>

			<!--Results-->
			<div v-if="isNotEmptyQuery" class="relative z-50 px-4 pb-4">

				<!--Show actions-->
				<CategoryName v-if="actions.length !== 0">
					{{ $t('Actions') }}
				</CategoryName>

				<div v-if="actions.length !== 0" class="mb-2">
					<div v-for="(result, i) in actions" :key="result.action.value" class="relative">
						<div
							class="flex items-center px-3.5 py-2.5"
							:class="{'dark:bg-4x-dark-foreground bg-light-background rounded-xl': i === index}"
						>
							<settings-icon v-if="['AppOthers', 'Profile', 'Password'].includes(result.action.value)" size="18" class="vue-feather text-theme"/>
							<credit-card-icon v-if="result.action.value === 'AppPayments'" size="18" class="vue-feather text-theme"/>
							<home-icon v-if="result.action.value === 'Files'" size="18" class="vue-feather text-theme"/>
							<trash2-icon v-if="result.action.value === 'Trash'" size="18" class="vue-feather text-theme"/>
							<database-icon v-if="['CreateFixedPlan', 'CreateMeteredPlan'].includes(result.action.value)" size="18" class="vue-feather text-theme"/>
							<user-plus-icon v-if="result.action.value === 'UserCreate'" size="18" class="vue-feather text-theme"/>
							<users-icon v-if="['TeamFolders', 'Users'].includes(result.action.value)" size="18" class="vue-feather text-theme"/>
							<user-check-icon v-if="result.action.value === 'SharedWithMe'" size="18" class="vue-feather text-theme"/>
							<link-icon v-if="result.action.value === 'MySharedItems'" size="18" class="vue-feather text-theme"/>
							<upload-cloud-icon v-if="result.action.value === 'RecentUploads'" size="18" class="vue-feather text-theme"/>
							<file-text-icon v-if="['Invoices', 'Invoice'].includes(result.action.value)" size="18" class="vue-feather text-theme"/>
							<database-icon v-if="result.action.value === 'Plans'" size="18" class="vue-feather text-theme"/>
							<dollar-sign-icon v-if="['Subscriptions', 'Billing'].includes(result.action.value)" size="18" class="vue-feather text-theme"/>
							<globe-icon v-if="result.action.value === 'Language'" size="18" class="vue-feather text-theme"/>
							<monitor-icon v-if="result.action.value === 'Pages'" size="18" class="vue-feather text-theme"/>
							<box-icon v-if="result.action.value === 'Dashboard'" size="18" class="vue-feather text-theme"/>
							<hard-drive-icon v-if="result.action.value === 'Storage'" size="18" class="vue-feather text-theme"/>
							<moon-icon v-if="result.action.value === 'dark-mode'" size="18" class="vue-feather text-theme"/>
							<maximize2-icon v-if="result.action.value === 'full-screen-mode'" size="18" class="vue-feather text-theme"/>
							<power-icon v-if="result.action.value === 'log-out'" size="18" class="vue-feather text-theme"/>
							<trash-icon v-if="result.action.value === 'empty-trash'" size="18" class="vue-feather text-theme"/>
							<grid-icon v-if="result.action.value === 'toggle-grid-list'" size="18" class="vue-feather text-theme"/>

							<b class="font-bold text-sm ml-3.5">
								{{ result.title }}
							</b>
						</div>

						<!--Keyboard shortcut hint-->
						<div v-if="! $isMobile()" class="absolute right-4 top-1/2 transform -translate-y-1/2">
							<span class="text-xs text-gray-400">{{ i === 0 ? '↵' : metaKeyIcon + i }}</span>
						</div>
					</div>
				</div>

				<!--Show results-->
				<CategoryName v-if="! activeFilter && results.length !== 0">
					{{ $t('Files & Folders') }}
				</CategoryName>

				<div v-if="results.length !== 0" v-for="(result, i) in results" :key="result.data.id" class="relative">

					<!--Users result-->
					<div
						v-if="activeFilter === 'users' && !result.action"
						:class="{'dark:bg-4x-dark-foreground bg-light-background rounded-xl': (i + actions.length) === index}"
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
						v-if="! activeFilter && !result.action"
						:entry="result"
						:class="{'dark:bg-4x-dark-foreground bg-light-background rounded-xl': (i + actions.length) === index}"
						:highlight="false"
						:mobile-handler="false"
						@click.native="openItem(result)"
					/>

					<!--Keyboard shortcut hint-->
					<div v-if="! $isMobile()" class="absolute right-4 top-1/2 transform -translate-y-1/2">
						<span class="text-xs text-gray-400">{{ (i + actions.length) === 0 ? '↵' : metaKeyIcon + (i + actions.length) }}</span>
					</div>
				</div>

				<!--Show Empty message-->
				<span v-if="results.length === 0 && actions.length === 0" class="p-2.5 text-sm dark:text-gray-400 text-gray-700">
					{{ $t('messages.nothing_was_found') }}
				</span>
			</div>

			<!--Hints-->
			<KeyboardHints />
		</div>
	</div>
</template>

<script>
import {
	BoxIcon,
	CreditCardIcon,
	DatabaseIcon,
	DollarSignIcon,
	FileTextIcon,
	GlobeIcon,
	GridIcon,
	HardDriveIcon,
	HomeIcon,
	LinkIcon,
	Maximize2Icon,
	MonitorIcon,
	MoonIcon,
	PowerIcon,
	SearchIcon,
	SettingsIcon,
	Trash2Icon,
	TrashIcon,
	UploadCloudIcon,
	UserCheckIcon,
	UserPlusIcon,
	UsersIcon,
	XIcon
} from 'vue-feather-icons'
import Spinner from '/resources/js/components/FilesView/Spinner'
import MemberAvatar from "../FilesView/MemberAvatar"
import FilterSuggestion from "./FilterSuggestion"
import ItemList from "../FilesView/ItemList"
import CategoryName from "./CategoryName"
import {events} from '/resources/js/bus'
import {mapGetters} from 'vuex'
import {debounce} from 'lodash'
import axios from "axios"
import KeyboardHints from "./KeyboardHints";

export default {
	name: 'Spotlight',
	components: {
		KeyboardHints,
		CreditCardIcon,
		GridIcon,
		CategoryName,
		FilterSuggestion,
		Maximize2Icon,
		TrashIcon,
		MoonIcon,
		PowerIcon,
		HardDriveIcon,
		UploadCloudIcon,
		FileTextIcon,
		DollarSignIcon,
		GlobeIcon,
		MonitorIcon,
		BoxIcon,
		UsersIcon,
		UserCheckIcon,
		LinkIcon,
		SettingsIcon,
		HomeIcon,
		DatabaseIcon,
		UserPlusIcon,
		MemberAvatar,
		Trash2Icon,
		SearchIcon,
		ItemList,
		Spinner,
		XIcon,
	},
	computed: {
		...mapGetters([
			'isDarkMode',
			'config',
			'user',
		]),
		actionList() {
			let adminLocations = [
				{
					title: this.$t('Go To Dashboard'),
					action: {
						type: 'route',
						value: 'Dashboard',
					},
				},
				{
					title: this.$t('Go To Settings'),
					action: {
						type: 'route',
						value: 'AppOthers',
					},
				},
				{
					title: this.$t('Go To Payments'),
					action: {
						type: 'route',
						value: 'AppPayments',
					},
				},
				{
					title: this.$t('Go To Pages'),
					action: {
						type: 'route',
						value: 'Pages',
					},
				},
				{
					title: this.$t('Go To Languages'),
					action: {
						type: 'route',
						value: 'Language',
					},
				},
				{
					title: this.$t('Show all Users'),
					action: {
						type: 'route',
						value: 'Users',
					},
				},
				{
					title: this.$t('Show all Plans'),
					action: {
						type: 'route',
						value: 'Plans',
					},
				},
				{
					title: this.$t('Show Transactions'),
					action: {
						type: 'route',
						value: 'Invoices',
					},
				},
			]

			let fileLocations = [
				{
					title: this.$t('Go Home'),
					action: {
						type: 'route',
						value: 'Files',
					},
				},
				{
					title: this.$t('Go To Recent Uploads'),
					action: {
						type: 'route',
						value: 'RecentUploads',
					},
				},
				{
					title: this.$t('Go To Publicly Shared'),
					action: {
						type: 'route',
						value: 'MySharedItems',
					},
				},
				{
					title: this.$t('Go To Trash'),
					action: {
						type: 'route',
						value: 'Trash',
					},
				},
				{
					title: this.$t('Go To Team Folders'),
					action: {
						type: 'route',
						value: 'TeamFolders',
					},
				},
				{
					title: this.$t('Go To Shared with Me'),
					action: {
						type: 'route',
						value: 'SharedWithMe',
					},
				},
			]

			let adminActions = [
				{
					title: this.$t('Create User'),
					action: {
						type: 'route',
						value: 'UserCreate',
					},
				},
			]

			let userSettings = [
				{
					title: this.$t('Update Profile Settings'),
					action: {
						type: 'route',
						value: 'Profile',
					},
				},
				{
					title: this.$t('Update Security & API'),
					action: {
						type: 'route',
						value: 'Password',
					},
				},
				{
					title: this.$t('Show Storage Details'),
					action: {
						type: 'route',
						value: 'Storage',
					},
				},
				{
					title: this.$t('Show Billing'),
					action: {
						type: 'route',
						value: 'Billing',
					},
				},
			]

			let functionList = [
				{
					title: this.$t('Toggle Grid/List View'),
					action: {
						type: 'function',
						value: 'toggle-grid-list',
					},
				},
				{
					title: this.$t('Toggle Dark/Light Mode'),
					action: {
						type: 'function',
						value: 'dark-mode',
					},
				},
				{
					title: this.$t('Toggle Full-Screen Mode'),
					action: {
						type: 'function',
						value: 'full-screen-mode',
					},
				},
				{
					title: this.$t('Empty Your Trash'),
					action: {
						type: 'function',
						value: 'empty-trash',
					},
				},
				{
					title: this.$t('Log Out'),
					action: {
						type: 'function',
						value: 'log-out',
					},
				},
			]

			if (this.user.data.attributes.role === 'admin') {

				// Available only for fixed subscription
				if (this.config.subscriptionType === 'fixed') {
					adminLocations.push({
						title: this.$t('Show all Subscriptions'),
						action: {
							type: 'route',
							value: 'Subscriptions',
						},
					})
				}

				// Available only when is metered billing and plan doesnt exist or when is fixed billing
				if ((this.config.subscriptionType === 'metered' && ! this.config.isCreatedMeteredPlan) || this.config.subscriptionType === 'fixed') {
					adminActions.push({
						title: this.$t('Create Plan'),
						action: {
							type: 'route',
							value: this.config.subscriptionType === 'fixed'
								? 'CreateFixedPlan'
								: 'CreateMeteredPlan',
						},
					})
				}

				return [].concat.apply([], [functionList, userSettings, fileLocations, adminActions, adminLocations])
			}

			if (this.user.data.attributes.role === 'user') {
				return [].concat.apply([], [functionList, userSettings, fileLocations])
			}
		},
		isAdmin() {
			return this.user.data.attributes.role === 'admin'
		},
		metaKeyIcon() {
			return this.$isApple() ? '⌘' : 'alt'
		},
		isNotEmptyQuery() {
			return this.query !== ''
		},
		isEmptyQuery() {
			return this.query === '' || this.query === undefined
		}
	},
	data() {
		return {
			activeFilter: undefined,
			backspaceHits: 0,
			isVisible: false,
			isLoading: false,
			query: '',
			index: 0,

			results: [],
			actions: [],

			filters: [
				{
					keyword: 'u',
					description: this.$t('Search through your users...'),
					slug: 'users',
				},
			]
		}
	},
	watch: {
		query(query) {
			if (query === '' || typeof query === 'undefined')
				this.results = []
				this.actions = []

			// Reset selection index
			this.index = 0

			// Go for filter keyword
			let getFilterQuery = query.substr(0, 2)

			// search for the users
			if (getFilterQuery === 'u ' && this.isAdmin && ! this.activeFilter) {
				this.setFilter('users')
			}

			// Browse actions
			if (! this.activeFilter) {
				this.actions = this.actionList.filter(el => el.title.toLowerCase().indexOf(query) > -1).slice(0, 3)
			}

			this.findResult(query)
		},
	},
	methods: {
		showByShortcut(e) {
			// Preserve select and reload native shortcut
			if (!['a', 'r', 'v'].includes(e.key)) {
				e.preventDefault()
			}

			const allowedRange = this.results.length + this.actions.length

			// Allow only numbers within result range
			if (Number.isInteger(parseInt(e.key)) && parseInt(e.key) < allowedRange) {
				this.index = parseInt(e.key)

				this.showSelected()
			}
		},
		showSelected() {
			let index = this.index
			let resultIndex = index - this.actions.length

			// Open Action
			if (this.actions.length > 0 && index < this.actions.length ) {
				this.openAction(this.actions[index])
				return
			}

			// Open user
			if (this.activeFilter === 'users') {
				this.openUser(this.results[resultIndex])
			}

			// Open file or folder
			if (! this.activeFilter) {
				this.openItem(this.results[resultIndex])
			}
		},
		openAction(arg) {
			if (arg.action.type === 'route') {
				this.$router.push({name: arg.action.value})
			}

			if (arg.action.type === 'function') {

				if (arg.action.value === 'toggle-grid-list') {
					this.$store.dispatch('togglePreviewType')
				}

				if (arg.action.value === 'dark-mode') {
					this.$store.dispatch('toggleDarkMode', !this.isDarkMode)
				}

				if (arg.action.value === 'full-screen-mode') {
					this.$store.dispatch('toggleNavigationBars')
				}

				if (arg.action.value === 'log-out') {
					this.$store.dispatch('logOut')
				}

				if (arg.action.value === 'empty-trash') {
					this.$emptyTrashQuietly()
				}
			}

			this.exitSpotlight()
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
			this.actions = []
			this.results = []
			this.query = ''
			this.isVisible = false
			events.$emit('popup:close')
		},
		onPageDown() {
			let results = this.results.length
			let actions = this.actions.length

			let totalResultLength = (results + actions) - 1

			if (this.index < totalResultLength)
				this.index++
		},
		onPageUp() {
			if (this.index > 0) this.index--
		},
		setFilter(filter) {
			// Set active filter
			this.activeFilter = filter

			// Set default values
			this.results = []
			this.query = ''

			this.$nextTick(() => this.$refs.searchInput.focus())
		},
		undoFilter() {
			if (this.activeFilter && this.query === '' && this.backspaceHits !== 2) {
				this.backspaceHits++
			}

			if (this.backspaceHits === 2) {
				this.removeFilter()
			}
		},
		removeFilter() {
			// Set default values
			this.activeFilter = undefined
			this.backspaceHits = 0
		},
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
