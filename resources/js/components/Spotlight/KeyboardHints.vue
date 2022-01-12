<template>
	<div v-if="! $isMobile()" class="flex items-center px-5 pb-2">

		<!--Arrow key navigation-->
		<div class="flex items-center mr-4">
			<ArrowUpIcon size="12" class="vue-feather text-gray-400" />
			<ArrowDownIcon size="12" class="vue-feather text-gray-400" />

			<span class="text-xs ml-1.5 text-gray-400">
				{{ $t('Navigate') }}
			</span>
		</div>

		<!--Submit key-->
		<div class="flex items-center">
			<CornerDownLeftIcon size="12" class="vue-feather text-gray-400" />

			<span class="text-xs ml-1.5 text-gray-400">
				{{ $t('Go') }}
			</span>
		</div>
	</div>
</template>

<script>
	import {
		CornerDownLeftIcon,
		ArrowDownIcon,
		ArrowUpIcon,
	} from 'vue-feather-icons'

	export default {
		name: 'KeyboardHints',
		components: {
			CornerDownLeftIcon,
			ArrowDownIcon,
			ArrowUpIcon,
		}
	}
</script>