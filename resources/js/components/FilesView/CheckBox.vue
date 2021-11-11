<template>
	<div>
		<div
			class="w-5 h-5 flex items-center justify-center rounded-md"
			:class="{'bg-theme': isClicked, 'dark:bg-dark-foreground bg-light-background': !isClicked}"
			@click="changeState"
		>
			<CheckIcon v-if="isClicked" class="vue-feather text-white" size="17" />
		</div>
	</div>
</template>

<script>
import {CheckIcon} from 'vue-feather-icons'

export default {
	name: 'CheckBox',
	props: [
		'isClicked'
	],
	components: {
		CheckIcon
	},
	data() {
		return {
			isSwitched: undefined
		}
	},
	methods: {
		changeState() {
			this.isSwitched = ! this.isSwitched
			this.$emit('input', this.isSwitched)
		}
	},
	mounted() {
		this.isSwitched = this.isClicked
	}
}
</script>
