<template>
    <div
		:class="type"
		class="md:relative md:top-0 md:bottom-0 absolute top-14 bottom-24 left-0 right-0 h-auto overflow-y-auto overflow-x-auto px-6"
	>
        <slot />
    </div>
</template>

<script>
    export default {
		name: 'PopupContent',
		props: [
			'type'
		]
	}
</script>