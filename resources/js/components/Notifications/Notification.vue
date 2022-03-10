<template>
	<article class="z-20 relative flex items-start p-2.5 mb-1.5 space-x-4 rounded-xl dark:hover:bg-4x-dark-foreground hover:bg-light-background bg-opacity-80">

		<user-plus-icon v-if="notification.type === 'team-invitation'" size="20" class="vue-feather text-theme shrink-0" />
		<upload-cloud-icon v-if="['file-request', 'remote-upload-done'].includes(notification.type)" size="20" class="vue-feather text-theme shrink-0" />

		<div>
			<b class="font-extrabold text-sm mb-1.5 block">
				{{ notification.title }}
			</b>

			<p class="text-sm mb-1.5 dark:text-gray-500">
				{{ notification.description }}
			</p>

			<div class="flex items-center mb-4">
				<!--<MemberAvatar class="mr-2" :size="22" :is-border="false" :member="user" />-->
				<time class=" block text-xs dark:text-gray-400 text-gray-400">
					09. Mar. 2022, 08:27
				</time>
			</div>

			<!--Accept or decline team invitation-->
			<div v-if="notification.type === 'team-invitation'" class="flex items-center space-x-3">
				<div class="flex items-center cursor-pointer py-1.5 px-2 rounded-xl transition-colors dark:hover:bg-green-900 hover:bg-green-100">
					<check-icon size="16" class="vue-feather dark:text-green-600 text-green-600 mr-2" />
					<span class="text-sm font-bold dark:text-green-600 text-green-600">
						{{ $t('Accept') }}
					</span>
				</div>

				<div class="flex items-center cursor-pointer py-1.5 px-2 rounded-xl transition-colors dark:hover:bg-rose-900 hover:bg-rose-100">
					<x-icon size="16" class="vue-feather dark:text-rose-600 text-rose-600 mr-2" />
					<span class="text-sm font-bold dark:text-rose-600 text-rose-600">
						{{ $t('Decline') }}
					</span>
				</div>
			</div>

			<!--Go to route-->
			<router-link v-if="['file-request', 'remote-upload-done'].includes(notification.type)" :to="{ name: 'Users' }" class="mt-4 flex items-center">
				<span class="mr-2 whitespace-nowrap text-xs font-bold">
					{{ $t('Show Files') }}
				</span>
				<chevron-right-icon size="16" class="text-theme vue-feather" />
			</router-link>
		</div>
	</article>
</template>
<script>
import {CheckIcon, XIcon, MailIcon, UserPlusIcon, UploadCloudIcon, ChevronRightIcon} from 'vue-feather-icons'
import MemberAvatar from '../FilesView/MemberAvatar'

export default {
	name: 'Notification',
	props: [
		'notification',
	],
	components: {
		MemberAvatar,
		ChevronRightIcon,
		UploadCloudIcon,
		UserPlusIcon,
		CheckIcon,
		MailIcon,
		XIcon,
	}
}
</script>