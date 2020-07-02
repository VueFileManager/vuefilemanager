<template>
    <PageTab :is-loading="isLoading">
        <PageTabGroup v-if="subscription">
            <FormLabel>Subscription Plan</FormLabel>

            <!--Info about active subscription-->
            <div v-if="! subscription.data.attributes.canceled" class="state active">
                <ListInfo class="list-info">
                    <ListInfoItem class="list-item" title="Plan" :content="subscription.data.attributes.name + ' - ' + subscription.data.attributes.capacity_formatted"/>
                    <ListInfoItem class="list-item" title="Billed" content="Monthly"/>
                    <ListInfoItem class="list-item" title="Status" :content="status"/>
                    <ListInfoItem class="list-item" title="Created At" :content="subscription.data.attributes.created_at"/>
                    <ListInfoItem class="list-item" title="Renews At" :content="subscription.data.attributes.ends_at"/>
                </ListInfo>
                <div class="plan-action">
                    <ButtonBase
                            :disabled="isDeleting"
                            @click.native="cancelSubscription"
                            :button-style="cancelButtonStyle"
                            class="confirm-button">
                        {{ cancelButtonText }}
                    </ButtonBase>
                </div>
            </div>

            <!--Info about canceled subscription-->
            <div v-if="subscription.data.attributes.canceled" class="state canceled">
                <ListInfo class="list-info">
                    <ListInfoItem class="list-item" title="Plan" :content="subscription.data.attributes.name"/>
                    <ListInfoItem class="list-item" title="Status" :content="status"/>
                    <ListInfoItem class="list-item" title="Canceled At" :content="subscription.data.attributes.canceled_at"/>
                    <ListInfoItem class="list-item" title="Ends At" :content="subscription.data.attributes.ends_at"/>
                </ListInfo>
                <div class="plan-action">
                    <ButtonBase
                            :disabled="isResuming"
                            @click.native="resumeSubscription"
                            :button-style="resumeButtonStyle"
                            class="confirm-button">
                        {{ resumeButtonText }}
                    </ButtonBase>
                </div>
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
    import FormLabel from '@/components/Others/Forms/FormLabel'
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
            FormLabel,
            ListInfo,
            PageTab,
        },
        computed: {
            cancelButtonText() {
                return this.isConfirmedCancel ? this.$t('popup_share_edit.confirm') : 'Cancel Plan'
            },
            cancelButtonStyle() {
                return this.isConfirmedCancel ? 'danger-solid' : 'secondary'
            },
            resumeButtonText() {
                return this.isConfirmedResume ? this.$t('popup_share_edit.confirm') : 'Resume Plan'
            },
            resumeButtonStyle() {
                return this.isConfirmedResume ? 'theme-solid' : 'secondary'
            },
            status() {
                if (this.subscription.data.attributes.canceled) {
                    return 'Canceled'
                }
                if (this.subscription.data.attributes.active) {
                    return 'Active'
                }
            }
        },
        data() {
            return {
                subscription: undefined,
                isConfirmedCancel: false,
                isConfirmedResume: false,
                isDeleting: false,
                isResuming: false,
                isLoading: true,
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
                    this.isLoading = true

                    // Send delete request
                    axios
                        .post('/api/subscription/cancel')
                        .then(() => {

                            // Update user data
                            this.$store.dispatch('getAppData').then(() => {
                                this.fetchSubscriptionDetail()
                            })

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
                            this.isLoading = false
                        })
                }
            },
            resumeSubscription() {

                // Set confirm button
                if (! this.isConfirmedResume) {

                    this.isConfirmedResume = true
                } else {

                    // Start deleting spinner button
                    this.isResuming = true
                    this.isLoading = true

                    // Send delete request
                    axios
                        .post('/api/subscription/resume')
                        .then(() => {

                            // Update user data
                            this.$store.dispatch('getAppData').then(() => {
                                this.fetchSubscriptionDetail()
                            })

                            // End deleting spinner button
                            this.isResuming = false

                            events.$emit('alert:open', {
                                emoji: '👍',
                                title: 'Subscription Was Resumed',
                                message: 'Your subscription was re-activated, and they will be billed on the original billing cycle.',
                                buttonStyle: 'theme',
                                button: 'That\'s awesome!'
                            })
                        })
                        .catch(() => {

                            // End deleting spinner button
                            this.isResuming = false
                            this.isLoading = false
                        })
                }
            },
            fetchSubscriptionDetail() {
                axios.get('/api/user/subscription')
                    .then(response => {

                        if (response.status == 204) {
                            this.subscription = undefined
                            this.isLoading = false
                        }

                        if (response.status == 200) {
                            this.subscription = response.data
                            this.isLoading = false
                        }

                    })
            }
        },
        created() {
            this.fetchSubscriptionDetail()
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .plan-action {
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
