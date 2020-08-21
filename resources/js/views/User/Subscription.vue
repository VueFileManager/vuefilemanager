<template>
    <PageTab :is-loading="isLoading">
        <PageTabGroup v-if="subscription && !isLoading">
            <FormLabel>
                {{ $t('user_subscription.title') }}
            </FormLabel>

            <!--Info about active subscription-->
            <div v-if="! subscription.data.attributes.canceled" class="state active">
                <ListInfo class="list-info">
                    <ListInfoItem class="list-item" :title="$t('user_subscription.plan')"
                                  :content="subscription.data.attributes.name + ' - ' + subscription.data.attributes.capacity_formatted"/>
                    <ListInfoItem class="list-item" :title="$t('user_subscription.billed')" :content="$t('admin_page_user.subscription.interval_mo')"/>
                    <ListInfoItem class="list-item" :title="$t('user_subscription.status')" :content="status"/>
                    <ListInfoItem class="list-item capitalize" :title="$t('user_subscription.created_at')" :content="subscription.data.attributes.created_at"/>
                    <ListInfoItem class="list-item capitalize" :title="$t('user_subscription.renews_at')" :content="subscription.data.attributes.ends_at"/>
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
                    <ListInfoItem class="list-item" :title="$t('user_subscription.plan')" :content="subscription.data.attributes.name + ' - ' + subscription.data.attributes.capacity_formatted"/>
                    <ListInfoItem class="list-item" :title="$t('user_subscription.status')" :content="status"/>
                    <ListInfoItem class="list-item capitalize" :title="$t('user_subscription.canceled_at')" :content="subscription.data.attributes.canceled_at"/>
                    <ListInfoItem class="list-item capitalize" :title="$t('user_subscription.ends_at')" :content="subscription.data.attributes.ends_at"/>
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
        <InfoBox v-if="! subscription && !isLoading">
            <p>{{ $t('user_subscription.empty') }}</p>
        </InfoBox>
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
            cancelButtonText() {
                return this.isConfirmedCancel ? this.$t('popup_share_edit.confirm') : this.$t('user_subscription.cancel_plan')
            },
            cancelButtonStyle() {
                return this.isConfirmedCancel ? 'danger-solid' : 'secondary'
            },
            resumeButtonText() {
                return this.isConfirmedResume ? this.$t('popup_share_edit.confirm') : this.$t('user_subscription.resume_plan')
            },
            resumeButtonStyle() {
                return this.isConfirmedResume ? 'theme-solid' : 'secondary'
            },
            status() {
                if (this.subscription.data.attributes.incomplete) {
                    return this.$t('global.incomplete')
                }
                if (this.subscription.data.attributes.canceled) {
                    return this.$t('global.canceled')
                }
                if (this.subscription.data.attributes.active) {
                    return this.$t('global.active')
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
                if (!this.isConfirmedCancel) {
                    this.isConfirmedCancel = true
                    return
                }

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

                        events.$emit('alert:open', {
                            emoji: '👍',
                            title: this.$t('popup_subscription_cancel.title'),
                            message: this.$t('popup_subscription_cancel.message'),
                            buttonStyle: 'theme',
                            button: this.$t('popup_subscription_cancel.button')
                        })
                    })
                    .catch(() => {
                        events.$emit('alert:open', {
                            title: this.$t('popup_error.title'),
                            message: this.$t('popup_error.message'),
                        })
                    })
                    .finally(() => {

                        // End deleting spinner button
                        this.isDeleting = false
                        this.isLoading = false
                        this.isConfirmedCancel = false
                    })
            },
            resumeSubscription() {

                // Set confirm button
                if (! this.isConfirmedResume) {
                    this.isConfirmedResume = true
                    return
                }

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

                        events.$emit('alert:open', {
                            emoji: '👍',
                            title: this.$t('popup_subscription_resumed.title'),
                            message: this.$t('popup_subscription_resumed.message'),
                            buttonStyle: 'theme',
                            button: this.$t('popup_subscription_resumed.button')
                        })
                    })
                    .catch(() => {
                        events.$emit('alert:open', {
                            title: this.$t('popup_error.title'),
                            message: this.$t('popup_error.message'),
                        })
                    })
                    .finally(() => {

                        // End deleting spinner button
                        this.isResuming = false
                        this.isLoading = false
                        this.isConfirmedResume = false
                    })
            },
            fetchSubscriptionDetail() {
                axios.get('/api/user/subscription')
                    .then(response => {

                        if (response.status == 204) {
                            this.subscription = undefined
                        }

                        if (response.status == 200) {
                            this.subscription = response.data
                        }

                    }).finally(() => {
                        this.isLoading = false
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
</style>
