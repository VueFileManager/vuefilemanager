<template>
    <ul class="option-group py-1">
        <slot></slot>
    </ul>
</template>

<script>
    export default {
        name: 'OptionGroup'
    }
</script>

<style scoped lang="scss">
	.option-group {

		&:first-child {
			padding-top: 0 !important;
		}

		&:last-child {
			padding-bottom: 0 !important;
		}
	}
</style>
