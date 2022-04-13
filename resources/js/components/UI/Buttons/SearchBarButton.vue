<template>
    <div
        @click="$openSpotlight()"
        class="relative cursor-pointer rounded-lg bg-light-background dark:bg-dark-foreground"
    >
        <div class="flex w-56 items-center justify-between px-4 py-2.5 text-left xl:w-64">
            <div class="flex items-center">
                <search-icon size="18" class="vue-feather text-gray-400 dark:text-gray-600" />
                <span class="pl-2.5 text-xs font-bold text-gray-400 dark:text-gray-600">
                    {{ $t('search_anything') }}
                </span>
            </div>
            <span
                class="rounded border px-1 py-0.5 text-xs font-bold tracking-normal text-gray-400 dark:border-slate-200 dark:border-opacity-5 dark:text-gray-600"
            >
                {{ metaKeyIcon }}+K
            </span>
        </div>
    </div>
</template>

<script>
import { SearchIcon } from 'vue-feather-icons'

    export default {
        name: 'SearchBarButton',
        components: {
            SearchIcon,
        },
		computed: {
			metaKeyIcon() {
				return this.$isApple() ? '⌘' : 'Ctrl'
			},
		},
    }
</script>
