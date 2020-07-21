<template>
    <WidgetWrapper :icon="icon" :title="title">
        <div class="widget-value">
            <span>{{ value }}</span>
        </div>
        <router-link :to="{name: linkRoute}" class="footer-link">
            <span class="content">{{ linkName }}</span>
            <chevron-right-icon size="16"></chevron-right-icon>
        </router-link>
    </WidgetWrapper>
</template>

<script>
    import WidgetWrapper from '@/components/Admin/WidgetWrapper'
    import { UsersIcon, StarIcon, HardDriveIcon, ChevronRightIcon } from 'vue-feather-icons'

    export default {
        name: 'WidgetTotals',
        props: ['icon', 'title', 'value', 'linkRoute', 'linkName'],
        components: {
            ChevronRightIcon,
            WidgetWrapper,
            HardDriveIcon,
            StarIcon,
            UsersIcon
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .widget-value {
        margin-top: 10px;
        margin-bottom: 30px;

        span {
            @include font-size(38);
            font-weight: 800;
        }
    }

    .footer-link {
        display: flex;
        align-items: center;

        polyline {
            stroke: $theme;
        }

        .content {
            @include font-size(12);
            font-weight: 700;
            margin-right: 5px;
        }
    }

    @media only screen and (max-width: 1190px) {
        .widget-value {

            span {
                @include font-size(30);
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .widget-value {

            span {
                color: $dark_mode_text_primary;
            }
        }

        .footer-link {

            .content {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
