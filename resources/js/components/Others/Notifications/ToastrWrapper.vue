<template>
    <div id="toastr-wrapper">
        <ToastrItem :item="item" v-for="(item, i) in notifications" :key="i"/>
    </div>
</template>

<script>
    import ToastrItem from '@/components/Others/Notifications/ToastrItem'
    import {events} from "@/bus";

    export default {
        components: {
            ToastrItem,
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
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

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
        top: 30px;
        z-index: 10;
    }

    @media only screen and (max-width: 690px) {

        #toastr-wrapper {
            top: initial;
            right: 15px;
            left: 15px;
            bottom: 15px;
        }
    }

</style>
