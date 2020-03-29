<template>
    <div class="storage-size" v-if="app">
        <div class="storage-info">
            <span class="title">Storage</span>
            <span class="size">{{ app.storage.used }} of {{ app.storage.capacity }} Used</span>
        </div>
        <ProgressBar :progress="app.storage.percentage" :class="{'is-exceeded': app.storage.percentage > 100}"/>
    </div>
</template>

<script>
    import ProgressBar from '@/components/VueFileManagerComponents/FilesView/ProgressBar'
    import { mapGetters } from 'vuex'

    export default {
        name: 'StorageSize',
        components: {
            ProgressBar,
        },
        computed: {
            ...mapGetters(['app']),
        },
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .storage-size {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        margin: 15px;
        border-radius: 8px;

        .storage-info {
            display: flex;
            flex-wrap: nowrap;
            align-items: center;

            span {
                width: 100%;
                white-space: nowrap;
            }

            .title {
                @include font-size(14);
                font-weight: 700;
                color: $text;
            }

            .size {
                @include font-size(12);
                text-align: right;
                color: $text-muted;
            }
        }
    }

    .progress-bar {

        &.is-exceeded /deep/ span {
            background: $danger;
        }
    }

    @media only screen and (max-width: 690px) {

        .storage-size {
            position: relative;
            margin-bottom: 0;

            .size {
                @include font-size(10);
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .storage-size {

            .storage-info .title {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
