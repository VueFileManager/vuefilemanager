<template>
    <div @click="$openSpotlight" class="relative dark:bg-dark-foreground bg-light-background rounded-lg cursor-pointer">
        <div class="flex justify-between items-center px-5 py-2.5 xl:w-72 w-52 text-left">
			<div class="flex items-center">
				<search-icon size="18" class="vue-feather dark:text-gray-600 text-gray-400" />
				<span class="font-bold xl:text-sm text-xs dark:text-gray-600 text-gray-400 pl-2.5">
					{{ $t('inputs.placeholder_search_files') }}
				</span>
			</div>
			<span class="font-bold xl:text-sm text-xs dark:text-gray-600 text-gray-400 border rounded px-1 py-0.5 tracking-normal">
				{{ metaKeyIcon }}+K
			</span>
		</div>
    </div>
</template>

<script>
    import {SearchIcon} from 'vue-feather-icons'

    export default {
        name: 'SearchBar',
        components: {
            SearchIcon,
        },
		computed: {
			metaKeyIcon() {
				return this.$isApple() ? '⌘' : '⊞'
			},
		},
    }
</script>
