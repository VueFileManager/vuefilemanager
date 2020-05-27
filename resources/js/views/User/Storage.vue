<template>
    <div id="single-page">
        <div id="page-content" class="full-width" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page">
                <SectionTitle>{{ $t('storage.sec_capacity') }}</SectionTitle>
                <StorageItemDetail type="disk" :title="$t('storage.total_used', {used: storage.attributes.used})" :percentage="storage.attributes.percentage" :used="$t('storage.total_capacity', {capacity: storage.attributes.capacity})"/>

                <SectionTitle>{{ $t('storage.sec_details') }}</SectionTitle>
                <StorageItemDetail type="images" :title="$t('storage.images')" :percentage="storage.meta.images.percentage" :used="storage.meta.images.used" />
                <StorageItemDetail type="videos" :title="$t('storage.videos')" :percentage="storage.meta.videos.percentage" :used="storage.meta.videos.used" />
                <StorageItemDetail type="audios" :title="$t('storage.audios')" :percentage="storage.meta.audios.percentage" :used="storage.meta.audios.used" />
                <StorageItemDetail type="documents" :title="$t('storage.documents')" :percentage="storage.meta.documents.percentage" :used="storage.meta.documents.used" />
                <StorageItemDetail type="others" :title="$t('storage.others')" :percentage="storage.meta.others.percentage" :used="storage.meta.others.used" />
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import PageHeader from '@/components/Others/PageHeader'
    import Spinner from '@/components/FilesView/Spinner'
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            StorageItemDetail,
            SectionTitle,
            MobileHeader,
            PageHeader,
            Spinner,
        },
        data() {
            return {
                isLoading: true,
                storage: undefined,
            }
        },
        created() {
            axios.get('/api/user/storage')
                .then(response => {
                    this.storage = response.data.data
                    this.isLoading = false
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    #single-page {
        overflow: hidden;
        width: 100%;
        height: 100%;
        position: relative;

        .content-page {
            overflow-y: auto;
            height: 100%;
            padding-bottom: 100px;
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
        }
    }

    @media only screen and (max-width: 960px) {

        #single-page {

            .content-page {
                padding-left: 15px;
                padding-right: 15px;
            }
        }
    }

    @media (prefers-color-scheme: dark) {


    }

</style>
