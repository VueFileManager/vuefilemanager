<template>
    <div v-if="notifications.length > 0" class="fixed bottom-4 right-4 left-4 z-50 md:left-auto lg:bottom-8 lg:right-8">
        <ToasterItem :item="item" v-for="(item, i) in notifications" :key="i" />
    </div>
</template>

<script>
import ToasterItem from './ToasterItem'
import { events } from '../../../bus'

export default {
    components: {
        ToasterItem,
    },
    data() {
        return {
            notifications: [],
        }
    },
    created() {
        events.$on('toaster', (notification) => this.notifications.push(notification))
    },
}
</script>
