<template>
    <div class="z-20 relative block flex w-full items-center justify-between py-5 px-5 text-center lg:hidden">
        <!--Location Title-->
        <div
            class="inline-block overflow-hidden text-ellipsis whitespace-nowrap align-middle text-sm font-bold transition-all duration-200 dark:text-gray-100"
            style="max-width: 200px"
        >
            {{ locationName }}
        </div>

        <!--More Actions-->
        <div class="flex items-center">
            <div @click="$openSpotlight()" class="mr-4 cursor-pointer px-2">
                <search-icon size="17" class="vue-feather dark:text-gray-100" />
            </div>
            <div @click="$showMobileMenu('user-navigation')" class="cursor-pointer pr-1.5">
                <menu-icon size="17" class="vue-feather dark:text-gray-100" />
            </div>
        </div>
    </div>
</template>
<script>
import { MenuIcon, ChevronLeftIcon, SearchIcon } from 'vue-feather-icons'

export default {
    name: 'MobileNavigationToolbar',
    components: {
        ChevronLeftIcon,
        SearchIcon,
        MenuIcon,
    },
    computed: {
        locationName() {
            return this.$t(this.$route.meta.title)
        },
    },
}
</script>
