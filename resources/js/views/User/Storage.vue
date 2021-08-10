<template>
    <PageTab v-if="storage">
        <PageTabGroup>
            <FormLabel>{{ $t('storage.sec_capacity') }}</FormLabel>
            <StorageItemDetail type="disk" :title="$t('storage.total_used', {used: storage.attributes.used})" :percentage="storage.attributes.percentage" :used="$t('storage.total_capacity', {capacity: storage.attributes.capacity})"/>
        </PageTabGroup>
        <PageTabGroup>
            <FormLabel>{{ $t('storage.sec_details') }}</FormLabel>
            <StorageItemDetail type="images" :title="$t('storage.images')" :percentage="storage.meta.images.percentage" :used="storage.meta.images.used" />
            <StorageItemDetail type="videos" :title="$t('storage.videos')" :percentage="storage.meta.videos.percentage" :used="storage.meta.videos.used" />
            <StorageItemDetail type="audios" :title="$t('storage.audios')" :percentage="storage.meta.audios.percentage" :used="storage.meta.audios.used" />
            <StorageItemDetail type="documents" :title="$t('storage.documents')" :percentage="storage.meta.documents.percentage" :used="storage.meta.documents.used" />
            <StorageItemDetail type="others" :title="$t('storage.others')" :percentage="storage.meta.others.percentage" :used="storage.meta.others.used" />
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
    import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
    import PageTab from '/resources/js/components/Others/Layout/PageTab'
    import StorageItemDetail from '/resources/js/components/Others/StorageItemDetail'
    import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
    import SectionTitle from '/resources/js/components/Others/SectionTitle'
    import PageHeader from '/resources/js/components/Others/PageHeader'
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import axios from 'axios'

    export default {
        name: 'Storage',
        components: {
            PageTabGroup,
            FormLabel,
            PageTab,
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
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

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

    .dark-mode {


    }

</style>
