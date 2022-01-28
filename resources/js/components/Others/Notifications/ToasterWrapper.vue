<template>
    <div v-if="notifications.length > 0" class="fixed lg:bottom-8 bottom-4 lg:right-8 right-4 md:left-auto left-4 z-50">
        <ToasterItem :item="item" v-for="(item, i) in notifications" :key="i"/>
    </div>
</template>

<script>
    import ToasterItem from "./ToasterItem";
    import {events} from '../../../bus'

    export default {
        components: {
            ToasterItem,
        },
        data() {
            return {
                notifications: []
            }
        },
        created() {
            events.$on('toaster', notification => this.notifications.push(notification))
        }
    }
</script>
