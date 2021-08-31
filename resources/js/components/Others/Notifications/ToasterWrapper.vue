<template>
    <div id="toastr-wrapper">
        <ToasterItem :item="item" v-for="(item, i) in notifications" :key="i"/>
    </div>
</template>

<script>
    import ToasterItem from '/resources/js/components/Others/Notifications/ToasterItem'
    import {events} from '/resources/js/bus'

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

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .toastr-list {
        transition: all 5s ease;
        display: inline-block;
    }

    .toastr-list-enter,
    .toastr-list-leave-to {
        opacity: 0;
        transform: translateY(-100%);
    }

    .toastr-list-leave-active {
        position: absolute;
    }

    #toastr-wrapper {
        position: absolute;
        right: 30px;
        bottom: 30px;
        z-index: 90;
    }

    @media only screen and (max-width: 690px) {

        #toastr-wrapper {
            right: 15px;
            left: 15px;
            bottom: 15px;
        }
    }

</style>
