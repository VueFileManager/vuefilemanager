<template>
    <PopupWrapper name="update-team-folder">

        <!--Title-->
        <PopupHeader :title="$t('Edit Team Folder')" icon="user-plus" />

        <!--Content-->
        <PopupContent>

			<!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="item" info="metadata" />

            <!--Form to set team folder-->
            <ValidationObserver @submit.prevent="updateTeamFolder" ref="teamFolderForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Add Member-->
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Email" v-slot="{ errors }">
					<label class="input-label">{{ $t('Add Member') }}:</label>
					<input @keypress.enter.stop.prevent="addMember" v-model="email" :class="{'is-error': errors[0]}" type="email" class="focus-border-theme" :placeholder="$t('Type member email...')">
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>

				<!--Member list-->
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Members" rules="required" v-slot="{ errors }">
					<label class="input-label">{{ $t('Your Members') }}:</label>
					<span v-if="errors[0]" class="error-message" style="margin-top: -5px">{{ $t('Please add at least one member.') }}</span>
					<TeamList v-model="members" />
					<TeamList v-model="invitations" />
				</ValidationProvider>
            </ValidationObserver>

        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase
                    class="popup-button"
                    @click.native="$closePopup()"
                    button-style="secondary"
            >{{ $t('popup_move_item.cancel') }}
            </ButtonBase>
            <ButtonBase
                    class="popup-button"
                    @click.native="updateTeamFolder"
                    button-style="theme"
					:loading="isLoading"
					:disabled="isLoading"
            >{{ $t('Update Team Folder') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import PopupWrapper from '/resources/js/components/Others/Popup/PopupWrapper'
    import PopupActions from '/resources/js/components/Others/Popup/PopupActions'
    import PopupContent from '/resources/js/components/Others/Popup/PopupContent'
    import PopupHeader from '/resources/js/components/Others/Popup/PopupHeader'
    import ThumbnailItem from '/resources/js/components/Others/ThumbnailItem'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import TeamList from "./Components/TeamList";
    import {required} from 'vee-validate/dist/rules'
	import InfoBox from "../Others/Forms/InfoBox";
    import {events} from '/resources/js/bus'
	import axios from "axios";

    export default {
        name: 'UpdateTeamFolderPopup',
        components: {
            ValidationProvider,
            ValidationObserver,
			TeamList,
            ThumbnailItem,
            PopupWrapper,
            PopupActions,
            PopupContent,
            PopupHeader,
            ButtonBase,
            required,
			InfoBox,
        },
        computed: {
			//
        },
        data() {
            return {
            	invitations: [],
            	members: [],
                item: undefined,
                name: undefined,
				email: undefined,
				isLoading: false,
            }
        },
        methods: {
            async updateTeamFolder() {
				const isValid = await this.$refs.teamFolderForm.validate()

				if (!isValid) return

				this.isLoading = true

				axios
					.patch(`/api/teams/folders/${this.item.id}`, {
						members: this.members,
						invitations: this.invitations,
					})
					.then(response => {
						// todo: update view

						this.$store.dispatch('getAppData')
					})
					.catch(() => this.$isSomethingWrong())
					.finally(() => {
						this.isLoading = false
						this.name = undefined
						this.invitations = undefined

						this.$closePopup()
					})
            },
			addMember() {
            	let email = this.email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)[0]

            	if (! email) {
					this.$refs.teamFolderForm.setErrors({
						'Email': this.$t("You have to type valid email")
					});
				}

				this.$refs.teamFolderForm.reset()

            	this.invitations.push({
					email: this.email,
					avatar: '/assets/images/default-avatar.png',
					permission: 'can-edit',
				})

				this.email = undefined
			}
        },
        mounted() {
            events.$on('popup:open', args => {
                if (args.name !== 'update-team-folder') return

				console.log(args.item);

				if (args.item) {
					this.item = args.item
					this.members = args.item.team_members
					this.invitations = args.item.team_invitations
				}
            })

			events.$on('popup:close', () => {
				setTimeout(() => {
					this.email = undefined
					this.name = undefined
					this.item = undefined
					this.invitations = []
					this.members = []
				}, 150)
			})
        }
    }
</script>

<style scoped lang="scss">
    @import "resources/sass/vuefilemanager/_inapp-forms.scss";
    @import '/resources/sass/vuefilemanager/_forms';

    .item-thumbnail {
        margin-bottom: 20px;
    }
</style>
