<template>
	<div class="media-full-preview" id="mediaPreview" v-if="fileInfoDetail[0]">

        <!--Arrow navigation-->
        <div v-if="files.length > 1" class="navigation-arrows">
            <div @click.prevent="prev" class="prev">
                <chevron-left-icon size="17" />
            </div>

            <div @click.prevent="next" class="next">
                <chevron-right-icon size="17" />
            </div>
        </div>

        <!--File preview-->
		<div class="file-wrapper-preview">

            <!--Show PDF-->
            <div v-if="isPDF" id="pdf-wrapper">
                <pdf :src="pdfdata" v-for="i in numPages" :key="i" :page="i" scale="page-width" style="width:100%; margin:20px auto;" id="printable-file">
                    <template slot="loading">
                        <h1>loading content...</h1>
                    </template>
                </pdf>
            </div>

            <!--Show Audio, Video and Image-->
            <div v-if="!isPDF" class="file-wrapper">

				<audio
                    v-if="isAudio"
                    :class="{ 'file-shadow': !$isMobile() }"
                    class="file audio"
                    :src="currentFile.file_url"
                    controls>
                </audio>

                <img
                    id="printable-file"
                    v-if="isImage"
                    class="file"
                    :class="{'file-shadow': !$isMobile() }"
                    :src="currentFile.file_url"
                />

                <div class="video-wrapper" v-if="isVideo">
					<video
                        :src="currentFile.file_url"
                        class="video"
                        :class="{'file-shadow': !$isMobile() }"
                        controlsList="nodownload"
                        disablePictureInPicture
                        playsinline
                        controls
                        autoplay
                    />
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import {ChevronLeftIcon, ChevronRightIcon} from 'vue-feather-icons'
import ToolbarButton from '@/components/FilesView/ToolbarButton'
import Spinner from '@/components/FilesView/Spinner'
import {mapGetters} from 'vuex'
import {events} from '@/bus'
import pdf from 'pdfvuer'

export default {
    name: 'MediaFullPreview',
    components: {
        ChevronRightIcon,
        ChevronLeftIcon,
        ToolbarButton,
        Spinner,
        pdf,
    },
    computed: {
        ...mapGetters([
            'fileInfoDetail',
            'data',
        ]),
        currentFile() {
            return this.files[Math.abs(this.currentIndex) % this.files.length]
        },
        isPDF() {
            return this.fileInfoDetail[0].mimetype === 'pdf'
        },
        isVideo() {
            return this.fileInfoDetail[0].type === 'video'
        },
        isAudio() {
            return this.fileInfoDetail[0].type === 'audio'
        },
        isImage() {
            return this.fileInfoDetail[0].type === 'image'
        }
    },
    data() {
        return {
            pdfdata: undefined,
            numPages: 0,
            currentIndex: 0,
            files: [],
        }
    },
    watch: {
        files() {
            if (this.files.length === 0)
                events.$emit('file-preview:hide')
        },
        currentFile() {
            if (this.fileInfoDetail[0]) {
                this.$store.commit('CLEAR_FILEINFO_DETAIL')
                this.$store.commit('GET_FILEINFO_DETAIL', this.currentFile)

                // Init pdf instance
                if (this.fileInfoDetail[0].mimetype === 'pdf') {
                    this.getPdf()
                }
            }
        },
        fileInfoDetail() {
            if (!this.fileInfoDetail[0]) {
                this.currentIndex -= 1

                this.$store.commit('GET_FILEINFO_DETAIL', this.currentFile)

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
        next() {
            if (!this.files.length > 1) return

            this.pdfdata = undefined

            this.currentIndex += 1

            if (this.currentIndex > this.files.length - 1) {
                this.currentIndex = 0
            }
        },
        prev() {
            if (!this.files.length > 1) return

            this.pdfdata = undefined

            this.currentIndex -= 1

            if (this.currentIndex < 0) {
                this.currentIndex = this.files.length - 1
            }
        },
        getPdf() {
            this.pdfdata = undefined
            this.numPages = 0

            let self = this;

            self.pdfdata = pdf.createLoadingTask(this.currentFile.file_url);

            self.pdfdata.then(pdf => self.numPages = pdf.numPages);
        },
        getFilesForView() {
            let requestedFile = this.fileInfoDetail[0]

            this.data.map(element => {

                if (requestedFile.mimetype === 'pdf') {

                    if (element.mimetype === 'pdf')
                        this.files.push(element)

                } else {

                    if (element.type === requestedFile.type)
                        this.files.push(element)
                }
            })

            this.files.forEach((element, index) => {
                if (element.id === this.fileInfoDetail[0].id) {
                    this.currentIndex = index
                }
            })
        },
    },
    created() {
        events.$on('file-preview:next', () => this.next())
        events.$on('file-preview:prev', () => this.prev())

        this.getFilesForView()
    }
}
</script>

<style src="pdfvuer/dist/pdfvuer.css" lang="css"></style>
<style lang="scss">
@import '@assets/vuefilemanager/_variables';
@import '@assets/vuefilemanager/_mixins';

.navigation-arrows {

    .prev, .next {
        cursor: pointer;
        position: absolute;
        top: 45%;
        display: flex;
        justify-content: center;
        color: $text;
        border-radius: 50%;
        text-decoration: none;
        user-select: none;
        filter: drop-shadow(0px 1px 0 rgba(255, 255, 255, 1));
        padding: 10px;
    }

    .next {
        right: 0;
    }

    .prev {
        left: 0;
    }
}

#pdf-wrapper {
    overflow-y: scroll;
    width: 80%;
    margin: 0 auto;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.media-full-preview {
    height: calc(100% - 72px);
    top: 72px;
    position: relative;
    background-color: white;
}

.file-wrapper-preview {
    width: 100%;
    height: 100%;
    padding: 30px 0px;
    display: flex;
    overflow: hidden;
    justify-content: center;
    align-items: center;
    background-color: white;

    .file-wrapper {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;

        .file-shadow {
            box-shadow: 0 8px 40px rgba(17, 26, 52, 0.05);
        }

        .file {
            max-width: 100%;
            max-height: 100%;
            align-self: center;
        }

        .audio {
            border-radius: 28px;
        }

        img {
            border-radius: 4px;
        }

        .video-wrapper {
            max-width: 1080px;
            max-height: 100%;

            @media (min-width: 1200px) {
                & {
                    max-width: 800px;
                }
            }

            @media (min-width: 1920px) and (max-width: 2560px) {
                & {
                    max-width: 1080px;
                }
            }
            @media (min-width: 2560px) and (max-width: 3840px) {
                & {
                    max-width: 1440px;
                }
            }
            @media (min-width: 3840px) {
                & {
                    max-width: 2160px;
                }
            }

            .video {
                max-width: 100%;
                max-height: 100%;
                align-self: center;
            }
        }
    }
}

@media (prefers-color-scheme: dark) {

    .navigation-arrows {
        .prev, .next {
            color: $light-text;
            filter: drop-shadow(0px 1px 0 rgba(17, 19, 20, 1));
        }
    }

    .file-wrapper-preview {
        background-color: $dark_mode_background;

        .file-wrapper {
            .file-shadow {
                box-shadow: 0 8px 40px rgba(0, 0, 0, 0.1);
            }
        }
    }
}
</style>