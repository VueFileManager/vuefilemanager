<template>
    <div @click="goBack" class="flex items-center text-left select-none relative">

		<!--Menu icon-->
        <div v-if="!isVisibleNavigationBars" @click="toggleNavigationBars" class="-mt-0.5 mr-2 p-2 cursor-pointer lg:block hidden">
            <menu-icon size="17" />
        </div>

        <chevron-left-icon
            size="17"
            :class="{
                '-translate-x-3 opacity-0': !isLoadedFolder || !isNotHomepage,
                'translate-x-0 opacity-100': isLoadedFolder && isNotHomepage,
            }"
            class="lg:-mt-0.5 mr-2 -ml-1 cursor-pointer align-middle transition-all duration-200"
        />

        <!--Folder Title-->
        <b
            :class="{ '-translate-x-4': !isLoadedFolder || !isNotHomepage }"
            class="inline-block transform overflow-hidden text-ellipsis whitespace-nowrap align-middle text-sm font-bold transition-all duration-200 max-w-[200px]"
        >
            {{ $getCurrentLocationName() }}
        </b>

        <div
            @click.stop="showItemActions"
            :class="{
                '-translate-x-4 opacity-0': !currentFolder || !isNotHomepage,
                'translate-x-0 opacity-100': currentFolder && isNotHomepage,
            }"
            class="ml-3 transform rounded-md bg-light-background py-0.5 px-1.5 transition-all duration-200 dark:bg-dark-foreground cursor-pointer"
			id="folder-actions"
        >
            <more-horizontal-icon size="14" />
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";
import {events} from "../../bus";
import { MenuIcon, ChevronLeftIcon, MoreHorizontalIcon } from 'vue-feather-icons'

export default {
    name: 'NavigationBar',
	components: {
		MoreHorizontalIcon,
		ChevronLeftIcon,
		MenuIcon,
	},
	computed: {
		...mapGetters([
			'isVisibleNavigationBars',
			'currentFolder',
			'sharedDetail',
		]),
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
	}
}
</script>
