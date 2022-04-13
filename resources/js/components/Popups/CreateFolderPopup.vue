<template>
    <PopupWrapper name="create-folder">
        <!--Title-->
        <PopupHeader :title="$t('popup_create_folder.title')" icon="edit" />

        <!--Content-->
        <PopupContent>
            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent="createFolder" ref="createForm" v-slot="{ invalid }" tag="form">
                <!--Set folder name-->
                <ValidationProvider tag="div" mode="passive" name="Title" rules="required" v-slot="{ errors }">
                    <AppInputText :title="$t('popup_create_folder.label')" :error="errors[0]">
                        <input
                            v-model="name"
                            :class="{ '!border-rose-600': errors[0] }"
                            type="text"
                            ref="input"
                            class="focus-border-theme input-dark"
                            :placeholder="$t('popup_create_folder.placeholder')"
                        />
                    </AppInputText>
                </ValidationProvider>

                <AppInputSwitch
                    :title="$t('emoji_as_an_icon')"
                    :description="$t('replace_icon_with_emoji')"
                    :is-last="!isEmoji"
                >
                    <SwitchInput v-model="isEmoji" :state="isEmoji" />
                </AppInputSwitch>

                <!--Set emoji-->
                <EmojiPicker v-if="isEmoji" v-model="emoji" :default-emoji="emoji" />
            </ValidationObserver>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary"
                >{{ $t('cancel') }}
            </ButtonBase>
            <ButtonBase class="w-full" @click.native="createFolder" button-style="theme"
                >{{ $t('popup_create_folder.title') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from './Components/PopupWrapper'
import PopupActions from './Components/PopupActions'
import PopupContent from './Components/PopupContent'
import PopupHeader from './Components/PopupHeader'
import ThumbnailItem from '../UI/Entries/ThumbnailItem'
import ButtonBase from '../UI/Buttons/ButtonBase'
import { required } from 'vee-validate/dist/rules'
import AppInputSwitch from '../Forms/Layouts/AppInputSwitch'
import AppInputText from '../Forms/Layouts/AppInputText'
import SwitchInput from '../Inputs/SwitchInput'
import { events } from '../../bus'
import EmojiPicker from '../Emoji/EmojiPicker'

export default {
    name: 'CreateFolderPopup',
    components: {
        AppInputSwitch,
        SwitchInput,
        EmojiPicker,
        AppInputText,
        ValidationProvider,
        ValidationObserver,
        ThumbnailItem,
        PopupWrapper,
        PopupActions,
        PopupContent,
        PopupHeader,
        ButtonBase,
        required,
    },
    data() {
        return {
            name: undefined,
            isEmoji: false,
            emoji: undefined,
        }
    },
    methods: {
        async createFolder() {
            // Validate fields
            const isValid = await this.$refs.createForm.validate()

            if (!isValid) return

            await this.$store.dispatch('createFolder', {
                name: this.name,
                emoji: this.emoji,
            })

            this.$closePopup()

            this.name = undefined
            this.isEmoji = false
            this.emoji = undefined
        },
    },
    mounted() {
        events.$on('popup:open', ({ name }) => {
            if (name === 'create-folder' && !this.$isMobile()) this.$nextTick(() => this.$refs.input.focus())
        })
    },
}
</script>
