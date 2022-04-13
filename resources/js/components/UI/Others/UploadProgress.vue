<template>
    <transition name="info-panel">
        <div v-if="fileQueue.length > 0" class="upload-progress">
            <div class="progress-title">
                <!--Is processing-->
                <span v-if="isProcessingFile" class="flex items-center justify-center">
                    <refresh-cw-icon size="12" class="sync-alt text-theme" />
                    {{ $t('uploading.processing_file') }}
                </span>

                <!--Multi file upload-->
                <span v-if="!isProcessingFile && fileQueue.length > 0">
                    {{
                        $t('uploading.progress', {
                            current: filesInQueueUploaded,
                            total: filesInQueueTotal,
                            progress: uploadingProgress,
                        })
                    }}
                </span>
            </div>
            <div class="progress-wrapper">
                <ProgressBar :progress="uploadingProgress" />
                <span @click="cancelUpload" :title="$t('uploading.cancel')" class="cancel-icon">
                    <x-icon size="16" @click="cancelUpload" class="hover-text-theme"></x-icon>
                </span>
            </div>
        </div>
    </transition>
</template>

<script>
import ProgressBar from './ProgressBar'
import { RefreshCwIcon, XIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'
import { events } from '../../../bus'

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
        ]),
    },
    methods: {
        cancelUpload() {
            events.$emit('cancel-upload')
        },
    },
}
</script>

<style scoped lang="scss">
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.sync-alt {
    animation: spin 1s linear infinite;
    margin-right: 5px;

    polyline,
    path {
        color: inherit;
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
    position: relative;
    z-index: 1;

    .progress-wrapper {
        display: flex;

        .cancel-icon {
            cursor: pointer;
            padding: 0 7px 0 13px;

            &:hover {
                line {
                    color: inherit;
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

.dark {
    .progress-bar {
        background: $dark_mode_foreground;
    }
}
</style>
