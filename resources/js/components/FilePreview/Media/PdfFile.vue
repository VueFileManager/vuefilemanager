<template>
    <div
        class="absolute bottom-0 top-0 left-0 right-0 z-10 mx-auto overflow-y-auto rounded-xl md:p-5"
        :style="{ width: documentSize + '%' }"
    >
		<div
			v-if="isLoading"
			class="mx-auto fixed left-0 right-0 top-1/2 translate-y-5 w-full z-10"
		>
			<Spinner />
		</div>
        <VuePdfEmbed
            v-if="pdfSource"
            :key="pdfSource"
            :source="pdfSource"
            text-layer
            annotation-layer
            id="printable-file"
            class="pdf-document mx-auto w-full"
            @rendered="documentLoaded"
            @loading-failed="documentLoaded"
        />
    </div>
</template>

<script>
import Spinner from "../../UI/Others/Spinner.vue"
import { events } from '../../../bus'
import VuePdfEmbed from 'vue-pdf-embed'
import 'vue-pdf-embed/dist/styles/annotationLayer.css'
import 'vue-pdf-embed/dist/styles/textLayer.css'

export default {
    name: 'PdfFile',
    components: {
		Spinner,
        VuePdfEmbed,
    },
    props: [
		'file'
	],
    watch: {
        file() {
            this.getPdf()
			this.isLoading = true
        },
    },
    data() {
        return {
            pdfSource: undefined,
            documentSize: 50,
			isLoading: true,
        }
    },
    methods: {
		documentLoaded() {
			this.isLoading = false
		},
        getPdf() {
            this.pdfSource = this.file.data.attributes.file_url
        },
        getDocumentSize() {
            if (window.innerWidth < 960) {
                this.documentSize = 100
            }

            if (window.innerWidth > 960) {
                this.documentSize = localStorage.getItem('documentSize')
                    ? parseInt(localStorage.getItem('documentSize'))
                    : 50
            }
        },
        zoomIn() {
            if (this.documentSize < 100) {
                this.documentSize += 10
                localStorage.setItem('documentSize', this.documentSize)
            }
        },
        zoomOut() {
            if (this.documentSize > 40) {
                this.documentSize -= 10
                localStorage.setItem('documentSize', this.documentSize)
            }
        },
    },
    created() {
        this.getDocumentSize()
        this.getPdf()

        events.$on('document-zoom:in', () => this.zoomIn())
        events.$on('document-zoom:out', () => this.zoomOut())
    },
}
</script>

<style scoped>
/* Same look as the previous per-page pdfvuer components (mb-6, rounded, shadow) */
.pdf-document :deep(.vue-pdf-embed__page) {
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 1.5rem;
    width: 100%;
    overflow: hidden;
}

@media (min-width: 768px) {
    .pdf-document :deep(.vue-pdf-embed__page) {
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }
}
</style>
