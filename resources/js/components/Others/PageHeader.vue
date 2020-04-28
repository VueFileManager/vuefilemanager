<template>
    <div class="page-header" @click="goHome">
        <div class="icon" v-if="isSmallAppSize">
            <FontAwesomeIcon icon="chevron-left" />
        </div>
        <div class="content">
            <h1 class="title">{{ title }}</h1>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'PageHeader',
        props: [
            'title', 'description'
        ],
        computed: {
            ...mapGetters(['appSize']),
            isSmallAppSize() {
                return this.appSize === 'small'
            }
        },
        methods: {
            goHome() {
                if (this.isSmallAppSize) {
                    events.$emit('show:sidebar')
                    this.$router.push({name: 'Files'})
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import "@assets/app.scss";

    .page-header {
        display: flex;
        align-items: center;
        padding: 20px 30px;
        background: white;
        position: sticky;
        top: 0;
        z-index: 9;

        .title {
            @include font-size(22);
            font-weight: 700;
            color: $text;
        }

        .icon {
            @include font-size(16);
            margin-right: 15px;
            cursor: pointer;
        }
    }

    @media only screen and (max-width: 960px) {

        .page-header {
            padding: 20px 15px;

            .title {
                @include font-size(18);
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .page-header {
            background: $dark_mode_background;

            .title {
                color: $dark_mode_text_primary;
            }

            .icon path {
                fill: $theme;
            }
        }
    }
</style>
