<template>
    <AuthContentWrapper ref="auth">

        <!--Log In by Email-->
        <AuthContent name="invitation" :visible="false">
            <Headline
				v-if="invitation"
				:title="$t('Invitation To Join Team Folder')"
				:description="$t('{name} invite you to join with his team into shared team folder', {name: invitation.data.relationships.inviter.data.attributes.name})"
			>
				<div class="text-center mb-10 w-24 mx-auto relative">
					<VueFolderTeamIcon class="inline-block w-28" />
					<MemberAvatar
						class="absolute -bottom-2.5 -right-6"
						:is-border="true"
						:size="38"
						:member="invitation.data.relationships.inviter"
					/>
				</div>
			</Headline>

			<div class="form inline-form">
                <AuthButton @click.native="acceptInvitation" icon="chevron-right" :text="$t('Accept Invitation')" :loading="isLoading" :disabled="isLoading" />
			</div>

            <div class="additional-link font-bold">
				Or
                <b @click="declineInvitation" class="text-theme font-bold">
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

			<router-link replace v-if="! config.isAuthenticated" :to="{name: 'SignIn'}">
                <AuthButton icon="chevron-right" :text="$t('Proceed to your account')"/>
            </router-link>

			<router-link replace v-if="config.isAuthenticated" :to="{name: 'SharedWithMe', params: {id: invitation.data.attributes.parent_id}}">
                <AuthButton icon="chevron-right" :text="$t('Go to Team Folder')"/>
            </router-link>
        </AuthContent>

        <!--Denied invitation screen-->
        <AuthContent name="denied" :visible="false">

            <Headline
				:title="$t('You are successfully denied invitation')"
				:description="$t('You can now proceed to your account')"
			/>

			<router-link :to="{name: 'SignIn'}">
                <AuthButton icon="chevron-right" :text="$t('Proceed to your account')"/>
            </router-link>
        </AuthContent>

        <!--Used or Expired invitation screen-->
        <AuthContent name="expired" :visible="false">

            <Headline
				:title="$t('Your invitation has been used')"
				:description="$t('We are sorry but this invitation was used previously')"
			/>

			<router-link replace v-if="! config.isAuthenticated" :to="{name: 'SignIn'}">
                <AuthButton icon="chevron-right" :text="$t('Log In')"/>
            </router-link>

			<router-link replace v-if="config.isAuthenticated" :to="{name: 'SharedWithMe'}">
                <AuthButton icon="chevron-right" :text="$t('Go to your shared folders')"/>
            </router-link>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
	import {ValidationObserver, ValidationProvider} from 'vee-validate/dist/vee-validate.full'
	import VueFolderTeamIcon from "../../components/FilesView/Icons/VueFolderTeamIcon"
    import AuthContentWrapper from '/resources/js/components/Auth/AuthContentWrapper'
	import AuthContent from '/resources/js/components/Auth/AuthContent'
	import MemberAvatar from "../../components/FilesView/MemberAvatar"
	import AuthButton from '/resources/js/components/Auth/AuthButton'
	import Spinner from '/resources/js/components/FilesView/Spinner'
	import Headline from "../Auth/Headline"
	import {mapGetters} from 'vuex'
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
            ...mapGetters([
				'config'
			]),
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

				axios.put(`/api/teams/invitations/${this.$router.currentRoute.params.id}`)
					.then(() => {
						this.goToAuthPage('accepted')
					})
					.catch(() => {
						this.$isSomethingWrong()
					})
					.finally(() => this.isLoading = false)
			},
			declineInvitation() {
				this.isLoading = true

				axios.delete(`/api/teams/invitations/${this.$router.currentRoute.params.id}`)
					.then(() => {
						this.goToAuthPage('denied')
					})
					.catch(() => {
						this.$isSomethingWrong()
					})
					.finally(() => this.isLoading = false)
			},
            goToAuthPage(slug) {
                this.$refs.auth.$children.forEach(page => {

                    // Hide current step
                    page.isVisible = false

                    if (page.$props.name === slug) {

                        // Go to next step
                        page.isVisible = true
                    }
                })
            },
        },
        created() {
			axios.get(`/api/teams/invitations/${this.$router.currentRoute.params.id}`)
				.then(response => {
					this.invitation = response.data
					this.goToAuthPage('invitation')
				})
				.catch(error => {
					if (error.response.status === 410) {
						this.goToAuthPage('expired')
					} else {
						this.$isSomethingWrong()
					}
				})
        }
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_auth-form';
    @import '/resources/sass/vuefilemanager/_auth';

    .spinner-wrapper {
        width: 100%;
        height: 50px;
        position: relative;
        top: 50px;
    }
</style>
