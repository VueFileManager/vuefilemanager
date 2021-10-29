<template>
    <div @click="$openSpotlight" class="relative dark:bg-dark-foreground bg-light-background rounded-lg cursor-pointer">
        <div class="flex justify-between px-5 py-3 xl:w-72 w-52 text-left">
			<span class="font-bold xl:text-sm text-xs dark:text-gray-600 text-gray-400">
				{{ $t('inputs.placeholder_search_files') }}
			</span>
			<span class="font-bold xl:text-sm text-xs dark:text-gray-600 text-gray-400">
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
