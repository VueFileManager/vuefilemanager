<template>
    <ContentSidebar
        v-if="navigationTree && navigationTree.length >= 1 && isVisibleNavigationBars"
        class="relative lg:!grid"
    >
        <!--Full screen button-->
        <div
            @click="$store.dispatch('toggleNavigationBars')"
            class="absolute top-2.5 right-0 inline-block cursor-pointer p-3 opacity-20 transition-all duration-200 hover:opacity-70"
        >
            <chevrons-left-icon size="18" />
        </div>

        <div class="mb-auto">
            <!--Locations-->
            <ContentGroup :title="$t('base')">
                <b
                    @click="goHome"
                    class="flex cursor-pointer items-center py-2.5"
                    :class="{ 'router-link-active': $route.params.id === sharedDetail.data.attributes.item_id }"
                >
                    <home-icon size="17" class="vue-feather icon-active mr-2.5" />
                    <small class="text-active text-xs font-bold">
                        {{ $t('home') }}
                    </small>
                </b>
            </ContentGroup>

            <!--Navigator-->
            <ContentGroup :title="$t('navigator')" can-collapse="true">
                <TreeMenuNavigator
                    class="folder-tree"
                    :depth="0"
                    :nodes="folder"
                    v-for="folder in navigationTree"
                    :key="folder.id"
                />
            </ContentGroup>
        </div>

        <ContentGroup class="mt-auto">
            <router-link
                v-if="!config.isAuthenticated"
                :to="{ name: 'SignIn' }"
                class="group flex cursor-pointer items-center py-2.5"
            >
                <div class="button-icon inline-block cursor-pointer rounded-xl pr-3">
                    <user-icon size="14" class="vue-feather group-hover-text-theme" />
                </div>
                <b class="group-hover-text-theme text-xs"> Sign In or Create Account </b>
            </router-link>
            <div
                @click="$store.dispatch('toggleThemeMode')"
                :title="$t('dark_mode_toggle')"
                class="group flex cursor-pointer items-center py-2.5"
            >
                <div class="button-icon inline-block cursor-pointer rounded-xl pr-3">
                    <sun-icon v-if="isDarkMode" size="14" class="vue-feather group-hover-text-theme" />
                    <moon-icon v-if="!isDarkMode" size="14" class="vue-feather group-hover-text-theme" />
                </div>
                <b class="group-hover-text-theme text-xs"> Set {{ isDarkMode ? 'Light' : 'Dark' }} Mode </b>
            </div>
        </ContentGroup>
    </ContentSidebar>
</template>

<script>
import {
    UserIcon,
    SunIcon,
    MoonIcon,
    ChevronsLeftIcon,
    FolderIcon,
    HomeIcon,
    LinkIcon,
    Trash2Icon,
    UploadCloudIcon,
    UserCheckIcon,
    UsersIcon,
    XIcon,
} from 'vue-feather-icons'
import TreeMenuNavigator from '../UI/Trees/TreeMenuNavigator'
import ContentSidebar from '../Sidebar/ContentSidebar'
import ContentGroup from '../Sidebar/ContentGroup'
import { events } from '../../bus'
import { mapGetters } from 'vuex'

export default {
    name: 'NavigationSharePanel',
    components: {
        TreeMenuNavigator,
        ContentSidebar,
        ContentGroup,
        UserIcon,
        SunIcon,
        MoonIcon,
        UploadCloudIcon,
        ChevronsLeftIcon,
        UserCheckIcon,
        FolderIcon,
        Trash2Icon,
        UsersIcon,
        HomeIcon,
        LinkIcon,
        XIcon,
    },
    computed: {
        ...mapGetters([
            'sharedDetail',
            'navigation',
            'clipboard',
            'config',
            'user',
            'isVisibleNavigationBars',
            'isDarkMode',
        ]),
        favourites() {
            return this.user.data.relationships.favourites.attributes.folders
        },
        storage() {
            return this.$store.getters.user.data.attributes.storage
        },
        tree() {
            return this.user.data.attributes.folders
        },
        navigationTree() {
            return this.navigation ? this.navigation[0].folders : undefined
        },
    },
    data() {
        return {
            draggedItem: undefined,
            area: false,
        }
    },
    methods: {
        goHome() {
            this.$router.replace({
                name: 'Public',
                params: {
                    token: this.sharedDetail.data.attributes.token,
                    id: this.sharedDetail.data.attributes.item_id,
                },
            })
        },
    },
    created() {
        // Listen for dragstart folder items
        events.$on('dragstart', (item) => (this.draggedItem = item))
    },
}
</script>
