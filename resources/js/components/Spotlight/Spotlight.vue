<template>
    <div
		id="spotlight"
        v-if="isVisible"
        @keyup.esc="exitSpotlight"
        @click.exact.self="exitSpotlight"
        tabindex="-1"
        class="fixed left-0 right-0 bottom-0 top-0 z-50 z-50 h-full w-full bg-white dark:bg-dark-background md:absolute md:bg-dark-background md:bg-opacity-[0.35] dark:md:bg-opacity-[0.45]"
    >
        <div
            class="relative z-50 mx-auto w-full overflow-y-auto md:mt-8 md:max-w-xl md:rounded-xl md:bg-white md:shadow-xl dark:md:bg-2x-dark-foreground 2xl:mt-20"
        >
            <!--Query bar-->
            <div class="z-50 mx-auto flex items-center px-5 py-4">
                <div class="relative mr-4">
                    <div v-if="isLoading" class="spinner-icon origin-center translate-y-2.5 scale-50 transform">
                        <Spinner />
                    </div>
                    <search-icon
                        :class="{ 'opacity-0': isLoading }"
                        size="22"
                        class="magnify dark-text-theme text-theme vue-feather"
                    />
                </div>

                <!--Filter-->
                <div
                    v-if="activeFilter"
                    @click="removeFilter"
                    class="mr-3 flex cursor-pointer items-center rounded-lg bg-light-background px-2 py-1 dark:bg-4x-dark-foreground"
                >
                    <b class="dark-text-theme -mt-0.5 pr-1.5 text-sm font-bold">
                        {{ activeFilter }}
                    </b>
                    <x-icon size="12" />
                </div>

                <!--Text search field-->
                <input
                    class="w-full border-none bg-transparent text-lg font-semibold placeholder-gray-700 focus:outline-none dark:placeholder-gray-400 sm:text-xl"
                    v-model="query"
                    @keydown.delete="undoFilter"
                    @keydown.enter="showSelected"
                    @keydown.meta="showByShortcut"
					@keydown.ctrl="showByShortcut"
                    @keyup.down="onPageDown"
                    @keyup.up="onPageUp"
                    type="text"
                    :placeholder="$t('spotlight_search')"
                    ref="searchInput"
                />

                <!--Desktop searchbar hint-->
                <div v-if="!$isMobile()" class="mr-2">
                    <span class="text-sm text-gray-400">esc</span>
                </div>

                <!--Mobile close icon-->
                <div v-if="$isMobile()" @click="exitSpotlight" class="cursor-pointer">
                    <x-icon size="22" class="close" />
                </div>
            </div>

            <!--Show tips-->
            <div
                v-if="isEmptyQuery && !activeFilter && !$isThisRoute($route, ['Public']) && isAdmin"
                class="relative z-50 px-4 pb-4"
            >
                <CategoryName>
                    {{ $t('suggested_filters') }}
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
                    {{ $t('actions') }}
                </CategoryName>

                <div v-if="actions.length !== 0" class="mb-2">
                    <div v-for="(result, i) in actions" :key="result.action.value" class="relative">
                        <div
                            @mousedown="openAction(result)"
                            class="flex cursor-pointer items-center px-3.5 py-2.5"
                            :class="{
                                'rounded-xl bg-light-background dark:bg-4x-dark-foreground': i === index,
                            }"
                        >
                            <settings-icon
                                v-if="['AppOthers', 'Profile', 'Password', 'AppServer', 'AppAppearance', 'AppIndex', 'AppEnvironment', 'AppOthers', 'AppSignInUp', 'AppAdsense'].includes(result.action.value)"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <credit-card-icon
                                v-if="result.action.value === 'AppPayments'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <home-icon
                                v-if="result.action.value === 'Files'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <trash2-icon
                                v-if="result.action.value === 'Trash'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <database-icon
                                v-if="['CreateFixedPlan', 'CreateMeteredPlan'].includes(result.action.value)"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <user-plus-icon
                                v-if="result.action.value === 'UserCreate'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <users-icon
                                v-if="['TeamFolders', 'Users'].includes(result.action.value)"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <user-check-icon
                                v-if="result.action.value === 'SharedWithMe'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <link-icon
                                v-if="result.action.value === 'MySharedItems'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <link2-icon
                                v-if="result.action.value === 'remote-upload'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <upload-cloud-icon
                                v-if="result.action.value === 'RecentUploads'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <file-text-icon
                                v-if="['Invoices', 'Invoice'].includes(result.action.value)"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <database-icon
                                v-if="result.action.value === 'Plans'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <dollar-sign-icon
                                v-if="['Subscriptions', 'Billing'].includes(result.action.value)"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <globe-icon
                                v-if="result.action.value === 'Language'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <monitor-icon
                                v-if="result.action.value === 'Pages'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <box-icon
                                v-if="result.action.value === 'Dashboard'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <hard-drive-icon
                                v-if="result.action.value === 'Storage'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <moon-icon
                                v-if="result.action.value === 'dark-mode'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <maximize2-icon
                                v-if="result.action.value === 'full-screen-mode'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <power-icon
                                v-if="result.action.value === 'log-out'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <trash-icon
                                v-if="result.action.value === 'empty-trash'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <grid-icon
                                v-if="result.action.value === 'toggle-grid-list'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <smile-icon
                                v-if="result.action.value === 'toggle-emoji'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <folder-plus-icon
                                v-if="result.action.value === 'create-team-folder'"
                                size="18"
                                class="vue-feather text-theme"
                            />
                            <upload-cloud-icon
                                v-if="result.action.value === 'create-file-request'"
                                size="18"
                                class="vue-feather text-theme"
                            />

                            <b class="ml-3.5 text-sm font-bold">
                                {{ result.title }}
                            </b>
                        </div>

                        <!--Keyboard shortcut hint-->
                        <div v-if="!$isMobile()" class="absolute right-4 top-1/2 -translate-y-1/2 transform">
                            <span class="text-xs text-gray-400">
								{{ i === 0 ? '↵' : metaKeyIcon + i }}
							</span>
                        </div>
                    </div>
                </div>

                <!--Show results-->
                <CategoryName v-if="!activeFilter && results.length !== 0">
                    {{ $t('files_and_folders') }}
                </CategoryName>

                <div v-if="results.length !== 0" v-for="(result, i) in results" :key="result.data.id" class="relative">
                    <!--Users result-->
                    <div
                        v-if="activeFilter === 'users' && !result.action"
                        :class="{
                            'rounded-xl bg-light-background dark:bg-4x-dark-foreground': i + actions.length === index,
                        }"
                        class="flex items-center px-2.5 py-3.5"
                        @click="openUser(result)"
                    >
                        <MemberAvatar :is-border="false" :size="44" :member="result" />
                        <div class="ml-3">
                            <b
                                class="max-w-1 block overflow-hidden text-ellipsis whitespace-nowrap text-sm font-bold"
                                style="max-width: 155px"
                            >
                                {{ result.data.attributes.name }}
                            </b>
                            <span class="block text-xs text-gray-600 dark:text-gray-500">
                                {{ result.data.attributes.email }}
                            </span>
                        </div>
                    </div>

                    <!--Item result-->
                    <ItemList
                        v-if="!activeFilter && !result.action"
                        :entry="result"
                        :class="{
                            'rounded-xl bg-light-background dark:bg-4x-dark-foreground': i + actions.length === index,
                        }"
                        :highlight="false"
                        :mobile-handler="false"
                        @click.native="openItem(result)"
                    />

                    <!--Keyboard shortcut hint-->
                    <div v-if="!$isMobile()" class="absolute right-4 top-1/2 -translate-y-1/2 transform">
                        <span class="text-xs text-gray-400">{{
                            i + actions.length === 0 ? '↵' : metaKeyIcon + (i + actions.length)
                        }}</span>
                    </div>
                </div>

                <!--Show Empty message-->
                <span
                    v-if="results.length === 0 && actions.length === 0"
                    class="p-2.5 text-sm text-gray-700 dark:text-gray-400"
                >
                    {{ $t('messages.nothing_was_found') }}
                </span>
            </div>

            <!--Hints-->
            <KeyboardHints />
        </div>
    </div>
