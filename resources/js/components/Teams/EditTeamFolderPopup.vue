<template>
    <PopupWrapper name="update-team-folder">
        <!--Title-->
        <PopupHeader :title="$t('edit_team_folder')" icon="user-plus" />

        <!--Content-->
        <PopupContent>
            <!--Item Thumbnail-->
            <ThumbnailItem class="mb-5" :item="item" />

            <!--Form to set team folder-->
            <ValidationObserver @submit.prevent="updateTeamFolder" ref="teamFolderForm" v-slot="{ invalid }" tag="form">
                <!--Add Member-->
                <ValidationProvider tag="div" mode="passive" name="Email" v-slot="{ errors }">
                    <AppInputText :title="$t('add_member')" :error="errors[0]">
                        <div class="relative">
                            <span
                                v-if="email"
                                @click="addMember"
                                class="button-base theme absolute right-2 top-1/2 -translate-y-1/2 transform cursor-pointer rounded-lg px-3 py-2 text-sm font-bold"
                            >
                                {{ $t('add') }}
                            </span>

                            <!--TODO: Fix !pr-20 after JIT official release-->
                            <input
                                @keypress.enter.stop.prevent="addMember"
                                ref="email"
                                v-model="email"
                                :class="{ '!border-rose-600': errors[0] }"
                                type="email"
                                class="focus-border-theme input-dark !pr-20"
                                :placeholder="$t('type_member_email_')"
                            />
                        </div>
                    </AppInputText>
                </ValidationProvider>

                <!--Member list-->
                <ValidationProvider tag="div" mode="passive" name="Members" v-slot="{ errors }">
                    <label class="input-label">{{ $t('your_members') }}:</label>
                    <span v-if="errors[0]" class="text-left text-xs text-red-600" style="margin-top: -5px">{{
                        $t('add_at_least_one_member')
                    }}</span>
                    <TeamList v-model="members" />
                    <TeamList v-model="invitations" />

                    <p
                        v-if="Object.values(members).length === 0 && Object.values(invitations).length === 0"
                        class="text-xs dark:text-gray-500"
                    >
                        {{ $t('add_at_least_one_member_into_team_folder') }}
                    </p>
                </ValidationProvider>
            </ValidationObserver>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary"
                >{{ $t('cancel') }}
            </ButtonBase>
            <ButtonBase
                class="w-full"
                @click.native="updateTeamFolder"
                :button-style="isDisabledSubmit ? 'secondary' : 'theme'"
                :loading="isLoading"
                :disabled="isLoading || isDisabledSubmit"
                >{{ $t('update_team_folder') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import AppInputText from '../Forms/Layouts/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from '../Popups/Components/PopupWrapper'
import PopupActions from '../Popups/Components/PopupActions'
import PopupContent from '../Popups/Components/PopupContent'
import PopupHeader from '../Popups/Components/PopupHeader'
import ThumbnailItem from '../UI/Entries/ThumbnailItem'
import ButtonBase from '../UI/Buttons/ButtonBase'
import TeamList from './Components/TeamList'
import { required } from 'vee-validate/dist/rules'
import InfoBox from '../UI/Others/InfoBox'
import { events } from '../../bus'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
    name: 'EditTeamFolderPopup',
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
        isDisabledSubmit() {
            return Object.values(this.members).length === 0 && Object.values(this.invitations).length === 0
        },
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
                .then((response) => {
                    this.$store.commit('UPDATE_ITEM', response.data)
                    this.$store.commit('SET_CURRENT_TEAM_FOLDER', response.data)

                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('team_folder_updated'),
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
            if (!this.$isValidEmail(this.email)) {
                this.$refs.teamFolderForm.setErrors({
                    Email: this.$t('type_valid_email'),
                })
                return
            }

            if (this.$cantInviteMember(this.email, this.invitations)) {
                this.$refs.teamFolderForm.setErrors({
                    Email: this.$t('upgrade_to_invite_members'),
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
    mounted() {
        events.$on('popup:open', (args) => {
            if (args.name !== 'update-team-folder') return

            this.item = args.item

            this.members = args.item.data.relationships.members.data.map((member) => {
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

            this.invitations = args.item.data.relationships.invitations.data.map((member) => {
                return {
                    id: member.data.id,
                    type: 'invitation',
                    email: member.data.attributes.email,
                    color: member.data.attributes.color,
                    permission: member.data.attributes.permission,
                }
            })

            this.$nextTick(() => {
                if (this.$refs.email && !this.$isMobile()) this.$refs.email.focus()
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
    },
}
</script>

<style scoped lang="scss">
@import '../../../sass/vuefilemanager/inapp-forms';
@import '../../../sass/vuefilemanager/forms';

.item-thumbnail {
    margin-bottom: 20px;
}
</style>
