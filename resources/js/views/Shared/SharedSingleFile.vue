<template>
    <div class="flex h-screen items-center justify-center">
        <div class="w-full text-center">
            <ItemGrid v-if="sharedFile" :entry="sharedFile" :highlight="true" :mobile-handler="true" />

            <ButtonBase @click.native="download" button-style="theme" class="mx-auto">
                {{ $t('page_shared.download_file') }}
            </ButtonBase>

			<!--Google Adsense banner-->
            <div v-if="config.allowedAdsense" v-html="config.adsenseBanner01" class="min-h-[120px] mt-5"></div>
        </div>
    </div>
</template>

<script>
import ButtonBase from '../../components/FilesView/ButtonBase'
import ItemGrid from '../../components/FilesView/ItemGrid'
import { mapGetters } from 'vuex'

export default {
    name: 'SharedSingleItem',
    components: {
        ButtonBase,
        ItemGrid,
    },
    computed: {
        ...mapGetters(['sharedDetail', 'sharedFile', 'config']),
    },
    methods: {
        download() {
            this.$downloadFile(this.sharedFile.data.attributes.file_url, this.sharedFile.data.attributes.name + '.' + this.sharedFile.data.attributes.mimetype)
        },
    },
    mounted() {
        if (!this.sharedDetail) {
            this.$store.dispatch('getShareDetail', this.$route.params.token).then(() => {
                this.$store.dispatch('getSingleFile')
            })
        } else {
            this.$store.dispatch('getSingleFile')
        }
    },
}
</script>
