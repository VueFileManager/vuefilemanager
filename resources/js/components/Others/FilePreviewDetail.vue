<template>
    <div v-if="canBePreview" class="preview">
        <img v-if="singleFile.data.type === 'image' && singleFile.data.attributes.thumbnail" :src="singleFile.data.attributes.thumbnail" :alt="singleFile.data.attributes.name" />
        <audio v-else-if="singleFile.data.type === 'audio'" :src="singleFile.data.attributes.file_url" controlsList="nodownload" controls></audio>
        <video v-else-if="singleFile.data.type === 'video'" controlsList="nodownload" disablePictureInPicture playsinline controls>
            <source :src="singleFile.data.attributes.file_url" type="video/mp4">
        </video>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import { includes } from 'lodash'

    export default {
        name: 'FilePreview',
        computed: {
            ...mapGetters([
				'clipboard',
			]),
			singleFile() {
            	return this.clipboard[0]
			},
            canBePreview() {
                return this.singleFile && ! includes([
                    'folder', 'file'
                ], this.singleFile.data.type)
            }
        }
	}
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

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