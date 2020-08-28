<template>
    <PageTab :is-loading="isLoading" class="form-fixed-width">
        <PageTabGroup v-if="subscription && !isLoading">
            <FormLabel>
                {{ $t('user_subscription.title') }}
            </FormLabel>

            <!--Info about active subscription-->
            <div v-if="! subscription.attributes.canceled" class="state active">
                <ListInfo class="list-info">
                    <ListInfoItem class="list-item" :title="$t('user_subscription.plan')"
                                  :content="subscription.attributes.name + ' - ' + subscription.attributes.capacity_formatted"/>
                    <ListInfoItem class="list-item" :title="$t('user_subscription.billed')" :content="$t('admin_page_user.subscription.interval_mo')"/>
                    <ListInfoItem class="list-item" :title="$t('user_subscription.status')" :content="status"/>
                    <ListInfoItem class="list-item capitalize" :title="$t('user_subscription.created_at')" :content="subscription.attributes.created_at"/>
                    <ListInfoItem class="list-item capitalize" :title="$t('user_subscription.renews_at')" :content="subscription.attributes.ends_at"/>
                </ListInfo>
            </div>

            <!--Info about canceled subscription-->
            <div v-if="subscription.attributes.canceled" class="state canceled">
                <ListInfo class="list-info">
                    <ListInfoItem class="list-item" :title="$t('user_subscription.plan')" :content="subscription.attributes.name + ' - ' + subscription.attributes.capacity_formatted"/>
                    <ListInfoItem class="list-item" :title="$t('user_subscription.status')" :content="status"/>
                    <ListInfoItem class="list-item capitalize" :title="$t('user_subscription.canceled_at')" :content="subscription.attributes.canceled_at"/>
                    <ListInfoItem class="list-item capitalize" :title="$t('user_subscription.ends_at')" :content="subscription.attributes.ends_at"/>
                </ListInfo>
            </div>
        </PageTabGroup>
        <PageTabGroup v-if="! subscription && !isLoading">
            <InfoBox>
                <p>{{ $t('admin_page_user.subscription.empty') }}</p>
            </InfoBox>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import ListInfoItem from '@/components/Others/ListInfoItem'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import ListInfo from '@/components/Others/ListInfo'
    import {ExternalLinkIcon} from "vue-feather-icons"
    import {mapGetters} from 'vuex'
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
            FormLabel,
            ListInfo,
            InfoBox,
            PageTab,
        },
        computed: {
            status() {
                if (this.subscription.attributes.incomplete) {
                    return this.$t('global.incomplete')
                }
                if (this.subscription.attributes.canceled) {
                    return this.$t('global.canceled')
                }
                if (this.subscription.attributes.active) {
                    return this.$t('global.active')
                }
            }
        },
        data() {
            return {
                subscription: undefined,
                isLoading: true,
            }
        },
        created() {
            axios.get('/api/users/' + this.$route.params.id + '/subscription')
                .then(response => {
                    this.subscription = response.data.data
                    this.isLoading = false
                }).catch(error => {
                    if (error.response.status == 404) {
                        this.isLoading = false
                    }
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
</style>
