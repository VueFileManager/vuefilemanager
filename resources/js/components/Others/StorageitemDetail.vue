<template>
    <article class="detail-storage-item" :class="type">
        <div class="header-storage-item">
            <div class="icon">
                <image-icon v-if="type == 'images'" size="23"></image-icon>
                <video-icon v-if="type == 'videos'" size="23"></video-icon>
                <music-icon v-if="type == 'audios'" size="23"></music-icon>
                <file-text-icon v-if="type == 'documents'" size="23"></file-text-icon>
                <file-icon v-if="type == 'others'" size="23"></file-icon>
                <hard-drive-icon v-if="type == 'disk'" size="23"></hard-drive-icon>
            </div>
            <div class="title">
                <b class="type">{{ title }}</b>
                <span class="total-size">{{ used }}</span>
            </div>
        </div>
        <ProgressBar class="storage-progress" :progress="percentage" />
    </article>
</template>

<script>
    import ProgressBar from '@/components/FilesView/ProgressBar'
    import { ImageIcon, VideoIcon, FileTextIcon, FileIcon, HardDriveIcon, MusicIcon } from 'vue-feather-icons'

    export default {
        name: 'StorageItemDetail',
        props: ['percentage', 'title', 'type', 'used'],
        components: {
            HardDriveIcon,
            FileTextIcon,
            ProgressBar,
            MusicIcon,
            VideoIcon,
            ImageIcon,
            FileIcon,
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .detail-storage-item {
        margin-bottom: 35px;

        &.disk {

            .icon {

                path, line, polyline, rect, circle, polygon {
                    stroke: $theme;
                }
            }

            .storage-progress {

                /deep/ span {
                    background: $theme;
                }
            }
        }

        &.images {

            .icon {

                path, line, polyline, rect, circle, polygon {
                    stroke: $purple;
                }
            }

            .storage-progress {

                /deep/ span {
                    background: $purple;
                }
            }
        }

        &.videos {

            .icon {

                path, line, polyline, rect, circle, polygon {
                    stroke: $yellow;
                }
            }

            .storage-progress {

                /deep/ span {
                    background: $yellow;
                }
            }
        }

        &.audios {

            .icon {

                path, line, polyline, rect, circle, polygon {
                    stroke: $pink;
                }
            }

            .storage-progress {

                /deep/ span {
                    background: $pink;
                }
            }
        }

        &.documents {

            .icon {

                path, line, polyline, rect, circle, polygon {
                    stroke: $red;
                }
            }

            .storage-progress {

                /deep/ span {
                    background: $red;
                }
            }
        }

        &.others {

            .icon {

                path, line, polyline, rect, circle, polygon {
                    stroke: $text;
                }
            }

            .storage-progress {

                /deep/ span {
                    background: $text;
                }
            }
        }
    }

    .header-storage-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 10px;

        .icon {
            width: 35px;
        }

        .type {
            @include font-size(15);
            color: $text;
        }

        .total-size {
            @include font-size(10);
            display: block;
            color: $text-muted;
        }
    }

    @media (prefers-color-scheme: dark) {

        .header-storage-item {
            .type {
                color: $dark_mode_text_primary;
            }

            .total-size {
                color: $dark_mode_text_secondary;
            }
        }

        .detail-storage-item {

            &.others {

                .icon {

                    path, line, polyline, rect, circle, polygon {
                        stroke: lighten($dark_mode_foreground, 15%);
                    }
                }

                .storage-progress {

                    /deep/ span {
                        background: lighten($dark_mode_foreground, 15%);
                    }
                }
            }
        }
    }
</style>
