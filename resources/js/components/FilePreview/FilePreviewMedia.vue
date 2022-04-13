<template>
    <div v-if="currentFile" class="absolute top-[56px] left-0 right-0 bottom-0 select-none lg:top-[66px]">
        <!--Arrow navigation-->
        <div v-if="!$isMobile() && files.length > 1">
            <div @click.prevent="prev" class="fixed top-1/2 left-0 z-20 cursor-pointer p-3">
                <chevron-left-icon size="20" />
            </div>

            <div @click.prevent="next" class="fixed top-1/2 right-0 z-20 cursor-pointer p-3">
                <chevron-right-icon size="20" />
            </div>
        </div>

        <!--Desktop preview-->
        <div
            v-if="!$isMobile() || fastPreview"
            class="flex h-full w-full items-center justify-center"
        >
            <!--Show File-->
			<ItemGrid v-if="isFile && !isPDF" :entry="currentFile" :mobile-handler="false" :can-hover="false"/>

            <!--Show PDF-->
            <PdfFile v-if="isFile && isPDF" :file="currentFile" />

            <!--Show Audio, Video and Image-->
            <div v-if="isAudio || isImage || isVideo" class="flex h-full w-full items-center justify-center">
                <Audio v-if="isAudio" :file="currentFile" />
                <Video v-if="isVideo" :file="currentFile" class="mx-auto max-h-full max-w-[1080px] self-center" />
                <ImageFile v-if="isImage" :file="currentFile" class="mx-auto max-h-[100%] max-w-[100%] self-center" :class="{'file-shadow': !$isMobile()}" id="printable-file" />
            </div>
        </div>

        <!--Mobile Preview-->
        <div
            v-if="($isMobile() && !fastPreview) && (isAudio || isImage || isVideo || isPDF)"
            @scroll="checkGroupInView"
            id="group-box"
            ref="scrollBox"
            class="flex h-full snap-x snap-mandatory gap-6 overflow-x-auto"
        >
            <div
                v-for="(file, i) in files"
                :key="i"
                :id="`group-${file.data.id}`"
                class="relative flex h-full w-screen shrink-0 snap-center items-center justify-center"
            >
                <ImageFile v-if="isImage" :file="file" class="mx-auto max-h-[100%] max-w-[100%] self-center" />
                <Audio v-if="isAudio" :file="file" />
                <Video v-if="isVideo" :file="file" />
                <PdfFile v-if="isPDF" :file="file" />
            </div>
        </div>
    </div>
</template>

<script>
import { ChevronLeftIcon, ChevronRightIcon } from 'vue-feather-icons'
import ToolbarButton from '../UI/Buttons/ToolbarButton'
import ItemGrid from "../UI/Entries/ItemGrid"
import ImageFile from './Media/ImageFile'
import Audio from './Media/Audio'
import Video from './Media/Video'
import Spinner from '../UI/Others/Spinner'
import { mapGetters } from 'vuex'
import { events } from '../../bus'

export default {
    name: 'FilePreviewMedia',
    components: {
		PdfFile: () => import('./Media/PdfFile'),
        ChevronRightIcon,
        ChevronLeftIcon,
        ToolbarButton,
        ImageFile,
		ItemGrid,
        Spinner,
        Audio,
        Video,
    },
    computed: {
        ...mapGetters(['fastPreview', 'clipboard', 'entries']),
        currentFile() {
            return this.fastPreview ? this.fastPreview : this.files[Math.abs(this.currentIndex) % this.files.length]
        },
		isFile() {
            return this.currentFile.data.type === 'file'
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
		currentIndex() {
			this.$store.commit('CLIPBOARD_REPLACE', this.currentFile)
		},
    },
    methods: {
        checkGroupInView: _.debounce(function () {
            this.files.forEach((file, index) => {
                let element = document.getElementById(`group-${file.data.id}`).getBoundingClientRect()
                let scrollBox = document.getElementById('group-box').getBoundingClientRect()

                // Get video
                const video = document.querySelector(`#group-${file.data.id} video`)

                // Pause video when playing
                if (video && !video.paused) {
                    video.pause()
                }

                // Check if the group is in the viewport of group-box
                if (element.left === scrollBox.left) {
                    this.currentIndex = index
                }
            })
        }, 50),
        getFilesForView() {
            let requestedFile = this.clipboard[0]

            this.entries.map((element) => {
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
    mounted() {
        events.$on('file-preview:next', () => this.next())
        events.$on('file-preview:prev', () => this.prev())

		events.$on('file:deleted', id => {
			this.files = this.files.filter(item => item.data.id !== id)

			if (this.files.length === 0) {
				events.$emit('file-preview:hide')
			} else {
				this.$store.commit('CLIPBOARD_REPLACE', this.currentFile)
			}
		})

		if (! this.fastPreview) {
			this.getFilesForView()
		}
    },
	destroyed() {
		events.$off('file:deleted')
	}
}
</script>
