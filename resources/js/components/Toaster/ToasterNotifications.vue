<template>
    <div
		v-if="toasters.length || notifications.length"
		class="fixed bottom-4 right-4 left-4 z-[55] sm:w-[360px] sm:left-auto lg:bottom-8 lg:right-8"
	>
        <ToasterWrapper
			v-for="notification in notifications"
			:key="notification.data.id"
			class="mt-4 overflow-hidden rounded-xl dark:bg-2x-dark-foreground bg-white/80 backdrop-blur-2xl shadow-xl"
			bar-color="bg-theme"
		>
            <Notification :notification="notification" class="z-10 !mb-0 !px-4 !pt-4 !pb-5" />
        </ToasterWrapper>

		<ToasterWrapper
			v-for="(toaster, i) in toasters"
			:key="i"
			class="mt-4 overflow-hidden rounded-xl shadow-xl"
			:bar-color="getToasterColor(toaster)"
		>
        	<Toaster :item="toaster" />
        </ToasterWrapper>
    </div>
</template>

<script>
import Notification from '../Notifications/Components/Notification'
import ToasterWrapper from './ToasterWrapper'
import {events} from '../../bus'
import Toaster from './Toaster'

export default {
	components: {
		ToasterWrapper,
		Notification,
		Toaster,
	},
	data() {
		return {
			notifications: [],
			toasters: [],
		}
	},
	methods: {
		getToasterColor(toaster) {
			return {
				danger: 'bg-red-400',
				success: 'bg-green-400',
			}[toaster.type]
		}
	},
	created() {
		events.$on('toaster', (toaster) => this.toasters.push(toaster))
		events.$on('notification', (notification) => this.notifications.push(notification))

		/*events.$emit('notification', {
			data: {
				type: 'file-request',
				id: 'df954d23-f9d4-4677-85c8-abfd48aaa090',
				attributes: {
					action: {
						type: 'route',
						params: {
							route: 'Files',
							button: 'Show Files',
							id: 'ae37b1d8-c147-489a-83ab-2a3c7cb86263',
						},
					},
					created_at: '',
					description: "Your file request for 'Multi Level Folder' folder was filled successfully.",
					read_at: '',
					title: 'File Request Filled',
					category: 'file-request',
				},
			},
		})*/
	},
}
</script>
