<template>
    <PopupWrapper name="create-team-folder">

        <!--Title-->
        <PopupHeader :title="popupTitle" icon="user-plus" />

        <!--Content-->
        <PopupContent>

			<!--Item Thumbnail-->
            <ThumbnailItem v-if="! isNewFolderTeamCreation" class="item-thumbnail" :item="item" info="metadata" />

            <!--Form to set team folder-->
            <ValidationObserver @submit.prevent="createTeamFolder" ref="teamFolderForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Set folder name-->
                <ValidationProvider v-if="isNewFolderTeamCreation" tag="div" mode="passive" class="input-wrapper password" name="Name" rules="required" v-slot="{ errors }">
                    <label class="input-label">{{ $t('popup_create_folder.label') }}:</label>
                    <input v-model="name" :class="{'is-error': errors[0]}" type="text" ref="input" :placeholder="$t('popup_create_folder.placeholder')">
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

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
					<TeamList v-model="invitations" />
					<p v-if="Object.values(invitations).length === 0" class="input-help">{{ $t('Please add at least one member into your Team Folder.') }}</p>
				</ValidationProvider>

				<InfoBox v-if="! isNewFolderTeamCreation" style="margin-bottom: 0">
					<p v-html="$t('popup.move_into_team_disclaimer')"></p>
				</InfoBox>
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
                    @click.native="createTeamFolder"
                    button-style="theme"
					:loading="isLoading"
					:disabled="isLoading"
            >{{ popupSubmit }}
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
        name: 'CreateTeamFolderPopup',
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
			popupTitle() {
                return this.item ? this.$t('Convert as Team Folder') : this.$t('Create Team Folder')
            },
			popupSubmit() {
                return this.item ? this.$t('Move & Invite Members') : this.$t('Create Team Folder')
            },
			isNewFolderTeamCreation() {
				return ! this.item
			}
        },
        data() {
            return {
            	invitations: [],
                item: undefined,
                name: undefined,
				email: undefined,
				isLoading: false,
            }
        },
        methods: {
            async createTeamFolder() {
				const isValid = await this.$refs.teamFolderForm.validate()

				if (!isValid) return

				this.isLoading = true

				let route = this.name
					? `/api/teams/folders`
					: `/api/teams/convert/${this.item.id}`

				let payload = this.name
					? {
						name: this.name,
						invitations: this.invitations,
					}
					: {
						invitations: this.invitations,
					}

				axios
					.post(route, payload)
					.then(response => {
						// todo: push to team folder
						//this.$router.push({name: 'TeamFolders', params: {id: response.data.id}})

						if (! this.$isThisRoute(this.$route, ['TeamFolders']))
							this.$router.push({name: 'TeamFolders'})

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
					type: 'invitation',
					email: this.email,
					permission: 'can-edit',
				})

				this.email = undefined
			}
        },
        mounted() {
            events.$on('popup:open', args => {
                if (args.name !== 'create-team-folder') return

				if (args.item) {
					this.item = args.item
				}
            })

			events.$on('popup:close', () => {
				setTimeout(() => {
					this.email = undefined
					this.name = undefined
					this.item = undefined
					this.invitations = []
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
