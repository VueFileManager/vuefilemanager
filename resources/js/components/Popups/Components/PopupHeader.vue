<template>
    <div class="flex items-center justify-between px-6 pt-6 pb-6">
        <div class="flex items-center">
            <div class="mr-3">
                <link2-icon v-if="icon === 'remote-upload'" size="18" class="vue-feather text-theme" />
                <upload-cloud-icon v-if="icon === 'upload'" size="18" class="vue-feather text-theme" />
                <corner-down-right-icon v-if="icon === 'move'" size="18" class="vue-feather text-theme" />
                <share-icon v-if="icon === 'share'" size="18" class="vue-feather text-theme" />
                <edit2-icon v-if="icon === 'edit'" size="18" class="vue-feather text-theme" />
                <key-icon v-if="icon === 'key'" size="18" class="vue-feather text-theme" />
                <users-icon v-if="icon === 'users'" size="18" class="vue-feather text-theme" />
                <user-plus-icon v-if="icon === 'user-plus'" size="18" class="vue-feather text-theme" />
                <credit-card-icon v-if="icon === 'credit-card'" size="18" class="vue-feather text-theme" />
                <bell-icon v-if="icon === 'bell'" size="18" class="vue-feather text-theme" />
            </div>

            <b class="text-base font-bold">
                {{ title }}
            </b>
        </div>
        <div @click="closePopup" class="-m-3 cursor-pointer p-3">
            <x-icon size="14" class="hover-text-theme vue-feather" />
        </div>
    </div>
</template>

<script>
import {
	Link2Icon,
	BellIcon,
	UploadCloudIcon,
    CreditCardIcon,
    KeyIcon,
    UserPlusIcon,
    CornerDownRightIcon,
    LinkIcon,
    XIcon,
    Edit2Icon,
    ShareIcon,
    UsersIcon,
} from 'vue-feather-icons'
import { events } from '../../../bus'

export default {
    name: 'PopupHeader',
    props: ['title', 'icon'],
    components: {
		Link2Icon,
		BellIcon,
		UploadCloudIcon,
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
        },
    },
}
</script>
