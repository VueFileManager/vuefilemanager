<template>
    <article
        class="delay-[3000ms] duration-700 transition-all relative z-[11] mb-1.5 flex items-start space-x-4 rounded-xl p-2.5"
		:class="{'dark:bg-4x-dark-foreground bg-light-background/80': isUnread}"
    >
        <gift-icon
            v-if="notification.data.attributes.category === 'gift'"
            size="22"
            class="vue-feather text-theme shrink-0"
        />
        <user-plus-icon
            v-if="notification.data.attributes.category === 'team-invitation'"
            size="22"
            class="vue-feather text-theme shrink-0"
        />
        <trending-up-icon
            v-if="notification.data.attributes.category === 'subscription-created'"
            size="22"
            class="vue-feather text-theme shrink-0"
        />
        <alert-triangle-icon
            v-if="['billing-alert', 'insufficient-balance', 'payment-alert'].includes(notification.data.attributes.category)"
            size="22"
            class="vue-feather text-theme shrink-0"
        />
        <upload-cloud-icon
            v-if="['file-request', 'remote-upload-done'].includes(notification.data.attributes.category)"
            size="22"
            class="vue-feather text-theme shrink-0"
        />

        <div>
            <b class="mb-1.5 block font-extrabold">
                {{ notification.data.attributes.title }}
            </b>

            <p class="mb-1.5 text-sm dark:text-gray-500">
                {{ notification.data.attributes.description }}
            </p>

            <div class="flex items-center">
                <!--<MemberAvatar class="mr-2" :size="22" :is-border="false" :member="user" />-->
                <time class="block text-xs text-gray-400 dark:text-gray-400">
                    {{ notification.data.attributes.created_at }}
                </time>
            </div>

            <!--Accept or decline team invitation-->
            <div v-if="action && action.type === 'invitation'" class="flex items-center space-x-3 mt-4">
                <div
					@click="acceptAction"
                    class="relative flex cursor-pointer items-center rounded-xl py-1.5 px-2 transition-colors bg-green-100 dark:bg-green-900"
                >
					<refresh-cw-icon v-if="isAccepting" size="16" class="animate-spin left-0 right-0 mx-auto vue-feather text-green-600 dark:text-green-600 absolute justify-center" />
                    <check-icon size="16" class="vue-feather mr-1 text-green-600 dark:text-green-600" :class="{'opacity-0': isAccepting}" />
                    <span class="text-sm font-bold text-green-600 dark:text-green-600" :class="{'opacity-0': isAccepting}">
                        {{ $t('accept') }}
                    </span>
                </div>

                <div
					@click="declineAction"
                    class="relative flex cursor-pointer items-center rounded-xl py-1.5 px-2 transition-colors bg-rose-100 dark:bg-rose-900"
                >
					<refresh-cw-icon v-if="isDeclining" size="16" class="animate-spin left-0 right-0 mx-auto vue-feather text-rose-600 dark:text-rose-600 absolute justify-center" />
                    <x-icon size="16" class="vue-feather mr-1 text-rose-600 dark:text-rose-600" :class="{'opacity-0': isDeclining}" />
                    <span class="text-sm font-bold text-rose-600 dark:text-rose-600 capitalize" :class="{'opacity-0': isDeclining}">
                        {{ $t('decline') }}
                    </span>
                </div>
            </div>

            <!--Go to route-->
            <router-link
                @click.native="closeCenter"
                v-if="action && action.type === 'route'"
                :to="{ name: action.params.route, params: { id: action.params.id } }"
                class="mt-4 flex items-center"
            >
                <span class="mr-2 whitespace-nowrap text-xs font-bold">
                    {{ action.params.button }}
                </span>
                <chevron-right-icon size="16" class="text-theme vue-feather" />
            </router-link>

            <!--Open Link-->
            <a
                @click.native="closeCenter"
                v-if="action && action.type === 'url'"
				:target="action.params.target === 'blank' ? '_blank' : '_self'"
                :href="action.params.url"
                class="mt-4 flex items-center"
            >
                <span class="mr-2 whitespace-nowrap text-xs font-bold">
                    {{ action.params.button }}
                </span>
                <chevron-right-icon size="16" class="text-theme vue-feather" />
            </a>
        </div>
    </article>
</template>
<script>
import { RefreshCwIcon, TrendingUpIcon, GiftIcon, CheckIcon, XIcon, MailIcon, UserPlusIcon, UploadCloudIcon, ChevronRightIcon, AlertTriangleIcon } from 'vue-feather-icons'
import MemberAvatar from '../../UI/Others/MemberAvatar'
import {events} from "../../../bus";

export default {
    name: 'Notification',
    props: ['notification'],
    components: {
        MemberAvatar,
		AlertTriangleIcon,
        ChevronRightIcon,
        UploadCloudIcon,
		TrendingUpIcon,
		RefreshCwIcon,
        UserPlusIcon,
        CheckIcon,
		GiftIcon,
        MailIcon,
        XIcon,
    },
    computed: {
        action() {
            return this.notification.data.attributes.action
        },
    },
	data() {
		return {
			isUnread: false,
			isAccepting: false,
			isDeclining: false,
		}
	},
    methods: {
		acceptAction() {
			this.isAccepting = true

			axios.put(`/api/teams/invitations/${this.notification.data.attributes.action.params.id}`)
				.then(() => {
					this.$store.commit('CLEAR_NOTIFICATION_ACTION_DATA', this.notification.data.id)

					events.$emit('toaster', {
						type: 'success',
						message: this.$t('you_accepted_invitation'),
					})
				})
				.finally(() => this.isAccepting = false)
		},
		declineAction() {
			this.isDeclining = true

			axios.delete(`/api/teams/invitations/${this.notification.data.attributes.action.params.id}`)
				.then(() => {
					this.$store.commit('CLEAR_NOTIFICATION_ACTION_DATA', this.notification.data.id)

					events.$emit('toaster', {
						type: 'success',
						message: this.$t('you_decline_invitation'),
					})
				})
				.finally(() => this.isDeclining = false)
		},
        closeCenter() {
            this.$store.commit('CLOSE_NOTIFICATION_CENTER')
			this.$closePopup()
        },
    },
	created() {
		this.isUnread = this.notification.data.attributes.read_at === null

		setTimeout(() => this.isUnread = false, 1000)
	}
}
</script>