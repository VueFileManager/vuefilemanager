<template>
    <PageTab>
        <PageTabGroup v-if="subscription">

            <!--Info about active subscription-->
            <div v-if="! subscription.canceled" class="state active">
                <ListInfo class="list-info">
                    <ListInfoItem class="list-item" title="Plan" :content="subscription.attributes.name + ' - ' + subscription.attributes.capacity_formatted"/>
                    <ListInfoItem class="list-item" title="Billed" content="Monthly"/>
                    <ListInfoItem class="list-item" title="Status" :content="status"/>
                    <ListInfoItem class="list-item" title="Created At" :content="subscription.attributes.created_at"/>
                    <ListInfoItem class="list-item" title="Renews At" :content="subscription.attributes.ends_at"/>
                </ListInfo>
            </div>

            <!--Info about canceled subscription-->
            <div v-if="subscription.attributes.canceled" class="state canceled">
                <ListInfo class="list-info">
                    <ListInfoItem class="list-item" title="Plan" :content="subscription.attributes.name"/>
                    <ListInfoItem class="list-item" title="Status" :content="status"/>
                    <ListInfoItem class="list-item" title="Canceled At" :content="subscription.attributes.canceled_at"/>
                    <ListInfoItem class="list-item" title="Ends At" :content="subscription.attributes.ends_at"/>
                </ListInfo>
            </div>
        </PageTabGroup>
        <PageTabGroup v-else>
            User don't have any subscription yet.
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import ListInfoItem from '@/components/Others/ListInfoItem'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageTab from '@/components/Others/Layout/PageTab'
    import ListInfo from '@/components/Others/ListInfo'
    import {ExternalLinkIcon} from "vue-feather-icons"
    import { mapGetters } from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'UserSubscription',
        components: {
            ExternalLinkIcon,
            DatatableWrapper,
            ListInfoItem,
            PageTabGroup,
            ButtonBase,
            ListInfo,
            PageTab,
        },
        computed: {
            status() {
                if (this.subscription.attributes.canceled) {
                    return 'Canceled'
                }
                if (this.subscription.attributes.active) {
                    return 'Active'
                }
            }
        },
        data() {
            return {
                subscription: undefined,
            }
        },
        created() {
            axios.get('/api/users/' + this.$route.params.id + '/subscription')
                .then(response => {
                    this.subscription = response.data.data
                    this.isLoading = false
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .cancel-plan {
        margin-top: 10px;
    }

    .list-info {
        display: flex;
        flex-wrap: wrap;

        .list-item {
            flex: 0 0 50%;
        }
    }

    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
