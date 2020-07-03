<template>
    <section id="viewport">

        <ContentSidebar>

            <!--Settings-->
            <ContentGroup title="Menu" class="navigator">
                <div class="menu-list-wrapper vertical">
                    <router-link replace :to="{name: 'Profile'}" class="menu-list-item link">
                        <div class="icon">
                            <user-icon size="17"></user-icon>
                        </div>
                        <div class="label">
                            {{ $t('menu.profile') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'Storage'}" class="menu-list-item link">
                        <div class="icon">
                            <hard-drive-icon size="17"></hard-drive-icon>
                        </div>
                        <div class="label">
                            {{ $t('menu.storage') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'Password'}" class="menu-list-item link">
                        <div class="icon">
                            <lock-icon size="17"></lock-icon>
                        </div>
                        <div class="label">
                            {{ $t('menu.password') }}
                        </div>
                    </router-link>
                </div>
            </ContentGroup>
            <ContentGroup title="Subscription" class="navigator" v-if="canShowSubscriptionSettings">
                <div class="menu-list-wrapper vertical">
                    <router-link replace :to="{name: 'Subscription'}" class="menu-list-item link">
                        <div class="icon">
                            <cloud-icon size="17"></cloud-icon>
                        </div>
                        <div class="label">
                            Subscription
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'PaymentMethods'}" class="menu-list-item link">
                        <div class="icon">
                            <credit-card-icon size="17"></credit-card-icon>
                        </div>
                        <div class="label">
                            Payment Cards
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'Invoice'}" class="menu-list-item link">
                        <div class="icon">
                            <file-text-icon size="17"></file-text-icon>
                        </div>
                        <div class="label">
                            Invoices
                        </div>
                    </router-link>
                </div>
            </ContentGroup>
        </ContentSidebar>

        <div id="single-page" v-if="user">
            <div id="page-content" class="medium-width" v-if="! isLoading">
                <MobileHeader :title="$router.currentRoute.meta.title"/>

                <div class="content-page">

                    <!--User thumbnail-->
                    <div class="page-detail-headline">
                        <div class="user-thumbnail">
                            <div class="avatar">
                                <UserImageInput
                                        v-model="avatar"
                                        :avatar="user.data.attributes.avatar"
                                />
                            </div>
                            <div class="info">
                                <b class="name">
                                    {{ user.data.attributes.name }}
                                    <ColorLabel v-if="config.isSaaS" :color="subscriptionColor">
                                        {{ subscriptionStatus }}
                                    </ColorLabel>
                                </b>
                                <span class="email">{{ user.data.attributes.email }}</span>
                            </div>
                        </div>
                        <div v-if="config.isSaaS" class="headline-actions">
                            <router-link :to="{name: 'UpgradePlan'}" v-if="! user.relationships.subscription || (user.relationships.subscription && ! user.relationships.subscription.data.attributes.is_highest)">
                                <ButtonBase class="upgrade-button" button-style="secondary" type="button">
                                    Upgrade Plan
                                </ButtonBase>
                            </router-link>
                        </div>
                    </div>

                    <!--Router Content-->
                    <router-view :user="user" />
                </div>
            </div>
            <div id="loader" v-if="isLoading">
                <Spinner></Spinner>
            </div>
        </div>
    </section>

</template>

<script>
    import ContentSidebar from '@/components/Sidebar/ContentSidebar'
    import ContentGroup from '@/components/Sidebar/ContentGroup'
    import UserImageInput from '@/components/Others/UserImageInput'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
    import { mapGetters } from 'vuex'
    import axios from 'axios'
    import {
        CreditCardIcon,
        HardDriveIcon,
        FileTextIcon,
        CloudIcon,
        UserIcon,
        LockIcon,
    } from 'vue-feather-icons'

    export default {
        name: 'Settings',
        components: {
            ContentSidebar,
            ContentGroup,
            CloudIcon,
            ButtonBase,
            CreditCardIcon,
            UserImageInput,
            FileTextIcon,
            MobileHeader,
            ColorLabel,
            PageHeader,
            Spinner,
            HardDriveIcon,
            UserIcon,
            LockIcon,
        },
        computed: {
            ...mapGetters(['user', 'config']),
            subscriptionStatus() {
                return this.user.data.attributes.subscription ? 'Premium' : 'Free'
            },
            subscriptionColor() {
                return this.user.data.attributes.subscription ? 'green' : 'purple'
            },
            canShowSubscriptionSettings() {
                return this.config.isSaaS
            }
        },
        data() {
            return {
                avatar: undefined,
                isLoading: false,
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .page-detail-headline {
        display: flex;
        justify-content: space-between;
        margin-bottom: 50px;
    }

    .user-thumbnail {
        display: flex;
        align-items: center;
        cursor: pointer;

        .avatar {
            margin-right: 20px;

            img {
                line-height: 0;
                width: 62px;
                height: 62px;
                border-radius: 12px;
            }
        }

        .info {

            .name {
                display: block;
                @include font-size(17);
                line-height: 1;
            }

            .email {
                color: $text-muted;
                @include font-size(14);
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .user-thumbnail {

            .info {

                .email {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }

    @media only screen and (max-width: 690px) {

        .page-detail-headline {
            display: block;

            .headline-actions {
                margin-top: 20px;

                .upgrade-button {
                    width: 100%;
                }
            }
        }
    }
</style>
