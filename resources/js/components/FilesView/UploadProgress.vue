<template>
    <transition name="info-panel">
        <div v-if="uploadingFilesCount" class="upload-progress">
            <div class="progress-title">
				<span v-if="isProcessingFile">
                    <refresh-cw-icon size="12" class="sync-alt"></refresh-cw-icon>
                    {{ $t('uploading.processing_file') }}
                </span>
				<span v-if="!isProcessingFile && uploadingFilesCount.total === 1">
                    {{ $t('uploading.progress_single_upload', {progress: uploadingFileProgress}) }}
                </span>
				<span v-if="!isProcessingFile && uploadingFilesCount.total > 1">
                    {{ $t('uploading.progress', {current:uploadingFilesCount.current, total: uploadingFilesCount.total, progress: uploadingFileProgress}) }}
                </span>
            </div>
            <ProgressBar :progress="uploadingFileProgress" />
        </div>
    </transition>
</template>

<script>
    import ProgressBar from '@/components/FilesView/ProgressBar'
    import { RefreshCwIcon } from 'vue-feather-icons'
    import {mapGetters} from 'vuex'

    export default {
        name: 'UploadProgress',
        components: {
            RefreshCwIcon,
            ProgressBar,
        },
        computed: {
            ...mapGetters([
                'uploadingFileProgress',
                'uploadingFilesCount',
                'isProcessingFile',
            ])
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

        .progress-title {
            font-weight: 700;
            text-align: center;

            span {
                @include font-size(14);
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .progress-bar {
            background: $dark_mode_foreground;
        }
    }
</style>
