<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading">
            <MobileHeader/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page">
                <SectionTitle>{{ $t('storage.sec_capacity') }}</SectionTitle>
                <StorageItemDetail type="disk" :title="$t('storage.total_used', {used: storage.used})" :percentage="storage.percentage" :used="$t('storage.total_capacity', {capacity: storage.capacity})"/>

                <SectionTitle>{{ $t('storage.sec_details') }}</SectionTitle>
                <StorageItemDetail type="images" :title="$t('storage.images')" :percentage="storageDetails.images.percentage" :used="storageDetails.images.used" />
                <StorageItemDetail type="videos" :title="$t('storage.videos')" :percentage="storageDetails.videos.percentage" :used="storageDetails.videos.used" />
                <StorageItemDetail type="audios" :title="$t('storage.audios')" :percentage="storageDetails.audios.percentage" :used="storageDetails.audios.used" />
                <StorageItemDetail type="documents" :title="$t('storage.documents')" :percentage="storageDetails.documents.percentage" :used="storageDetails.documents.used" />
                <StorageItemDetail type="others" :title="$t('storage.others')" :percentage="storageDetails.others.percentage" :used="storageDetails.others.used" />
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
                storageDetails: undefined
            }
        },
        created() {
            axios.get('/api/user/storage')
                .then(response => {
                    this.storage = response.data.data.attributes
                    this.storageDetails = response.data.data.relationships
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
