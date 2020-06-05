<template>
    <PageTab>
        <PageTabGroup>

        </PageTabGroup>
    </PageTab>
</template>

<script>
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import {ExternalLinkIcon} from "vue-feather-icons";
    import axios from 'axios'

    export default {
        name: 'UserSubscription',
        components: {
            PageTabGroup,
            PageTab,
            DatatableWrapper,
            ExternalLinkIcon,
        },
        data() {
            return {
                isLoading: true,
                invoices: undefined,
            }
        },
        created() {
/*            axios.get('/api/user/subscription')
                .then(response => {
                    this.invoices = response.data.data
                    this.isLoading = false
                })*/
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .block-form {
        max-width: 100%;
    }


    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
