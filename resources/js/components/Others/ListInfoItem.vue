<template>
    <div class="mb-4">
        <small class="text-theme font-bold text-xs block">
			{{ title }}
		</small>
        <b v-if="content" class="inline-block font-bold text-sm">
			{{ content }}
		</b>

        <slot v-if="$slots.default" />
    </div>
</template>

<script>
    export default {
        name: 'ListInfoItem',
        props: [
			'content',
			'title',
		]
    }
</script>
