<template>
    <div v-if="canBePreview" class="mb-4 block w-full">
        <!--Image-->
		<img
			v-if="singleFile.data.type === 'image'"
			:src="imageSrc"
			class="w-full overflow-hidden rounded-lg object-cover shadow-lg"
			@error="replaceByOriginal"
			alt=""
		>

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
            if (val.data.type === 'image') {
				this.getImageSrc()
            }
        },
    },
	data() {
		return {
			imageSrc: undefined,
		}
	},
	methods: {
		replaceByOriginal() {
			this.imageSrc = this.singleFile.data.attributes.file_url
		},
		getImageSrc() {
			this.imageSrc = this.singleFile.data.attributes.mimetype === 'svg'
				? this.singleFile.data.attributes.file_url
				: this.singleFile.data.attributes.thumbnail.lg
		},
	},
	created() {
		if (this.singleFile.data.type === 'image')
			this.getImageSrc()
	}
}
</script>
