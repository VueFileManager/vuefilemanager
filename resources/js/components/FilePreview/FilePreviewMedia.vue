<template>
	<div v-if="currentFile" class="file-preview-wrapper">

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
			<PdfFile v-if="isPDF" :file="currentFile"/>

            <!--Show Audio, Video and Image-->
            <div v-if="isAudio || isImage || isVideo" class="file-wrapper">
				<Audio v-if="isAudio" :file="currentFile"/>
				<Video v-if="isVideo" :file="currentFile"/>
				<ImageFile v-if="isImage" :file="currentFile"/>
			</div>
		</div>
	</div>
</template>

<script>
import {ChevronLeftIcon, ChevronRightIcon} from 'vue-feather-icons'
import ToolbarButton from "../FilesView/ToolbarButton";
import ImageFile from "./Media/ImageFile";
import PdfFile from "./Media/PdfFile";
import Audio from "./Media/Audio";
import Video from "./Media/Video";
import Spinner from "../FilesView/Spinner";
import {mapGetters} from 'vuex'
import {events} from "../../bus";

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
        ...mapGetters([
            'fastPreview',
            'clipboard',
            'entries',
        ]),
        currentFile() {
            return this.fastPreview
				? this.fastPreview
				: this.files[Math.abs(this.currentIndex) % this.files.length]
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
        }
    },
    data() {
        return {
            currentIndex: 0,
            files: [],
        }
    },
    watch: {
        files() {
            if (this.files.length === 0)
                events.$emit('file-preview-wrapper:hide')
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
        getFilesForView() {
            let requestedFile = this.clipboard[0]

            this.entries.map(element => {

                if (requestedFile.data.attributes.mimetype === 'pdf') {

                    if (element.data.attributes.mimetype === 'pdf')
                        this.files.push(element)

                } else {

                    if (element.data.type === requestedFile.data.type)
                        this.files.push(element)
                }
            })

            this.files.forEach((element, index) => {
                if (element.data.id === this.clipboard[0].data.id) {
                    this.currentIndex = index
                }
            })
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
		}
    },
    created() {
		events.$on('file-preview:next', () => this.next())
        events.$on('file-preview:prev', () => this.prev())

        this.getFilesForView()
    }
}
</script>

<style lang="scss">
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

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
        z-index: 2;
    }

    .next {
        right: 0;
    }

    .prev {
        left: 0;
    }
}

.file-preview-wrapper {
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
    }
}

@media only screen and (max-width: 960px) {

    .file-preview-wrapper {
        top: 53px;
    }
}


.dark {

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