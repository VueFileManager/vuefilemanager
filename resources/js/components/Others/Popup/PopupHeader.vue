<template>
    <div class="flex items-center justify-between px-6 pt-6 pb-6">
        <div class="flex items-center">
			<div class="mr-3">
				<corner-down-right-icon v-if="icon === 'move'" size="18" class="vue-feather text-theme" />
				<share-icon v-if="icon === 'share'" size="18" class="vue-feather text-theme" />
				<edit2-icon v-if="icon === 'edit'" size="18" class="vue-feather text-theme" />
				<key-icon v-if="icon === 'key'" size="18" class="vue-feather text-theme" />
				<users-icon v-if="icon === 'users'" size="18" class="vue-feather text-theme" />
				<user-plus-icon v-if="icon === 'user-plus'" size="18" class="vue-feather text-theme" />
				<credit-card-icon v-if="icon === 'credit-card'" size="18" class="vue-feather text-theme" />
			</div>

            <b class="font-bold text-base">
				{{ title }}
			</b>
        </div>
        <div @click="closePopup" class="cursor-pointer">
            <x-icon size="14" class="hover-text-theme vue-feather" />
        </div>
    </div>
</template>

<script>
    import {CreditCardIcon, KeyIcon, UserPlusIcon, CornerDownRightIcon, LinkIcon, XIcon, Edit2Icon, ShareIcon, UsersIcon} from 'vue-feather-icons'
    import {events} from '/resources/js/bus'

    export default {
        name: 'PopupHeader',
        props: [
            'title', 'icon'
        ],
        components: {
            CornerDownRightIcon,
			CreditCardIcon,
			UserPlusIcon,
			UsersIcon,
            ShareIcon,
            Edit2Icon,
            LinkIcon,
			KeyIcon,
            XIcon,
        },
        methods: {
            closePopup() {
                events.$emit('popup:close')
            }
        }
    }
</script>
