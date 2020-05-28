<template>
    <header class="mobile-header">

        <!-- Go back-->
        <div @click="goBack" class="go-back">
            <chevron-left-icon size="17" class="icon"></chevron-left-icon>
        </div>

        <!--Folder Title-->
        <div class="location-name">{{ title }}</div>

        <!--More Actions-->
        <div @click="showMobileNavigation" class="mobile-menu">
            <menu-icon size="17" class="icon"></menu-icon>
        </div>
    </header>
</template>

<script>
    import {events} from '@/bus'

    import {
        ChevronLeftIcon,
        MenuIcon,
    } from 'vue-feather-icons'

    export default {
        name: 'MenuBar',
        props: [
            'title'
        ],
        components: {
            ChevronLeftIcon,
            MenuIcon,
        },
        methods: {
            showMobileNavigation() {
                events.$emit('show:mobile-navigation')
            },
            goBack() {
                this.$router.back();
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .mobile-header {
        padding: 10px 0;
        text-align: center;
        background: white;
        position: sticky;
        display: none;
        z-index: 6;
        top: 0;

        > div {
            flex-grow: 1;
            align-self: center;
            white-space: nowrap;
        }

        .go-back {
            text-align: left;
        }

        .location-name {
            line-height: 1;
            text-align: center;
            width: 100%;
            vertical-align: middle;
            @include font-size(15);
            color: $text;
            font-weight: 700;
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }

        .mobile-menu {
            text-align: right;
        }

        .icon {
            vertical-align: middle;
            margin-top: -4px;
        }
    }

    @media only screen and (max-width: 690px) {
        .mobile-header {
            display: flex;
            margin-bottom: 15px;
        }
    }

    @media (prefers-color-scheme: dark) {
        .mobile-header {
            background: $dark_mode_background;

            .location-name {
                color: $dark_mode_text_primary;
            }
        }
    }

</style>
