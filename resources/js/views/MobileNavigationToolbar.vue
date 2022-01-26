<template>
	<div class="flex text-center py-5 px-4 w-full justify-between items-center z-20 lg:hidden block">

		<!-- Go back-->
		<div @click="$router.back()" class="go-back-button flex text-left items-center cursor-pointer">
			<chevron-left-icon size="17" class="transform align-middle mr-2 -ml-1" />

			<!--Folder Title-->
			<div class="text-sm align-middle font-bold overflow-hidden overflow-ellipsis inline-block whitespace-nowrap transition-all duration-200" style="max-width: 200px;">
				{{ locationName }}
			</div>
		</div>

		<!--More Actions-->
		<div @click="$showMobileMenu('user-navigation')" class="pr-2 cursor-pointer">
			<menu-icon size="17" />
		</div>
	</div>
</template>
<script>
    import {MenuIcon, ChevronLeftIcon } from 'vue-feather-icons'

	export default {
		name: 'MobileNavigationToolbar',
		components: {
			ChevronLeftIcon,
			MenuIcon,
		},
		computed: {
			locationName() {
				return this.$t(this.$route.meta.title)
			}
		}
	}
</script>
