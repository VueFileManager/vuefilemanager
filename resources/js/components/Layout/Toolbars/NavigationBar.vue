<template>
    <div @click="goBack" class="relative flex select-none items-center text-left min-w-0 lg:pr-6 pr-12">
        <!--Menu icon-->
        <div
            v-if="!isVisibleNavigationBars"
            @click.stop="toggleNavigationBars"
            class="-mt-0.5 mr-2 hidden cursor-pointer p-2 lg:block"
        >
            <menu-icon size="17" />
        </div>

        <chevron-left-icon
            size="17"
            :class="{
                '-translate-x-3 opacity-0': !isLoadedFolder || !isNotHomepage,
                'translate-x-0 opacity-100': isLoadedFolder && isNotHomepage,
            }"
            class="mr-2 -ml-1 cursor-pointer align-middle transition-all duration-200 lg:-mt-0.5 shrink-0"
        />

        <!--Folder Title-->
        <b
            :class="{ '-translate-x-4': !isLoadedFolder || !isNotHomepage }"
            class="inline-block min-w-0 transform overflow-hidden text-ellipsis whitespace-nowrap align-middle text-sm font-bold transition-all duration-200 dark:text-gray-100"
        >
            {{ $getCurrentLocationName() }}
        </b>

        <div
            @click.stop="showItemActions"
            :class="{
                '-translate-x-4 opacity-0': !currentFolder || !isNotHomepage,
                'translate-x-0 opacity-100': currentFolder && isNotHomepage,
            }"
            class="relative ml-3 transform cursor-pointer rounded-md bg-light-background py-0.5 px-1.5 transition-all duration-200 dark:bg-dark-foreground"
            id="folder-actions"
        >
            <more-horizontal-icon size="14" />
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import { events } from '../../../bus'
import { MenuIcon, ChevronLeftIcon, MoreHorizontalIcon } from 'vue-feather-icons'

export default {
    name: 'NavigationBar',
    components: {
        MoreHorizontalIcon,
        ChevronLeftIcon,
        MenuIcon,
    },
    computed: {
        ...mapGetters(['isVisibleNavigationBars', 'currentFolder', 'sharedDetail']),
        isNotHomepage() {
            if (this.$isThisRoute(this.$route, ['Public'])) {
                return this.sharedDetail && this.sharedDetail.data.attributes.item_id !== this.$route.params.id
            }

            return this.$route.params.id
        },
        isLoadedFolder() {
            return this.$route.params.id
        },
    },
    methods: {
        goBack() {
            if (this.isNotHomepage) this.$router.back()
        },
        showItemActions() {
            if (window.innerWidth > 1024) {
                events.$emit('context-menu:current-folder', this.currentFolder)
            } else {
                this.$store.commit('CLIPBOARD_CLEAR')
                this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.currentFolder)

                this.$showMobileMenu('file-menu')
                events.$emit('mobile-context-menu:show', this.currentFolder)
            }
        },
        toggleNavigationBars() {
            this.$store.dispatch('toggleNavigationBars')
        },
    },
}
</script>
