<template>
	<div class="flex text-center py-5 px-4 w-full justify-between items-center z-20 lg:hidden block">

		<!-- Go back-->
		<div @click="$router.back()" class="go-back-button flex text-left items-center cursor-pointer">
			<chevron-left-icon size="17" class="transform align-middle mr-2 -ml-1 dark:text-gray-100 vue-feather" />

			<!--Location Title-->
			<div class="text-sm dark:text-gray-100 align-middle font-bold overflow-hidden overflow-ellipsis inline-block whitespace-nowrap transition-all duration-200" style="max-width: 200px;">
				{{ locationName }}
			</div>
		</div>

		<!--More Actions-->
		<div class="flex items-center">
			<div @click="$openSpotlight()" class="px-2 mr-4 cursor-pointer">
				<search-icon size="17" class="vue-feather dark:text-gray-100" />
			</div>
			<div @click="$showMobileMenu('user-navigation')" class="pr-2 cursor-pointer">
				<menu-icon size="17" class="vue-feather dark:text-gray-100" />
			</div>
		</div>
	</div>
</template>
<script>
    import {MenuIcon, ChevronLeftIcon, SearchIcon } from 'vue-feather-icons'

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
			}
		}
	}
</script>
