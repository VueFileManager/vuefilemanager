<template>
    <PopupWrapper name="rename-item">
        <!--Title-->
        <PopupHeader :title="$t('popup_rename.title', {item: itemTypeTitle})" icon="edit" />

        <!--Content-->
        <PopupContent>

            <!--Item Thumbnail-->
            <ThumbnailItem class="mb-5" :item="pickedItem" info="metadata" :setFolderIcon="folderIcon" />

            <!--Form to set sharing-->
            <ValidationObserver @submit.prevent="changeName" ref="renameForm" v-slot="{ invalid }" tag="form">

                <!--Update item name-->
                <ValidationProvider tag="div" mode="passive" name="Name" rules="required" v-slot="{ errors }">
					<AppInputText :title="$t('popup_rename.label')" :error="errors[0]" :is-last="pickedItem.data.type !== 'folder'">
						<div class="flex items-center relative">
							<input v-model="pickedItem.data.attributes.name" :class="{'border-red': errors[0]}" ref="input" type="text" class="focus-border-theme input-dark" :placeholder="$t('popup_rename.placeholder')">
							<div @click="pickedItem.data.attributes.name = ''" class="absolute right-4">
								<x-icon class="close-icon hover-text-theme" size="14" />
							</div>
						</div>
					</AppInputText>
                </ValidationProvider>

				<!--Emoji-->
				<AppInputSwitch :title="$t('Emoji as an Icon')" :description="$t('Replace folder icon with an Emoji')" :is-last="! isEmoji">
					<SwitchInput v-model="isEmoji" :state="isEmoji" />
				</AppInputSwitch>

				<!--Set emoji-->
				<EmojiPicker v-if="isEmoji" v-model="emoji" :default-emoji="emoji"/>
            </ValidationObserver>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase class="w-full" @click.native="$closePopup()" button-style="secondary">
                {{ $t('popup_move_item.cancel') }}
            </ButtonBase>
            <ButtonBase class="w-full" @click.native="changeName" button-style="theme">
                {{ $t('popup_share_edit.save') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
import EmojiPicker from "./EmojiPicker";
import AppInputSwitch from "../Admin/AppInputSwitch"
import AppInputText from "../Admin/AppInputText"
import SwitchInput from "./Forms/SwitchInput"
import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
import PopupWrapper from '/resources/js/components/Others/Popup/PopupWrapper'
import PopupActions from '/resources/js/components/Others/Popup/PopupActions'
import PopupContent from '/resources/js/components/Others/Popup/PopupContent'
import PopupHeader from '/resources/js/components/Others/Popup/PopupHeader'
import ThumbnailItem from '/resources/js/components/Others/ThumbnailItem'
import ActionButton from '/resources/js/components/Others/ActionButton'
import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
import {XIcon} from 'vue-feather-icons'
import {required} from 'vee-validate/dist/rules'
import {events} from '/resources/js/bus'

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
            return this.pickedItem && this.pickedItem.data.type === 'folder' ? this.$t('types.folder') : this.$t('types.file')
        },
        moreOptionsTitle() {
            return this.isMoreOptions ? this.$t('shared_form.button_close_options') : this.$t('shared_form.button_folder_icon_open')
        },
    },
	watch: {
		isEmoji(val) {
			if (! val) {
				events.$emit('setFolderIcon', {emoji: undefined})

				this.folderIcon.emoji = undefined
			} else {
				events.$emit('setFolderIcon', {emoji: this.emoji})

				this.folderIcon.emoji = this.emoji
			}
		},
		emoji(val) {
			events.$emit('setFolderIcon', {
				emoji: val
			})

			this.folderIcon.emoji = val
		},
	},
    data() {
        return {
            pickedItem: undefined,
            isMoreOptions: false,
            folderIcon: {
				emoji: undefined,
				color: undefined,
			},
			isEmoji: false,
			emoji: undefined,
        }
    },
    methods: {
        moreOptions() {
            this.isMoreOptions = !this.isMoreOptions
        },
        changeName() {
            if (this.pickedItem.data.attributes.name && this.pickedItem.data.attributes.name !== '') {

                let item = {
                    id: this.pickedItem.data.id,
                    type: this.pickedItem.data.type,
                    name: this.pickedItem.data.attributes.name,
                }

				item['emoji'] = this.folderIcon.emoji || null
				item['color'] = this.folderIcon.color || null

				if (! this.folderIcon.emoji && ! this.folderIcon.color)
					item['emoji'] = null
					item['color'] = null

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

            if (!this.$isMobile()) {
                this.$nextTick(() => this.$refs.input.focus())
            }

            this.isMoreOptions = false

            this.folderIcon = {
				emoji: undefined,
				color: undefined,
			}

            // Store picked item
            this.pickedItem = args.item

			// Set default emoji if is set
			if (args.item.data.attributes.emoji) {
				this.isEmoji = true
				this.folderIcon.emoji = args.item.data.attributes.emoji
				this.emoji = args.item.data.attributes.emoji
			}
        })

        events.$on('setFolderIcon', icon => {
            this.folderIcon = icon
        })
    }
}
</script>

<style scoped lang="scss">
@import "resources/sass/vuefilemanager/_inapp-forms.scss";
@import '/resources/sass/vuefilemanager/_forms';

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
                    color: inherit;
                }
            }
        }

        .close-icon {
            line {
                color: rgba($text-muted, 0.3);
            }
        }
    }
}

.dark {
    .close-icon-wrapper {
        &:hover {

            .close-icon {
                line {
                    color: inherit !important;
                }
            }
        }

        .close-icon {
            line {
                color: rgba($dark_mode_text_primary, 0.3) !important;
            }
        }
    }
}
</style>
