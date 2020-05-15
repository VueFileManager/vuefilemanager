<template>
    <transition name="info-panel">
        <div v-if="uploadingFilesCount" class="upload-progress">
            <div class="progress-title">
				<span>{{ $t('uploading.progress', {current:uploadingFilesCount.current, total: uploadingFilesCount.total}) }}</span>
            </div>
            <ProgressBar :progress="uploadingFileProgress"/>
        </div>
    </transition>
</template>

<script>
    import ProgressBar from '@/components/FilesView/ProgressBar'
    import {mapGetters} from 'vuex'

    export default {
        name: 'UploadProgress',
        components: {
            ProgressBar,
        },
        computed: {
            ...mapGetters(['uploadingFileProgress', 'uploadingFilesCount'])
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

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
