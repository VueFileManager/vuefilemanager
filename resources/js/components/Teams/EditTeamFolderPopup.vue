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
					<input @keypress.enter.stop.prevent="addMember" ref="email" v-model="email" :class="{'is-error': errors[0]}" type="email" class="focus-border-theme" :placeholder="$t('Type member email...')">
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>

				<!--Member list-->
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Members" v-slot="{ errors }">
					<label class="input-label">{{ $t('Your Members') }}:</label>
					<span v-if="errors[0]" class="error-message" style="margin-top: -5px">{{ $t('Please add at least one member.') }}</span>
					<TeamList v-model="members" />
					<TeamList v-model="invitations" />
					<p v-if="Object.values(members).length === 0 && Object.values(invitations).length === 0" class="input-help">{{ $t('Please add at least one member into your Team Folder.') }}</p>
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
				:button-style="isDisabledSubmit ? 'secondary' : 'theme'"
				:loading="isLoading"
				:disabled="isLoading || isDisabledSubmit"
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
			isDisabledSubmit() {
				return Object.values(this.members).length === 0 && Object.values(this.invitations).length === 0
			}
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
					.patch(`/api/teams/folders/${this.item.data.id}`, {
						members: this.members,
						invitations: this.invitations,
					})
					.then(response => {
						this.$store.commit('UPDATE_ITEM', response.data)

						events.$emit('toaster', {
							type: 'success',
							message: this.$t('Your team folder was updated'),
						})
					})
					.catch(() => {
						events.$emit('toaster', {
							type: 'danger',
							message: this.$t('popup_error.title'),
						})
					})
					.finally(() => {
						this.isLoading = false
						this.name = undefined
						this.invitations = undefined
						this.members = undefined

						this.$closePopup()
					})
			},
			addMember() {
				let email = this.email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)[0]

				if (!email) {
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
				if (args.name !== 'update-team-folder') return

				this.item = args.item

				this.members = args.item.data.relationships.members.data.map(member => {
					return {
						type: 'member',
						id: member.data.id,
						email: member.data.attributes.email,
						name: member.data.attributes.name,
						avatar: member.data.attributes.avatar,
						color: member.data.attributes.color,
						permission: member.data.attributes.permission,
					}
				})

				this.invitations = args.item.data.relationships.invitations.data.map(member => {
					return {
						id: member.data.id,
						type: 'invitation',
						email: member.data.attributes.email,
						color: member.data.attributes.color,
						permission: member.data.attributes.permission,
					}
				})

				this.$nextTick(() => {
					this.$refs.email.focus()
				})
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