</template>

<script>
import { events } from '../../bus'
import ItemList from '../UI/Entries/ItemList'
import MemberAvatar from '../UI/Others/MemberAvatar'
import Spinner from '../UI/Others/Spinner'
import CategoryName from './Components/CategoryName'
import FilterSuggestion from './Components/FilterSuggestion'
import KeyboardHints from './Components/KeyboardHints'
import axios from 'axios'
import { debounce } from 'lodash'
import {
	Link2Icon,
    FolderPlusIcon,
    SmileIcon,
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
    XIcon,
} from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'Spotlight',
    components: {
		Link2Icon,
        FolderPlusIcon,
        SmileIcon,
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
        ...mapGetters(['config', 'user', 'sharedDetail']),
        actionList() {
            let adminLocations = [
                {
                    title: this.$t('go_to_dashboard'),
                    action: {
                        type: 'route',
                        value: 'Dashboard',
                    },
                },
                {
                    title: this.$t('go_to_settings'),
                    action: {
                        type: 'route',
                        value: 'AppOthers',
                    },
                },
                {
                    title: this.$t('go_to_payments'),
                    action: {
                        type: 'route',
                        value: 'AppPayments',
                    },
                },
                {
                    title: this.$t('go_to_pages'),
                    action: {
                        type: 'route',
                        value: 'Pages',
                    },
                },
                {
                    title: this.$t('go_to_languages'),
                    action: {
                        type: 'route',
                        value: 'Language',
                    },
                },
                {
                    title: this.$t('show_all_users'),
                    action: {
                        type: 'route',
                        value: 'Users',
                    },
                },
                {
                    title: this.$t('show_all_plans'),
                    action: {
                        type: 'route',
                        value: 'Plans',
                    },
                },
                {
                    title: this.$t('show_transactions'),
                    action: {
                        type: 'route',
                        value: 'Invoices',
                    },
                },
                {
                    title: this.$t('application_settings'),
                    action: {
                        type: 'route',
                        value: 'AppOthers',
                    },
                },
                {
                    title: this.$t('login_registration_settings'),
                    action: {
                        type: 'route',
                        value: 'AppSignInUp',
                    },
                },
                {
                    title: this.$t('appearance_settings'),
                    action: {
                        type: 'route',
                        value: 'AppAppearance',
                    },
                },
                {
                    title: this.$t('adsense_settings'),
                    action: {
                        type: 'route',
                        value: 'AppAdsense',
                    },
                },
                {
                    title: this.$t('homepage_settings'),
                    action: {
                        type: 'route',
                        value: 'AppIndex',
                    },
                },
                {
                    title: this.$t('environment_settings'),
                    action: {
                        type: 'route',
                        value: 'AppEnvironment',
                    },
                },
                {
                    title: this.$t('server_settings'),
                    action: {
                        type: 'route',
                        value: 'AppServer',
                    },
                },
            ]

            let fileLocations = [
                {
                    title: this.$t('go_home_spotlight'),
                    action: {
                        type: 'route',
                        value: 'Files',
                    },
                },
                {
                    title: this.$t('go_to_recent_uploads'),
                    action: {
                        type: 'route',
                        value: 'RecentUploads',
                    },
                },
                {
                    title: this.$t('go_to_publicly_shared'),
                    action: {
                        type: 'route',
                        value: 'MySharedItems',
                    },
                },
                {
                    title: this.$t('go_to_trash'),
                    action: {
                        type: 'route',
                        value: 'Trash',
                    },
                },
                {
                    title: this.$t('go_to_team_folders'),
                    action: {
                        type: 'route',
                        value: 'TeamFolders',
                    },
                },
                {
                    title: this.$t('go_to_shared_with_me'),
                    action: {
                        type: 'route',
                        value: 'SharedWithMe',
                    },
                },
            ]

            let adminActions = [
                {
                    title: this.$t('create_user'),
                    action: {
                        type: 'route',
                        value: 'UserCreate',
                    },
                },
            ]

            let userSettings = [
                {
                    title: this.$t('update_profile_settings'),
                    action: {
                        type: 'route',
                        value: 'Profile',
                    },
                },
                {
                    title: this.$t('update_security_api'),
                    action: {
                        type: 'route',
                        value: 'Password',
                    },
                },
                {
                    title: this.$t('show_storage_details'),
                    action: {
                        type: 'route',
                        value: 'Storage',
                    },
                },
                {
                    title: this.$t('show_billing'),
                    action: {
                        type: 'route',
                        value: 'Billing',
                    },
                },
                {
                    title: this.$t('empty_your_trash'),
                    action: {
                        type: 'function',
                        value: 'empty-trash',
                    },
                },
                {
                    title: this.$t('logout'),
                    action: {
                        type: 'function',
                        value: 'log-out',
                    },
                },
            ]

            let createList = [
                {
                    title: this.$t('create_team_folder'),
                    action: {
                        type: 'function',
                        value: 'create-team-folder',
                    },
                },
                {
                    title: this.$t('create_file_request'),
                    action: {
                        type: 'function',
                        value: 'create-file-request',
                    },
                },
                {
                    title: this.$t('remote_upload'),
                    action: {
                        type: 'function',
                        value: 'remote-upload',
                    },
                },
            ]

            let functionList = [
                {
                    title: this.$t('toggle_grid_list_view'),
                    action: {
                        type: 'function',
                        value: 'toggle-grid-list',
                    },
                },
                {
                    title: this.$t('toggle_dark_light_mode'),
                    action: {
                        type: 'function',
                        value: 'dark-mode',
                    },
                },
                {
                    title: this.$t('toggle_full_screen_mode'),
                    action: {
                        type: 'function',
                        value: 'full-screen-mode',
                    },
                },
            ]

            // Available only for apple users
            if (this.$isApple()) {
                functionList.push({
                    title: this.$t('toggle_emoji_type'),
                    action: {
                        type: 'function',
                        value: 'toggle-emoji',
                    },
                })
            }

            // Return commands for public page
            if (this.$isThisRoute(this.$route, ['Public'])) {
                return [].concat.apply([], [functionList])
            }

            // Return commands for logged admin
            if (this.isAdmin) {
                // Available only for fixed subscription
                if (this.config.subscriptionType === 'fixed') {
                    adminLocations.push({
                        title: this.$t('show_all_subscriptions'),
                        action: {
                            type: 'route',
                            value: 'Subscriptions',
                        },
                    })
                }

                // Available only when is metered billing and plan doesnt exist or when is fixed billing
                if (
                    (this.config.subscriptionType === 'metered' && !this.config.isCreatedMeteredPlan) ||
                    this.config.subscriptionType === 'fixed'
                ) {
                    adminActions.push({
                        title: this.$t('create_plan'),
                        action: {
                            type: 'route',
                            value: this.config.subscriptionType === 'fixed' ? 'CreateFixedPlan' : 'CreateMeteredPlan',
                        },
                    })
                }

                return [].concat.apply(
                    [],
                    [functionList, createList, userSettings, fileLocations, adminLocations, adminActions]
                )
            }

            // Return commands for logged user
            if (this.user.data.attributes.role === 'user') {
                return [].concat.apply([], [functionList, createList, userSettings, fileLocations])
            }
        },
        isAdmin() {
            return this.user.data.attributes.role === 'admin'
        },
        metaKeyIcon() {
            return this.$isApple() ? '⌘' : 'Ctrl'
        },
        isNotEmptyQuery() {
            return this.query !== ''
        },
        isEmptyQuery() {
            return this.query === '' || this.query === undefined
        },
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
                    description: this.$t('search_your_users'),
                    slug: 'users',
                },
            ],
        }
    },
    watch: {
        query(query) {
            if (query === '' || typeof query === 'undefined') this.results = []
            this.actions = []

            let formattedQuery = query.toLowerCase()

            // Reset selection index
            this.index = 0

            // Go for filter keyword
            let getFilterQuery = formattedQuery.substr(0, 2)

            // search for the users
            if (getFilterQuery === 'u ' && this.isAdmin && !this.activeFilter) {
                this.setFilter('users')
            }

            // Browse actions
            if (!this.activeFilter) {
                this.actions = this.actionList
                    .filter((el) => el.title.toLowerCase().indexOf(formattedQuery) > -1)
                    .slice(0, 3)
            }

            this.findResult(formattedQuery)
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
            if (this.actions.length > 0 && index < this.actions.length) {
                this.openAction(this.actions[index])
                return
            }

            // Open user
            if (this.activeFilter === 'users') {
                this.openUser(this.results[resultIndex])
            }

            // Open file or folder
            if (!this.activeFilter) {
                this.openItem(this.results[resultIndex])
            }
        },
        openAction(arg) {
            if (arg.action.type === 'route') {
                this.$router.push({ name: arg.action.value })
            }

            if (arg.action.type === 'function') {
                if (arg.action.value === 'toggle-emoji') {
                    this.$store.dispatch('toggleEmojiType')
                }

                if (arg.action.value === 'toggle-grid-list') {
                    this.$store.dispatch('togglePreviewType')
                }

                if (arg.action.value === 'dark-mode') {
                    this.$store.dispatch('toggleThemeMode')
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

                if (arg.action.value === 'create-team-folder') {
                    this.$createTeamFolder()
                }

                if (arg.action.value === 'create-file-request') {
                    this.$createFileRequest()
                }

                if (arg.action.value === 'remote-upload') {
                    this.$openRemoteUploadPopup()
                }
            }

            this.exitSpotlight()
        },
        openUser(user) {
            this.$router.push({
                name: 'UserDetail',
                params: { id: user.data.id },
            })

            this.exitSpotlight()
        },
        openItem(file) {
            // Show folder
            if (file.data.type === 'folder') {
                if (this.$isThisRoute(this.$route, ['Public'])) {
                    this.$router.push({
                        name: 'Public',
                        params: {
                            token: this.sharedDetail.data.attributes.token,
                            id: file.data.id,
                        },
                    })
                } else if (file.data.attributes.isTeamFolder) {
					let route = file.data.relationships.user.data.id === this.user.data.id
						? 'TeamFolders'
						: 'SharedWithMe'

					this.$router.push({
						name: route,
						params: { id: file.data.id },
					})
                } else {
                    this.$router.push({
                        name: 'Files',
                        params: { id: file.data.id },
                    })
                }
            }

			// Show file
			if (file.data.type !== 'folder') {
				this.$store.commit('ADD_TO_FAST_PREVIEW', file)
				this.$store.commit('CLIPBOARD_REPLACE', file)

				// Show file thumbnail
				events.$emit('file-preview:show')
			}

            this.exitSpotlight()
        },
        findResult: debounce(function (value) {
            // Prevent empty searching
            if (value === '' || typeof value === 'undefined') return

            this.isLoading = true

            // Get route
            let route = this.$store.getters.sharedDetail
                ? `/api/sharing/search/${this.$router.currentRoute.params.token}`
                : '/api/search'

            axios
                .get(`${route}?filter=${this.activeFilter}`, {
                    params: { query: value },
                })
                .then((response) => {
					this.results = response.data.data
                })
                .catch(() => this.$isSomethingWrong())
                .finally(() => (this.isLoading = false))
        }, 150),
        exitSpotlight() {
            this.actions = []
            this.results = []
            this.query = ''
            this.isVisible = false
        },
        onPageDown() {
            let results = this.results.length
            let actions = this.actions.length

            let totalResultLength = results + actions - 1

            if (this.index < totalResultLength) this.index++
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
        events.$on('spotlight:show', (filter) => {
            this.isVisible = true
            this.activeFilter = filter

            this.$nextTick(() => {
                if (this.$refs.searchInput) this.$refs.searchInput.focus()
            })
        })

        events.$on('spotlight:hide', () => this.exitSpotlight())
    },
}
</script>

<style lang="scss">
	#spotlight .item-name {
		padding-right: 35px;
	}
</style>
