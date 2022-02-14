<template>
    <AuthContentWrapper ref="auth" class="h-screen">
        <!--Invitation info-->
        <AuthContent name="invitation" :visible="false">
            <Headline
                v-if="invitation"
                :title="$t('Invitation To Join Team Folder')"
                :description="
                    $t('Jane invite you to join with his team into shared team folder', {
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
                    $t('Register account with your email peterpapp@makingcg.com and get access to this Team Folder.', {
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

            <div class="block">
                Or
                <b @click="declineInvitation" class="text-theme cursor-pointer font-bold">
                    {{ $t('decline') }}
                </b>
                your invitation.
            </div>
        </AuthContent>

        <!--Accepted invitation screen-->
        <AuthContent v-if="invitation" name="accepted" :visible="false">
            <Headline
                :title="$t('You are successfully joined')"
                :description="$t('You can now proceed to your account and participate in team folder')"
            />

            <router-link replace v-if="!config.isAuthenticated" :to="{ name: 'SignIn' }">
                <AuthButton
                    class="mb-12 w-full justify-center md:w-min"
                    icon="chevron-right"
                    :text="$t('Proceed to your account')"
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
                    :text="$t('Go to Team Folder')"
                />
            </router-link>
        </AuthContent>

        <!--Denied invitation screen-->
        <AuthContent name="denied" :visible="false">
            <Headline
                :title="$t('You are successfully denied invitation')"
                :description="$t('You can now proceed to your account')"
            />

            <router-link :to="{ name: 'SignIn' }">
                <AuthButton
                    class="mb-12 w-full justify-center md:w-min"
                    icon="chevron-right"
                    :text="$t('Proceed to your account')"
                />
            </router-link>
        </AuthContent>

        <!--Used or Expired invitation screen-->
        <AuthContent name="expired" :visible="false">
            <Headline
                :title="$t('Your invitation has been used')"
                :description="$t('We are sorry but this invitation was used previously')"
            />

            <router-link replace v-if="!config.isAuthenticated" :to="{ name: 'SignIn' }">
                <AuthButton class="mb-12 w-full justify-center md:w-min" icon="chevron-right" :text="$t('Log In')" />
            </router-link>

            <router-link replace v-if="config.isAuthenticated" :to="{ name: 'SharedWithMe' }">
                <AuthButton
                    class="mb-12 w-full justify-center md:w-min"
                    icon="chevron-right"
                    :text="$t('Go to your shared folders')"
                />
            </router-link>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate/dist/vee-validate.full'
import VueFolderTeamIcon from '../../components/FilesView/Icons/VueFolderTeamIcon'
import AuthContentWrapper from '../../components/Auth/AuthContentWrapper'
import AuthContent from '../../components/Auth/AuthContent'
import MemberAvatar from '../../components/FilesView/MemberAvatar'
import AuthButton from '../../components/Auth/AuthButton'
import Spinner from '../../components/FilesView/Spinner'
import Headline from '../Auth/Headline'
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
                ? this.$t('Accept Invitation')
                : this.$t('Accept and Register Account')
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
						this.$router.push({name: 'SignUp'})
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
