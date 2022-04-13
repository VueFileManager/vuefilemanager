<template>
    <PopupWrapper name="rename-item">
        <!--Title-->
        <PopupHeader :title="$t('popup_rename.title', { item: itemTypeTitle })" icon="edit" />

        <!--Content-->
        <PopupContent>
            <!--Item Thumbnail-->
            <ThumbnailItem class="mb-5" :item="pickedItem" :setFolderIcon="{ emoji: emoji, color: null }" />

            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent="changeName" ref="renameForm" v-slot="{ invalid }" tag="form">
                <!--Update item name-->
                <ValidationProvider tag="div" mode="passive" name="Name" rules="required" v-slot="{ errors }">
                    <AppInputText
                        :title="$t('popup_rename.label')"
                        :error="errors[0]"
                        :is-last="pickedItem.data.type !== 'folder'"
                    >
                        <div class="relative flex items-center">
                            <input
                                v-model="pickedItem.data.attributes.name"
                                :class="{ '!border-rose-600': errors[0] }"
                                ref="input"
                                type="text"
                                class="!pr-10 focus-border-theme input-dark"
                                :placeholder="$t('popup_rename.placeholder')"
                            />
                            <div @click="pickedItem.data.attributes.name = ''" class="absolute right-0 p-4 cursor-pointer">
                                <x-icon class="hover-text-theme" size="14" />
                            </div>
                        </div>
                    </AppInputText>
                </ValidationProvider>

                <!--Emoji-->
                <AppInputSwitch
                    v-if="pickedItem.data.type === 'folder'"
                    :title="$t('emoji_as_an_icon')"
                    :description="$t('replace_icon_with_emoji')"
                    :is-last="!isEmoji"
                >
                    <SwitchInput v-model="isEmoji" :state="isEmoji" />
                </AppInputSwitch>

                <!--Set emoji-->
                <EmojiPicker
                    v-if="pickedItem.data.type === 'folder' && isEmoji"
                    v-model="emoji"
                    :default-emoji="emoji"
                />
            </ValidationObserver>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary">
                {{ $t('cancel') }}
            </ButtonBase>
            <ButtonBase class="w-full" @click.native="changeName" button-style="theme">
                {{ $t('popup_share_edit.save') }}
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
import AppInputSwitch from '../Forms/Layouts/AppInputSwitch'
import AppInputText from '../Forms/Layouts/AppInputText'
import { required } from 'vee-validate/dist/rules'
import SwitchInput from '../Inputs/SwitchInput'
import EmojiPicker from '../Emoji/EmojiPicker'
import { XIcon } from 'vue-feather-icons'
import { events } from '../../bus'

export default {
    name: 'RenameItemPopup',
    components: {
        ValidationProvider,
        ValidationObserver,
        EmojiPicker,
        AppInputSwitch,
        SwitchInput,
        AppInputText,
        ThumbnailItem,
        PopupWrapper,
        PopupActions,
        PopupContent,
        PopupHeader,
        ButtonBase,
        required,
        XIcon,
    },
    computed: {
        itemTypeTitle() {
            return this.pickedItem && this.pickedItem.data.type === 'folder'
                ? this.$t('folder')
                : this.$t('file')
        },
    },
    watch: {
        isEmoji(val) {
            if (!val) {
                events.$emit('setFolderIcon', { emoji: undefined })
                this.emoji = undefined
            } else {
                events.$emit('setFolderIcon', { emoji: this.emoji })
            }
        },
        emoji(val) {
            events.$emit('setFolderIcon', {
                emoji: val,
            })
        },
    },
    data() {
        return {
            pickedItem: undefined,
            isEmoji: false,
            emoji: undefined,
        }
    },
    methods: {
        changeName() {
            if (this.pickedItem.data.attributes.name && this.pickedItem.data.attributes.name !== '') {
                let item = {
                    id: this.pickedItem.data.id,
                    type: this.pickedItem.data.type,
                    name: this.pickedItem.data.attributes.name,
                }

                item['emoji'] = this.emoji || null

                if (!this.isEmoji) item['emoji'] = null

                // Rename item request
                this.$store.dispatch('renameItem', item)

                // Rename item in view
                events.$emit('change:name', item)

                this.$closePopup()
            }
        },
    },
    mounted() {
        // Show popup
        events.$on('popup:open', (args) => {
            if (args.name !== 'rename-item') return

            this.isEmoji = false

            if (!this.$isMobile()) {
                this.$nextTick(() => this.$refs.input.focus())
            }

            // Set default emoji if exist
            if (args.item.data.attributes.emoji) {
                this.isEmoji = true
                this.emoji = args.item.data.attributes.emoji
            }

            // Store picked item
            this.pickedItem = args.item
        })
    },
}
</script>