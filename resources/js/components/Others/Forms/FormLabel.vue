<template>
    <div class="flex items-center mb-8">
        <edit-2-icon v-if="!icon" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <file-text-icon v-if="icon === 'file-text'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <settings-icon v-if="icon === 'settings'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <hard-drive-icon v-if="icon === 'hard-drive'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <smartphone-icon v-if="icon === 'smartphone'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <bell-icon v-if="icon === 'bell'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <key-icon v-if="icon === 'key'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <b class="font-bold text-lg">
            <slot></slot>
        </b>
    </div>
</template>

<script>
    import {
		BellIcon,
		FileTextIcon,
		HardDriveIcon,
		KeyIcon,
		Edit2Icon,
		SettingsIcon,
		SmartphoneIcon,
	} from 'vue-feather-icons'

    export default {
        name: 'FormLabel',
        props: ['icon'],
        components: {
			FileTextIcon,
			BellIcon,
			KeyIcon,
            Edit2Icon,
            SettingsIcon,
			HardDriveIcon,
			SmartphoneIcon,
        }
    }
</script>
