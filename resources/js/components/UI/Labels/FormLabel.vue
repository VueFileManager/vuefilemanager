<template>
    <div class="mb-8 flex items-center">
        <edit-2-icon v-if="!icon" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <frown-icon v-if="icon === 'frown'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <list-icon v-if="icon === 'list'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <info-icon v-if="icon === 'info'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <database-icon v-if="icon === 'database'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <file-text-icon v-if="icon === 'file-text'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <dollar-sign-icon v-if="icon === 'dollar'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <credit-card-icon v-if="icon === 'credit-card'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <bar-chart-icon v-if="icon === 'bar-chart'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <settings-icon v-if="icon === 'settings'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <hard-drive-icon v-if="icon === 'hard-drive'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <mail-icon v-if="icon === 'mail'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <smartphone-icon v-if="icon === 'smartphone'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <shield-icon v-if="icon === 'shield'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <bell-icon v-if="icon === 'bell'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <key-icon v-if="icon === 'key'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <users-icon v-if="icon === 'users'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <wifi-icon v-if="icon === 'wifi'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <trending-up-icon v-if="icon === 'trending-up'" size="22" class="vue-feather text-theme dark-text-theme mr-3" />
        <b class="text-md font-bold dark:text-gray-200 sm:text-lg">
            <slot />
        </b>
    </div>
</template>

<script>
import {
	TrendingUpIcon,
	WifiIcon,
	ListIcon,
	MailIcon,
    InfoIcon,
    DatabaseIcon,
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
    props: ['icon'],
    components: {
		TrendingUpIcon,
		WifiIcon,
		ListIcon,
		MailIcon,
        InfoIcon,
        DatabaseIcon,
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
    },
}
</script>
