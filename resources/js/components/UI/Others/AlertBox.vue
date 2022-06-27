<template>
    <div
		class="mb-6 flex items-center rounded-xl p-5 shadow-card"
		:class="{
			'dark:bg-green-700/30 bg-green-200': color === 'green',
			'dark:bg-rose-700/30 bg-rose-200': color === 'rose',
		}"
	>
		<refresh-cw-icon
			v-if="isLoading"
			size="18"
			class="vue-feather mr-4 shrink-0 animate-spin"
			:class="{
				'text-green-700 dark:text-green-500': color === 'green',
				'text-rose-700 dark:text-rose-500': color === 'rose',
			}"
		/>
        <alert-octagon-icon
			v-if="!isLoading"
			size="18"
			class="vue-feather mr-4 shrink-0"
			:class="{
				'text-green-700 dark:text-green-500': color === 'green',
				'text-rose-700 dark:text-rose-500': color === 'rose',
			}"
		/>
        <p
			class="text-sm"
			:class="{
				'text-green-700 dark:text-green-500': color === 'green',
				'text-rose-700 dark:text-rose-500': color === 'rose',
			}"
		>
            <slot />
        </p>
    </div>
</template>
<script>
	import {AlertOctagonIcon, RefreshCwIcon} from "vue-feather-icons";

	export default {
		name: 'AlertBox',
		props: [
			'isLoading',
			'color',
		],
		components: {
			AlertOctagonIcon,
			RefreshCwIcon,
		}
	}
</script>
