<template>
	<div class="flex items-center justify-between">
		<div class="flex items-center leading-none">
			<MemberAvatar :size="52" :is-border="false" :member="user" />
			<div class="pl-4">
				<b class="mb-1 block font-bold leading-none">
					{{ user.data.relationships.settings.data.attributes.name }}
				</b>
				<span class="text-theme text-sm font-semibold leading-none">
					{{ user.data.attributes.email }}
				</span>
			</div>
		</div>
		<NotificationBell @click="openNotificationPopup" />
	</div>
</template>

<script>
import MemberAvatar from '../FilesView/MemberAvatar'
import { mapGetters } from 'vuex'
import NotificationBell from "./NotificationBell";

export default {
    name: 'UserHeadline',
    components: {
		NotificationBell,
        MemberAvatar,
    },
    computed: {
        ...mapGetters(['user']),
    },
	methods: {
		openNotificationPopup() {

		}
	}
}
</script>
