<template>
    <div class="widget-card w-full">
        <div class="widget-content bg-white dark:bg-dark-foreground">
            <div class="flex items-center">
                <users-icon v-if="icon === 'users'" size="16" class="vue-feather text-theme mr-3" />
                <star-icon v-if="icon === 'star'" size="16" class="vue-feather text-theme mr-3" />
                <hard-drive-icon v-if="icon === 'hard-drive'" size="16" class="vue-feather text-theme mr-3" />
                <b class="text-base font-bold">
                    {{ title }}
                </b>
            </div>
            <slot></slot>
        </div>
    </div>
</template>

<script>
import { UsersIcon, StarIcon, HardDriveIcon, ChevronRightIcon } from 'vue-feather-icons'

export default {
    name: 'WidgetWrapper',
    props: ['icon', 'title'],
    components: {
        ChevronRightIcon,
        HardDriveIcon,
        StarIcon,
        UsersIcon,
    },
}
</script>
