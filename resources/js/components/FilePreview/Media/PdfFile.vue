<template>
    <div id="pdf-wrapper" :style="{ width: documentSize + '%' }">
        <!--		<pdf :src="pdfData" v-for="i in numPages" :key="i" :resize="true" :page="i" scale="page-width" style="width:100%; margin:0 auto 35px;" id="printable-file" class="pdf-file">
			<template slot="loading">
				<h1>loading content...</h1>
			</template>
		</pdf>-->
    </div>
</template>

<script>
//todo: resolve pdf

import { events } from '../../../bus'
//import pdf from 'pdfvuer'

export default {
    name: 'PdfFile',
    components: {
        //pdf,
    },
    props: ['file'],
    watch: {
        file() {
            this.getPdf()
        },
    },
    data() {
        return {
            pdfData: undefined,
            numPages: 0,
            documentSize: 50,
        }
    },
    methods: {
        getPdf() {
            this.pdfData = undefined
            this.numPages = 0

            let self = this

            //self.pdfData = pdf.createLoadingTask(this.file.data.attributes.file_url);

            //self.pdfData.then(pdf => self.numPages = pdf.numPages);
        },
        getDocumentSize() {
            if (window.innerWidth < 960) {
                this.documentSize = 100
            }

            if (window.innerWidth > 960) {
                this.documentSize = localStorage.getItem('documentSize') ? parseInt(localStorage.getItem('documentSize')) : 50
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

<!--<style src="pdfvuer/dist/pdfvuer.css" lang="css"></style>-->
<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';

#pdf-wrapper {
    border-radius: 8px;
    overflow-y: scroll;
    margin: 0 auto;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
    padding: 40px;

    .pdf-file {
        box-shadow: $light_mode_popup_shadow;
        border-radius: 8px;
        overflow: hidden;
    }
}

@media only screen and (max-width: 960px) {
    #pdf-wrapper {
        border-radius: 0;
        padding: 0;

        .pdf-file {
            box-shadow: none;
            border-radius: 0;
        }
    }
}
</style>
