<template>
    <AuthContentWrapper ref="auth" class="h-screen">
        <!--Invitation info-->
        <AuthContent name="invitation" :visible="false">
            <Headline
                v-if="invitation"
                :title="$t('invite_to_join_team_folder')"
                :description="
                    $t('user_invite_you_into_team_folder', {
                        name: invitation.data.relationships.inviter.data.attributes.name,
                    })
                "
            >
                <div class="relative mx-auto mb-10 w-24 text-center">
                    <VueFolderTeamIcon class="inline-block w-28" />
                    <MemberAvatar
                        :member="invitation.data.relationships.inviter"
                        class="absolute -bottom-2.5 -right-6"
                        :is-border="true"
                        :size="38"
                    />
                </div>
            </Headline>

            <p
                v-if="invitation && !invitation.data.attributes.isExistedUser"
                class="mx-auto mb-4 max-w-md text-sm text-gray-500"
                v-html="
                    $t('register_with_email_and_get_folder', {
                        email: invitation.data.attributes.email,
                    })
                "
            ></p>

            <AuthButton
                @click.native="acceptInvitation"
                class="mb-12 w-full justify-center md:w-min"
                icon="chevron-right"
                :text="acceptButton"
                :loading="isLoading"
                :disabled="isLoading"
            />

            <i18n path="or_decline_your_invitation" tag="div" class="block">
                <b @click="declineInvitation" class="text-theme cursor-pointer font-bold">
                    {{ $t('decline') }}
                </b>
            </i18n>
        </AuthContent>

        <!--Accepted invitation screen-->
        <AuthContent v-if="invitation" name="accepted" :visible="false">
            <Headline
                :title="$t('you_are_successfully_joined')"
                :description="$t('proceed_to_participate_with_team')"
            />

            <router-link replace v-if="!config.isAuthenticated" :to="{ name: 'SignIn' }">
                <AuthButton
                    class="mb-12 w-full justify-center md:w-min"
                    icon="chevron-right"
                    :text="$t('proceed_to_your_account')"
                />
            </router-link>

            <router-link
                replace
                v-if="config.isAuthenticated"
                :to="{
                    name: 'SharedWithMe',
                    params: { id: invitation.data.attributes.parent_id },
                }"
            >
                <AuthButton
                    class="mb-12 w-full justify-center md:w-min"
                    icon="chevron-right"
                    :text="$t('go_to_team_folder')"
                />
            </router-link>
        </AuthContent>

        <!--Denied invitation screen-->
        <AuthContent name="denied" :visible="false">
            <Headline
                :title="$t('you_denied_invitation')"
                :description="$t('proceed_to_your_account')"
            />

            <router-link :to="{ name: 'SignIn' }">
                <AuthButton
                    class="mb-12 w-full justify-center md:w-min"
                    icon="chevron-right"
                    :text="$t('proceed_to_your_account')"
                />
            </router-link>
        </AuthContent>

        <!--Used or Expired invitation screen-->
        <AuthContent name="expired" :visible="false">
            <Headline
                :title="$t('invitation_used')"
                :description="$t('invitation_used_description')"
            />

            <router-link replace v-if="!config.isAuthenticated" :to="{ name: 'SignIn' }">
                <AuthButton class="mb-12 w-full justify-center md:w-min" icon="chevron-right" :text="$t('log_in')" />
            </router-link>

            <router-link replace v-if="config.isAuthenticated" :to="{ name: 'SharedWithMe' }">
                <AuthButton
                    class="mb-12 w-full justify-center md:w-min"
                    icon="chevron-right"
                    :text="$t('go_to_your_shared_folders')"
                />
            </router-link>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate/dist/vee-validate.full'
import VueFolderTeamIcon from '../../components/Icons/VueFolderTeamIcon'
import AuthContentWrapper from '../../components/Layout/AuthPages/AuthContentWrapper'
import AuthContent from '../../components/Layout/AuthPages/AuthContent'
import MemberAvatar from '../../components/UI/Others/MemberAvatar'
import AuthButton from '../../components/UI/Buttons/AuthButton'
import Spinner from '../../components/UI/Others/Spinner'
import Headline from '../../components/UI/Labels/LogoHeadline'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'Invitation',
    components: {
        AuthContentWrapper,
        ValidationProvider,
        ValidationObserver,
        VueFolderTeamIcon,
        MemberAvatar,
        AuthContent,
        AuthButton,
        Headline,
        Spinner,
    },
    computed: {
        ...mapGetters(['config']),
        acceptButton() {
            return this.invitation && this.invitation.data.attributes.isExistedUser
                ? this.$t('accept_invitation')
                : this.$t('accept_and_register')
        },
    },
    data() {
        return {
            isLoading: false,
            invitation: undefined,
            isUsed: false,
        }
    },
    methods: {
        acceptInvitation() {
            this.isLoading = true

            axios
                .put(`/api/teams/invitations/${this.$router.currentRoute.params.id}`)
                .then(() => {
                    if (this.invitation.data.attributes.isExistedUser) {
                        this.goToAuthPage('accepted')
                    } else {
                        this.$router.push({ name: 'SignUp' })
                    }
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
                .finally(() => (this.isLoading = false))
        },
        declineInvitation() {
            this.isLoading = true

            axios
                .delete(`/api/teams/invitations/${this.$router.currentRoute.params.id}`)
                .then(() => {
                    this.goToAuthPage('denied')
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
                .finally(() => (this.isLoading = false))
        },
        goToAuthPage(slug) {
            this.$refs.auth.$children.forEach((page) => {
                // Hide current step
                page.isVisible = page.$props.name === slug
            })
        },
    },
    created() {
        axios
            .get(`/api/teams/invitations/${this.$router.currentRoute.params.id}`)
            .then((response) => {
                this.invitation = response.data
                this.goToAuthPage('invitation')
            })
            .catch((error) => {
                if (error.response.status === 410) {
                    this.goToAuthPage('expired')
                } else {
                    this.$isSomethingWrong()
                }
            })
    },
}
</script>
