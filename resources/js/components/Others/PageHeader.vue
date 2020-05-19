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
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .page-header {
        display: flex;
        align-items: center;
        background: white;
        z-index: 9;
        max-width: 700px;
        width: 100%;
        margin: 20px auto 30px;

        .title {
            @include font-size(18);
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
            margin: 0;

            .title {
                @include font-size(18);
            }
        }
    }

    @media only screen and (max-width: 690px) {
        .page-header {
            display: none;
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
