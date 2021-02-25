<template>
    <transition name="info-panel">
        <div v-if="fileQueue.length > 0" class="upload-progress">
            <div class="progress-title">

                <!--Is processing-->
				<span v-if="isProcessingFile">
                    <refresh-cw-icon size="12" class="sync-alt"></refresh-cw-icon>
                    {{ $t('uploading.processing_file') }}
                </span>

                <!--Multi file upload-->
				<span v-if="!isProcessingFile && fileQueue.length > 0">
                    {{ $t('uploading.progress', {current:filesInQueueUploaded, total: filesInQueueTotal, progress: uploadingProgress}) }}
                </span>
            </div>
            <div class="progress-wrapper">
                <ProgressBar :progress="uploadingProgress" />
                <span @click="cancelUpload" :title="$t('uploading.cancel')" class="cancel-icon">
                    <x-icon size="16" @click="cancelUpload"></x-icon>
                </span>
            </div>
        </div>
    </transition>
</template>

<script>
    import ProgressBar from '@/components/FilesView/ProgressBar'
    import { RefreshCwIcon, XIcon } from 'vue-feather-icons'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'UploadProgress',
        components: {
            RefreshCwIcon,
            ProgressBar,
            XIcon,
        },
        computed: {
            ...mapGetters([
                'filesInQueueUploaded',
                'filesInQueueTotal',
                'uploadingProgress',
                'isProcessingFile',
                'fileQueue',
            ])
        },
        methods: {
            cancelUpload() {
                events.$emit('cancel-upload')
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .sync-alt {
        animation: spin 1s linear infinite;
        margin-right: 5px;

        polyline, path {
            stroke: $theme;
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .info-panel-enter-active,
    .info-panel-leave-active {
        transition: all 0.3s ease;
    }

    .info-panel-enter,
    .info-panel-leave-to {
        opacity: 0;
        transform: translateY(-100%);
    }

    .upload-progress {
        width: 100%;
        padding-bottom: 10px;
        position: relative;
        z-index: 1;

        .progress-wrapper {
            display: flex;

            .cancel-icon {
                cursor: pointer;
                padding: 0 13px;

                &:hover {

                    line {
                        stroke: $theme;
                    }
                }
            }
        }

        .progress-title {
            font-weight: 700;
            text-align: center;

            span {
                @include font-size(14);
            }
        }
    }

    @media only screen and (max-width: 690px) {

        .upload-progress {

            .progress-wrapper .cancel-icon {
                padding: 0 9px;
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .progress-bar {
            background: $dark_mode_foreground;
        }
    }
</style>
