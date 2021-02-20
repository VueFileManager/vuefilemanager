<template>
    <PopupWrapper name="rename-item">
        <!--Title-->
        <PopupHeader :title="$t('popup_rename.title', {item: itemTypeTitle})" icon="edit"/>

        <!--Content-->
        <PopupContent>

            <!--Item Thumbnail-->
            <ThumbnailItem class="item-thumbnail" :item="pickedItem" info="metadata" :setFolderIcon="setFolderIcon"/>

            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent="changeName" ref="renameForm" v-slot="{ invalid }" tag="form" class="form-wrapper">

                <!--Set password-->
                <ValidationProvider tag="div" mode="passive" class="input-wrapper password" name="Name" rules="required" v-slot="{ errors }">
                    <label class="input-label">{{ $t('popup_rename.label') }}:</label>
                    <div class="input">
                        <input v-model="pickedItem.name" :class="{'is-error': errors[0]}" ref="input" type="text" :placeholder="$t('popup_rename.placeholder')">
                        <div @click="pickedItem.name = ''" class="close-icon-wrapper">
                            <x-icon class="close-icon" size="14"/>
                        </div>
                    </div>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <SetFolderIcon v-if="isMoreOptions" :folderData="pickedItem" :unique_id="pickedItem.unique_id" />

                <ActionButton v-if="pickedItem.type === 'folder'" @click.native.stop="moreOptions" :icon="isMoreOptions ? 'x' : 'pencil-alt'">{{ moreOptionsTitle }}</ActionButton>

            </ValidationObserver>


        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase class="popup-button" @click.native="$closePopup()" button-style="secondary">{{ $t('popup_move_item.cancel') }}
            </ButtonBase>
            <ButtonBase class="popup-button" @click.native="changeName" button-style="theme">{{ $t('popup_share_edit.save') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from '@/components/Others/Popup/PopupWrapper'
import PopupActions from '@/components/Others/Popup/PopupActions'
import PopupContent from '@/components/Others/Popup/PopupContent'
import PopupHeader from '@/components/Others/Popup/PopupHeader'
import SetFolderIcon from '@/components/Others/SetFolderIcon'
import ThumbnailItem from '@/components/Others/ThumbnailItem'
import ActionButton from '@/components/Others/ActionButton'
import ButtonBase from '@/components/FilesView/ButtonBase'
import { XIcon } from 'vue-feather-icons'
import { required } from 'vee-validate/dist/rules'
import { events } from '@/bus'
import axios from 'axios'

export default {
    name: 'RenameItem',
    components: {
        ValidationProvider,
        ValidationObserver,
        SetFolderIcon,
        ThumbnailItem,
        ActionButton,
        PopupWrapper,
        PopupActions,
        PopupContent,
        PopupHeader,
        ButtonBase,
        required,
        XIcon
    },
    computed: {
        itemTypeTitle() {
            return this.pickedItem && this.pickedItem.type === 'folder' ? this.$t('types.folder') : this.$t('types.file')
        },
        moreOptionsTitle() {
            return this.isMoreOptions ? this.$t('shared_form.button_close_options') : this.$t('shared_form.button_folder_icon_open')
        }
    },
    data() {
        return {
            pickedItem: undefined,
            isMoreOptions: false,
            setFolderIcon: undefined
        }
    },
    methods: {
        moreOptions() {
            this.isMoreOptions = !this.isMoreOptions
        },
        changeName() {
            if (this.pickedItem.name && this.pickedItem.name !== '') {

                let item = {
                    unique_id: this.pickedItem.unique_id,
                    type: this.pickedItem.type,
                    name: this.pickedItem.name,
                    folder_icon: this.setFolderIcon ? this.setFolderIcon : null
                }

                // Rename item request
                this.$store.dispatch('renameItem', item)

                // Rename item in view
                events.$emit('change:name', item)

                this.$closePopup()
            }
        }
    },
    mounted() {

        // Show popup
        events.$on('popup:open', args => {

            if (args.name !== 'rename-item') return

            if (! this.$isMobile()) {
                this.$nextTick(() => this.$refs.input.focus())
            }

            this.isMoreOptions = false

            this.setFolderIcon = undefined

            // Store picked item
            this.pickedItem = args.item
        })

        events.$on('setFolderIcon', (icon) => {
            this.setFolderIcon = icon.value
        })
    }
}
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_inapp-forms.scss";
@import '@assets/vue-file-manager/_forms';

.input {
    position: relative;
    display: flex;
    justify-content: flex-end;
    align-items: center;

    .close-icon-wrapper {
        width: 22px;
        height: 22px;
        position: absolute;
        cursor: pointer;
        right: 15px;
        border-radius: 6px;
        display: flex;
        justify-content: center;
        align-items: center;

        &:hover {
            .close-icon {
                line {
                    stroke: $theme;
                }
            }
        }

        .close-icon {
            line {
                stroke: rgba($text-muted, 0.3);
            }
        }
    }
}

.item-thumbnail {
    margin-bottom: 20px;
}

@media (prefers-color-scheme: dark) {
    .close-icon-wrapper {
        &:hover {

            .close-icon {
                line {
                    stroke: $theme !important;
                }
            }
        }

        .close-icon {
            line {
                stroke: rgba($dark_mode_text_primary, 0.3) !important;
            }
        }
    }
}
</style>
