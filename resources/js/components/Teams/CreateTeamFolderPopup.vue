<template>
    <PopupWrapper name="create-team-folder">

        <!--Title-->
        <PopupHeader :title="popupTitle" icon="users" />

        <!--Content-->
        <PopupContent>

			<!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="item" info="metadata" />

            <!--Form to set team folder-->
            <ValidationObserver @submit.prevent="createTeamFolder" ref="teamFolderForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Add Member-->
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Email" rules="required" v-slot="{ errors }">
					<label class="input-label">{{ $t('Add Member') }}:</label>
					<input @keypress.enter.stop="addMember" v-model="email" :class="{'is-error': errors[0]}" type="email" class="focus-border-theme" :placeholder="$t('Type member email...')">
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>

				<TeamMemberList v-model="members" />

				<InfoBox style="margin-bottom: 0">
					<p v-html="$t('Your folder will be moved into Team Folders.')"></p>
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
	import TeamMemberList from "./Components/TeamMemberList";
    import {required} from 'vee-validate/dist/rules'
	import InfoBox from "../Others/Forms/InfoBox";
    import {events} from '/resources/js/bus'

    export default {
        name: 'CreateTeamFolderPopup',
        components: {
            ValidationProvider,
            ValidationObserver,
			TeamMemberList,
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
                return this.item ? this.$t('Move into a Team Folder') : this.$t('Create Team Folder')
            },
			popupSubmit() {
                return this.item ? this.$t('Move & Invite Members') : this.$t('Create Team Folder')
            },
        },
        data() {
            return {
            	members: [
					{
						id: 1,
						name: 'Jane Doe',
						email: 'jane@doe.com',
						avatar: '/temp/avatar-01.png',
						permission: 'can-edit',
					},
					{
						id: 2,
						name: 'Emma Willis',
						email: 'dawn_mcglynn@adams.tv',
						avatar: '/temp/avatar-02.png',
						permission: 'can-view',
					},
					{
						id: 3,
						name: 'Barry Erickson',
						email: 'ulatid@jabo.edu',
						avatar: '/temp/avatar-03.png',
						permission: 'can-view-and-download',
					},
				],
                item: {
					type: 'folder',
					name: 'Shared Folder',
					created_at: '19. Feb. 2020 at 08:53',
				},
				email: undefined
            }
        },
        methods: {
            createTeamFolder() {

            },
			addMember() {
            	let email = this.email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)[0]

            	if (! email) {
					this.$refs.teamFolderForm.setErrors({
						'Email': this.$t("You have to type valid email")
					});
				}

				this.$refs.teamFolderForm.reset()

            	this.members.push({
					id: Math.floor(Math.random() * 10000000),
					email: this.email,
					avatar: '/assets/images/default-avatar.png',
					permission: 'can-edit',
				})

				this.email = undefined
			}
        },
        mounted() {
            events.$on('popup:open', item => {

                if (name !== 'create-team-folder') return

				this.item = item
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
