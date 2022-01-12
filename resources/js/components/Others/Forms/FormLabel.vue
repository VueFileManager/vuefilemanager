<template>
    <div class="flex items-center mb-8">
        <edit-2-icon v-if="!icon" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <frown-icon v-if="icon === 'frown'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <file-text-icon v-if="icon === 'file-text'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <dollar-sign-icon v-if="icon === 'dollar'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <credit-card-icon v-if="icon === 'credit-card'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <bar-chart-icon v-if="icon === 'bar-chart'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <settings-icon v-if="icon === 'settings'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <hard-drive-icon v-if="icon === 'hard-drive'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <smartphone-icon v-if="icon === 'smartphone'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <shield-icon v-if="icon === 'shield'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <bell-icon v-if="icon === 'bell'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <key-icon v-if="icon === 'key'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <users-icon v-if="icon === 'users'" size="22" class="mr-3 vue-feather text-theme dark-text-theme" />
        <b class="font-bold dark:text-gray-200 text-lg">
            <slot></slot>
        </b>
    </div>
</template>

<script>
    import {
		UsersIcon,
		ShieldIcon,
		CreditCardIcon,
		DollarSignIcon,
		SmartphoneIcon,
		HardDriveIcon,
		BarChartIcon,
		SettingsIcon,
		FileTextIcon,
		FrownIcon,
		Edit2Icon,
		BellIcon,
		KeyIcon,
	} from 'vue-feather-icons'

    export default {
        name: 'FormLabel',
        props: [
			'icon'
		],
        components: {
			UsersIcon,
			CreditCardIcon,
			DollarSignIcon,
			SmartphoneIcon,
			HardDriveIcon,
			BarChartIcon,
			SettingsIcon,
			FileTextIcon,
			ShieldIcon,
			FrownIcon,
			Edit2Icon,
			BellIcon,
			KeyIcon,
        }
    }
</script>
