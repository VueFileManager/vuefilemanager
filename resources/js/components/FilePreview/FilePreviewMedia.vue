<template>
    <div v-if="currentFile" class="absolute lg:top-[66px] top-[56px] left-0 right-0 bottom-0 select-none">

		<!--Arrow navigation-->
        <div v-if="!$isMobile() && files.length > 1" class="">
            <div @click.prevent="prev" class="fixed top-1/2 left-0 p-3 cursor-pointer z-20">
                <chevron-left-icon size="20" />
            </div>

            <div @click.prevent="next" class="fixed top-1/2 right-0 p-3 cursor-pointer z-20">
                <chevron-right-icon size="20" />
            </div>
        </div>

        <!--Desktop preview-->
        <div v-if="!$isMobile() && (isAudio || isImage || isVideo || isPDF)" class="w-full h-full flex justify-center items-center">

			<!--Show PDF-->
            <PdfFile v-if="isPDF" :file="currentFile" />

            <!--Show Audio, Video and Image-->
            <div class="w-full h-full flex items-center justify-center">
                <Audio v-if="isAudio" :file="currentFile"/>
                <Video v-if="isVideo" :file="currentFile" />
                <ImageFile v-if="isImage" :file="currentFile" class="max-w-[100%] max-h-[100%] self-center mx-auto" />
            </div>
        </div>

		<!--Mobile Preview-->
		<div v-if="$isMobile() && (isAudio || isImage || isVideo || isPDF)" @scroll="checkGroupInView" id="group-box" ref="scrollBox" class="flex gap-6 snap-x snap-mandatory overflow-x-auto h-full">
			<div v-for="(file, i) in files" :key="i" :id="`group-${file.data.id}`" class="w-screen h-full flex items-center justify-center snap-center shrink-0 relative">
                <ImageFile v-if="isImage" :file="file" class="max-w-[100%] max-h-[100%] self-center mx-auto"/>
				<Audio v-if="isAudio" :file="file"/>
                <Video v-if="isVideo" :file="file" />
				<PdfFile v-if="isPDF" :file="file" />
			</div>
		</div>
    </div>
</template>

<script>
import { ChevronLeftIcon, ChevronRightIcon } from 'vue-feather-icons'
import ToolbarButton from '../FilesView/ToolbarButton'
import ImageFile from './Media/ImageFile'
import PdfFile from './Media/PdfFile'
import Audio from './Media/Audio'
import Video from './Media/Video'
import Spinner from '../FilesView/Spinner'
import { mapGetters } from 'vuex'
import { events } from '../../bus'

export default {
    name: 'FilePreviewMedia',
    components: {
        ChevronRightIcon,
        ChevronLeftIcon,
        ToolbarButton,
        ImageFile,
        PdfFile,
        Spinner,
        Audio,
        Video,
    },
    computed: {
        ...mapGetters(['fastPreview', 'clipboard', 'entries']),
        currentFile() {
            return this.fastPreview ? this.fastPreview : this.files[Math.abs(this.currentIndex) % this.files.length]
        },
        isPDF() {
            return this.currentFile.data.attributes.mimetype === 'pdf'
        },
        isVideo() {
            return this.currentFile.data.type === 'video'
        },
        isAudio() {
            return this.currentFile.data.type === 'audio'
        },
        isImage() {
            return this.currentFile.data.type === 'image'
        },
    },
    data() {
        return {
            currentIndex: 0,
            files: [],
        }
    },
    watch: {
        files() {
            if (this.files.length === 0) events.$emit('file-preview-wrapper:hide')
        },
        currentFile() {
            if (this.clipboard[0]) {
                this.$store.commit('CLIPBOARD_CLEAR')
                this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.currentFile)
            }
        },
        clipboard() {
            if (!this.clipboard[0]) {
                this.currentIndex -= 1

                this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.currentFile)

                this.files = []
            }
        },
        data(newValue, oldValue) {
            if (newValue !== oldValue) {
                this.files = []
            }
        },
    },
    methods: {
		checkGroupInView: _.debounce(function () {
			this.files.forEach((file, index) => {
				let element = document.getElementById(`group-${file.data.id}`).getBoundingClientRect()
				let scrollBox = document.getElementById('group-box').getBoundingClientRect()

				// Get video
				const video = document.querySelector(`#group-${file.data.id} video`);

				// Pause video when playing
				if (video && !video.paused) {
					video.pause()
				}

				// Check if the group is in the viewport of group-box
				if (element.left === scrollBox.left) {
					this.currentIndex = index
				}
			})
		}, 100),
        getFilesForView() {
            let requestedFile = this.clipboard[0]

            this.entries.map(element => {
                if (requestedFile.data.attributes.mimetype === 'pdf') {
                    if (element.data.attributes.mimetype === 'pdf') this.files.push(element)
                } else {
                    if (element.data.type === requestedFile.data.type) this.files.push(element)
                }
            })

            this.files.forEach((element, index) => {
                if (element.data.id === this.clipboard[0].data.id) {
					this.currentIndex = index
                }
            })

			// Scroll to the selected item
			if (this.$isMobile()) {
				this.$nextTick(() => {
					let element = document.getElementById(`group-${this.files[this.currentIndex].data.id}`)

					this.$refs.scrollBox.scrollLeft = element.getBoundingClientRect().left
				})
			}
        },
        next() {
            if (!this.files.length > 1) return

            this.currentIndex += 1

            if (this.currentIndex > this.files.length - 1) {
                this.currentIndex = 0
            }
        },
        prev() {
            if (!this.files.length > 1) return

            this.currentIndex -= 1

            if (this.currentIndex < 0) {
                this.currentIndex = this.files.length - 1
            }
        },
    },
    created() {
        events.$on('file-preview:next', () => this.next())
        events.$on('file-preview:prev', () => this.prev())

        this.getFilesForView()
    },
}
</script>
