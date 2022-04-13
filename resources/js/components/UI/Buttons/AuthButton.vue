<template>
    <button
        class="group mx-auto inline-block flex items-center whitespace-nowrap rounded-lg border-2 border-black px-7 py-2.5 dark:border-gray-300"
    >
        <span class="pr-1 text-lg font-extrabold">
            {{ text }}
        </span>
        <refresh-cw-icon v-if="loading" size="20" class="vue-feather text-theme sync-alt -mr-1" />
        <chevron-right-icon v-if="!loading && icon" size="20" class="vue-feather text-theme -mr-1" />
    </button>
</template>

<script>
import { ChevronRightIcon, RefreshCwIcon } from 'vue-feather-icons'

export default {
    name: 'AuthContent',
    props: ['loading', 'icon', 'text'],
    components: {
        ChevronRightIcon,
        RefreshCwIcon,
    },
    data() {
        return {
            isVisible: false,
        }
    },
    created() {
        this.isVisible = this.visible
    },
}
</script>

<style scoped lang="scss">
.sync-alt {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
