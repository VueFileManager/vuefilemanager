<template>
    <div class="flex h-screen items-center justify-center">
        <div>
            <ItemGrid v-if="sharedFile" :entry="sharedFile" :highlight="true" :mobile-handler="true" />

            <ButtonBase @click.native="download" button-style="theme">
                {{ $t('page_shared.download_file') }}
            </ButtonBase>
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
        ...mapGetters(['sharedDetail', 'sharedFile']),
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
