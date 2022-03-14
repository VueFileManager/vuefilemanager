<template>
    <div v-if="canBePreview" class="mb-4 block w-full">
        <!--Image-->
		<ImageFile v-if="singleFile.data.type === 'image'" :file="singleFile" class="w-full overflow-hidden rounded-lg object-cover shadow-lg" />

        <!--Audio-->
        <audio
            v-if="singleFile.data.type === 'audio'"
            :src="singleFile.data.attributes.file_url"
            controlsList="nodownload"
            controls
            class="w-full"
        ></audio>

        <!--Video-->
        <video
            v-if="singleFile.data.type === 'video'"
            ref="video"
            class="h-auto w-full overflow-hidden rounded-sm"
            controlsList="nodownload"
            disablePictureInPicture
            playsinline
            controls
        >
            <source :src="singleFile.data.attributes.file_url" type="video/mp4" />
        </video>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { includes } from 'lodash'
import ImageFile from "../FilePreview/Media/ImageFile";

export default {
    name: 'FilePreview',
	components: {
		ImageFile
	},
	computed: {
        ...mapGetters(['clipboard']),
        singleFile() {
            return this.clipboard[0]
        },
        canBePreview() {
            return this.singleFile && !includes(['folder', 'file'], this.singleFile.data.type)
        },
    },
    watch: {
        singleFile: function (val) {
            if (val.data.type === 'video') {
                this.$refs.video.load()
            }
        },
    },
}
</script>
