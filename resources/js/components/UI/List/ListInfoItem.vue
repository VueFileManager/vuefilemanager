<template>
    <div class="mb-4">
        <small class="text-theme block text-xs font-bold">
            {{ title }}
        </small>
        <b v-if="content" class="inline-block text-sm font-bold">
            {{ content }}
        </b>

        <slot v-if="$slots.default" />
    </div>
</template>

<script>
export default {
    name: 'ListInfoItem',
    props: ['content', 'title'],
}
</script>
