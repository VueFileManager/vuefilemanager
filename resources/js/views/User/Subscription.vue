<template>
    <PageTab>
        <PageTabGroup v-if="subscription">

            <!--Info about active subscription-->
            <div v-if="! subscription.canceled" class="state active">
                <ListInfo class="list-info">
                    <ListInfoItem class="list-item" title="Plan" :content="subscription.name + ' - ' + subscription.capacity_formatted"/>
                    <ListInfoItem class="list-item" title="Billed" content="Monthly"/>
                    <ListInfoItem class="list-item" title="Status" :content="status"/>
                    <ListInfoItem class="list-item" title="Created At" :content="subscription.created_at"/>
                    <ListInfoItem class="list-item" title="Renews At" :content="subscription.ends_at"/>
                </ListInfo>
                <div class="cancel-plan">
                    <ButtonBase
                            @click.native="cancelSubscription"
                            :button-style="cancelButtonStyle"
                            class="cancel-button">
                        {{ cancelButtonText }}
                    </ButtonBase>
                </div>
            </div>

            <!--Info about canceled subscription-->
            <div v-if="subscription.canceled" class="state canceled">
                <ListInfo class="list-info">
                    <ListInfoItem class="list-item" title="Plan" :content="subscription.name"/>
                    <ListInfoItem class="list-item" title="Status" :content="status"/>
                    <ListInfoItem class="list-item" title="Canceled At" :content="subscription.canceled_at"/>
                    <ListInfoItem class="list-item" title="Ends At" :content="subscription.ends_at"/>
                </ListInfo>
            </div>
        </PageTabGroup>
        <PageTabGroup v-else>
            You don't have any subscription yet.
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
            cancelButtonText() {
                return this.isConfirmedCancel ? this.$t('popup_share_edit.confirm') : 'Cancel Plan'
            },
            cancelButtonStyle() {
                return this.isConfirmedCancel ? 'danger-solid' : 'danger'
            },
            subscription() {
                return this.$store.getters.user.relationships.subscription
                    ? this.$store.getters.user.relationships.subscription.data.attributes
                    : undefined
            },
            status() {
                if (this.subscription.canceled) {
                    return 'Canceled'
                }
                if (this.subscription.active) {
                    return 'Active'
                }
            }
        },
        data() {
            return {
                isConfirmedCancel: false,
                isDeleting: false,
            }
        },
        methods: {
            cancelSubscription() {

                // Set confirm button
                if (! this.isConfirmedCancel) {

                    this.isConfirmedCancel = true
                } else {

                    // Start deleting spinner button
                    this.isDeleting = true

                    // Send delete request
                    axios
                        .post('/api/subscription/cancel')
                        .then(() => {

                            // Update user data
                            this.$store.dispatch('getAppData')

                            // End deleting spinner button
                            this.isDeleting = false

                            events.$emit('alert:open', {
                                emoji: '👍',
                                title: 'Subscription Was Canceled',
                                message: 'You\'ll continue to have access to the features you\'ve paid for until the end of your billing cycle.',
                                buttonStyle: 'theme',
                                button: 'I\'m done'
                            })
                        })
                        .catch(() => {

                            // End deleting spinner button
                            this.isDeleting = false
                        })
                }
            }
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
