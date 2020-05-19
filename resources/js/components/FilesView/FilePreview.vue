<template>
    <div v-if="canBePreview" class="preview">
        <img v-if="fileInfoDetail.type == 'image'" :src="fileInfoDetail.thumbnail" :alt="fileInfoDetail.name" />
        <audio v-else-if="fileInfoDetail.type == 'audio'" :src="fileInfoDetail.file_url" controlsList="nodownload" controls></audio>
        <video v-else-if="fileInfoDetail.type == 'video'" controlsList="nodownload" disablePictureInPicture playsinline controls>
            <source :src="fileInfoDetail.file_url" type="video/mp4">
        </video>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import { includes } from 'lodash'

    export default {
        name: 'FilePreview',
        computed: {
            ...mapGetters(['fileInfoDetail']),
            canBePreview() {
                return this.fileInfoDetail && ! includes([
                    'folder', 'file'
                ], this.fileInfoDetail.type)
            }
        },
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .preview {
        width: 100%;
        display: block;
        margin-bottom: 7px;

        img {
            border-radius: 4px;
            overflow: hidden;
            width: 100%;
            object-fit: cover;
        }

        audio {
            width: 100%;
            &::-webkit-media-controls-panel {
                background-color: $light_background;
            }

            &::-webkit-media-controls-play-button {
                color: $theme;
            }
        }

        video {
            width: 100%;
            height: auto;
            border-radius: 3px;
        }
    }
</style>