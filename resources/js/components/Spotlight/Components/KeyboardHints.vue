<template>
    <div v-if="!$isMobile()" class="flex items-center px-5 pb-2">
        <!--Arrow key navigation-->
        <div class="mr-4 flex items-center">
            <ArrowUpIcon size="12" class="vue-feather text-gray-400" />
            <ArrowDownIcon size="12" class="vue-feather text-gray-400" />

            <span class="ml-1.5 text-xs text-gray-400">
                {{ $t('navigate') }}
            </span>
        </div>

        <!--Submit key-->
        <div class="flex items-center">
            <CornerDownLeftIcon size="12" class="vue-feather text-gray-400" />

            <span class="ml-1.5 text-xs text-gray-400">
                {{ $t('go') }}
            </span>
        </div>
    </div>
</template>

<script>
import { CornerDownLeftIcon, ArrowDownIcon, ArrowUpIcon } from 'vue-feather-icons'

export default {
    name: 'KeyboardHints',
    components: {
        CornerDownLeftIcon,
        ArrowDownIcon,
        ArrowUpIcon,
    },
}
</script>
