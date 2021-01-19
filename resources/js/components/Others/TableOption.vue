<template>
    <div>
        <slot v-if="activeTab"></slot>
    </div>
</template>

<script>

export default {
    name: "TableOption",
    props: ['title', 'icon'],
    data () {
        return {
            activeTab: false
        }
    },
}
</script>

