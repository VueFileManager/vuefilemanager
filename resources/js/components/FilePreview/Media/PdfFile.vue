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
        <pdf
            :src="pdfData"
            v-for="i in numPages"
            :key="i"
            :resize="true"
            :page="i"
            scale="page-width"
            id="printable-file"
            class="mx-auto mb-6 w-full overflow-hidden md:rounded-xl md:shadow-lg"
			@loading="documentLoaded"
        />
    </div>
</template>

<script>
import Spinner from "../../UI/Others/Spinner"
import { events } from '../../../bus'
import pdf from 'pdfvuer'

export default {
    name: 'PdfFile',
    components: {
		Spinner,
        pdf,
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
            pdfData: undefined,
            documentSize: 50,
			isLoading: true,
            numPages: 0,
        }
    },
    methods: {
		documentLoaded() {
			this.isLoading = false
		},
        getPdf() {
            this.pdfData = undefined
            this.numPages = 0

            let self = this

            self.pdfData = pdf.createLoadingTask(this.file.data.attributes.file_url)

            self.pdfData.then((pdf) => (self.numPages = pdf.numPages))
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

<style src="pdfvuer/dist/pdfvuer.css" lang="css"></style>
