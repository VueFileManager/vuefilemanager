<template>
    <div id="user-settings" v-if="app">

        <MobileHeader/>
        <PageHeader :title="$router.currentRoute.meta.title"/>

        <div class="content-page">
            <SectionTitle>Storage Capacity</SectionTitle>
            <StorageItemDetail type="disk" :title="'Total used ' + storage.used" :percentage="storage.percentage" :used="storage.capacity"/>

            <SectionTitle>Capacity Used Details</SectionTitle>
            <StorageItemDetail type="images" title="Images" :percentage="storageDetails.images.percentage" :used="storageDetails.images.used" />
            <StorageItemDetail type="videos" title="Videos" :percentage="storageDetails.videos.percentage" :used="storageDetails.videos.used" />
            <StorageItemDetail type="documents" title="Documents" :percentage="storageDetails.documents.percentage" :used="storageDetails.documents.used" />
            <StorageItemDetail type="others" title="Others" :percentage="storageDetails.others.percentage" :used="storageDetails.others.used" />
        </div>
    </div>
</template>

<script>
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import PageHeader from '@/components/Others/PageHeader'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            StorageItemDetail,
            SectionTitle,
            MobileHeader,
            PageHeader,
        },
        computed: {
            ...mapGetters(['app']),
        },
        data() {
            return {
                storage: undefined,
                storageDetails: undefined
            }
        },
        created() {
            axios.get('/api/user/storage')
                .then(response => {
                    this.storage = response.data.data.attributes
                    this.storageDetails = response.data.data.relationships
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    #user-settings {
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

        #user-settings {

            .content-page {
                padding-left: 15px;
                padding-right: 15px;
            }
        }
    }

    @media (prefers-color-scheme: dark) {


    }

</style>
