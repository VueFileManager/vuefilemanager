<template>
    <div class="flex h-screen items-center justify-center">
        <div v-if="file" class="w-full text-center">
            <ItemGrid :entry="file" :mobile-handler="false" />

            <ButtonBase @click.native="$downloadSelection(file)" button-style="theme" class="mx-auto">
                {{ $t('page_shared.download_file') }}
            </ButtonBase>

            <!--Google Adsense banner-->
            <div v-if="config.allowedAdsense" v-html="config.adsenseBanner01" class="mt-5 min-h-[120px]"></div>
        </div>
    </div>
</template>

<script>
import ButtonBase from '../components/FilesView/ButtonBase'
import ItemGrid from '../components/FilesView/ItemGrid'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'SharedSingleItem',
    components: {
        ButtonBase,
        ItemGrid,
    },
    computed: {
        ...mapGetters(['config']),
    },
    data() {
        return {
            file: undefined,
        }
    },
    mounted() {
        axios
            .get(`/api/browse/file/${this.$router.currentRoute.params.token}`)
            .then((response) => {
                this.file = response.data
            })
            .catch((error) => {
                if (error.response.status === 403)
					this.$router.push({ name: 'SharedAuthentication' })
            })
    },
}
</script>
