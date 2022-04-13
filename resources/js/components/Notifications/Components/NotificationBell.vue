<template>
	<div class="relative button-icon inline-block cursor-pointer rounded-xl p-3">
		<bell-icon size="18" class="vue-feather dark:text-gray-100 transition" :class="{'rotate-[30deg]': notificationCount}" />
		<span v-if="notificationCount" class="absolute z-[9] right-1.5 bottom-1.5 flex items-center justify-center w-4 h-4 bg-theme text-white rounded-full text-xs font-bold">
			{{ notificationCount }}
		</span>
		<span v-if="notificationCount" class="animate-ping absolute z-[8] right-1.5 bottom-1.5 flex items-center justify-center w-4 h-4 bg-theme text-white rounded-full text-xs font-bold"></span>
	</div>
</template>
<script>
import {mapGetters} from "vuex";
import {BellIcon} from "vue-feather-icons"

export default {
	name: 'NotificationBell',
	components: {
		BellIcon,
	},
	computed: {
		...mapGetters([
			'notificationCount'
		]),
	}
}
</script>
