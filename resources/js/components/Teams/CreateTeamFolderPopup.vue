<template>
    <PopupWrapper name="create-team-folder">
        <!--Title-->
        <PopupHeader :title="popupTitle" icon="user-plus" />

        <!--Content-->
        <PopupContent>
            <!--Item Thumbnail-->
            <ThumbnailItem v-if="!isNewFolderTeamCreation" class="mb-5" :item="item" />

            <!--Form to set team folder-->
            <ValidationObserver @submit.prevent="createTeamFolder" ref="teamFolderForm" v-slot="{ invalid }" tag="form">
                <!--Set folder name-->
                <ValidationProvider
                    v-if="isNewFolderTeamCreation"
                    tag="div"
                    mode="passive"
                    name="Name"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <AppInputText :title="$t('popup_create_folder.label')" :error="errors[0]">
                        <input
                            v-model="name"
                            :class="{ '!border-rose-600': errors[0] }"
                            type="text"
                            ref="name"
                            class="focus-border-theme input-dark"
                            :placeholder="$t('popup_create_folder.placeholder')"
                        />
                    </AppInputText>
                </ValidationProvider>

                <!--Add Member-->
                <ValidationProvider tag="div" mode="passive" name="Email" v-slot="{ errors }">
                    <AppInputText :title="$t('Add Member')" :error="errors[0]">
                        <div class="relative">
                            <span
                                v-if="email"
                                @click="addMember"
                                class="button-base theme absolute right-2 top-1/2 -translate-y-1/2 transform cursor-pointer rounded-lg px-3 py-2 text-sm font-bold"
                            >
                                Add
                            </span>
                            <input
                                @keypress.enter.stop.prevent="addMember"
                                ref="email"
                                v-model="email"
                                :class="{ '!border-rose-600': errors[0] }"
                                type="email"
                                class="focus-border-theme input-dark"
                                :placeholder="$t('Type member email...')"
                            />
                        </div>
                    </AppInputText>
                </ValidationProvider>

                <!--Member list-->
                <ValidationProvider tag="div" mode="passive" name="Members" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('Your Members')" :error="errors[0]" :is-last="true">
                        <span v-if="errors[0]" class="error-message" style="margin-top: -5px">
                            {{ $t('Please add at least one member.') }}
                        </span>

                        <TeamList v-model="invitations" />

                        <p v-if="Object.values(invitations).length === 0" class="text-xs dark:text-gray-500">
                            {{ $t('Please add at least one member into your Team Folders.') }}
                        </p>
                    </AppInputText>
                </ValidationProvider>

                <InfoBox v-if="!isNewFolderTeamCreation" class="mt-2.5 !mb-0">
                    <p v-html="$t('popup.move_into_team_disclaimer')"></p>
                </InfoBox>
            </ValidationObserver>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary"
                >{{ $t('cancel') }}
            </ButtonBase>
            <ButtonBase
                class="w-full"
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
import AppInputText from '../Admin/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from '../Others/Popup/PopupWrapper'
import PopupActions from '../Others/Popup/PopupActions'
import PopupContent from '../Others/Popup/PopupContent'
import PopupHeader from '../Others/Popup/PopupHeader'
import ThumbnailItem from '../Others/ThumbnailItem'
import ButtonBase from '../FilesView/ButtonBase'
import TeamList from './Components/TeamList'
import { required } from 'vee-validate/dist/rules'
import InfoBox from '../Others/Forms/InfoBox'
import { events } from '../../bus'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
    name: 'CreateTeamFolderPopup',
    components: {
        ValidationProvider,
        ValidationObserver,
        AppInputText,
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
        ...mapGetters(['user']),
        popupTitle() {
            return this.item ? this.$t('Convert as Team Folder') : this.$t('Create Team Folder')
        },
        popupSubmit() {
            return this.item ? this.$t('Move & Invite Members') : this.$t('Create Team Folder')
        },
        isNewFolderTeamCreation() {
            return !this.item
        },
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

            let route = this.name ? `/api/teams/folders` : `/api/teams/folders/${this.item.data.id}/convert`

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
                .then((response) => {
                    let isTeamFoldersLocation = this.$isThisRoute(['TeamFolders'])

                    // Redirect into newly created team folder
                    if (isTeamFoldersLocation && this.$route.params.id) {
                        this.$router.push({
                            name: 'TeamFolders',
                            params: { id: response.data.data.id },
                        })

                        // Add created team folder into Team Folder homepage view
                    } else if (isTeamFoldersLocation && !this.$route.params.id) {
                        this.$store.commit('ADD_NEW_FOLDER', response.data)

                        // Redirect to Team Folders after converting simple folder
                    } else if (!isTeamFoldersLocation) {
                        this.$router.push({ name: 'TeamFolders' })
                    }

                    let toasterMessage = this.isNewFolderTeamCreation
                        ? this.$t('Your Team was invited successfully.')
                        : this.$t('Your Team was invited and folder was moved into Team Folders section.')

                    events.$emit('toaster', {
                        type: 'success',
                        message: toasterMessage,
                    })

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
            if (this.$isInvalidEmail(this.email)) {
                this.$refs.teamFolderForm.setErrors({
                    Email: this.$t('You have to type valid email'),
                })
                return
            }

            if (this.$cantInviteMember(this.email, this.invitations)) {
                this.$refs.teamFolderForm.setErrors({
                    Email: this.$t('You have to upgrade your account to add this new member.'),
                })
                return
            }

            this.$refs.teamFolderForm.reset()

            this.invitations.unshift({
                type: 'invitation',
                email: this.email,
                permission: 'can-edit',
            })

            this.email = undefined
        },
    },
    created() {
        events.$on('popup:open', (args) => {
            if (args.name !== 'create-team-folder') return

            this.item = args.item

            this.$nextTick(() => {
                if (this.$isMobile()) return

                if (this.item) this.$refs.email.focus()

                if (!this.item && this.$refs.name) this.$refs.name.focus()
            })
        })

        events.$on('popup:close', () => {
            setTimeout(() => {
                this.email = undefined
                this.name = undefined
                this.item = undefined
                this.invitations = []
            }, 150)
        })
    },
}
</script>
