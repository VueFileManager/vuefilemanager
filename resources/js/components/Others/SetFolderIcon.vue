<template>
    <TabWrapper class="set-folder-icon">

        <!-- Emojis Picker -->
        <TabOption :selected="true" :title="$t('popup_rename.tab_emoji_title')" icon="emoji">
            <EmojiPicker :picked-emoji="pickedEmoji"/>
        </TabOption>

        <!-- Colors Picker-->
        <TabOption :title="$t('popup_rename.tab_color_title')" icon="folder">
            <div class="color-pick-wrapper">
                <label class="main-label">{{ $t('popup_rename.color_pick_label') }}:</label>
                <ul class="color-wrapper">
                    <li v-for="(color, i) in colors" :key="i" @click="setIcon({'color': color})" class="single-color">
                        <check-icon v-if="color === selectedColor" class="color-icon" size="22"/>
                        <span :style="{background:color}" class="color-box"></span>
                    </li>
                </ul>
            </div>
        </TabOption>
    </TabWrapper>
</template>

<script>
import { CheckIcon } from 'vue-feather-icons'
import EmojiPicker from '@/components/Others/EmojiPicker'
import TabWrapper from '@/components/Others/TabWrapper'
import TabOption from '@/components/Others/TabOption'
import { events } from '@/bus'

export default {
    name: 'SetFolderIcon',
    props: [ 'folderData' ],
    components: {
        EmojiPicker,
        TabWrapper,
        CheckIcon,
        TabOption,
    },
    computed: {
        pickedEmoji () {
            // If is color not seted send picked emoji via props to EmojiPicker for the EmojiSelected input
            return !this.selectedColor ? this.selectedEmoji : ''
        }
    },
    data() {
        return {
            selectedColor: undefined,
            selectedEmoji: undefined,
            colors: [
                '#41B883',
                '#FE6F6F',
                '#FE6F91',
                '#FE6FC0',
                '#FE6FF0',
                '#DD6FFE',
                '#AD6FFE',
                '#7D6FFE',
                '#6F90FE',
                '#6FC0FE',
                '#6FF0FE',
                '#6FFEDD',
                '#6FFEAD',
                '#6FFE7D',
                '#90FE6F',
                '#C0FE6F',
                '#F0FE6F',
                '#FEDD6F',
                '#FEAD6F',
                '#FE7D6F',
                '#4c4c4c',
                '#06070B',
            ]
        }
    },
    methods: {
        setIcon(value) {

            // Set emoji
            this.selectedColor = value.color

            this.selectedEmoji = undefined
            
            events.$emit('setFolderIcon', { 'value': value })

        }
    },
    mounted() {

        // Reset color after set emoji
        events.$on('setFolderIcon', (icon) => {
            
            if(icon.value.emoji) {
                this.selectedEmoji = icon.value.emoji
                this.selectedColor = undefined
            }
        })

        // If folder have already set some color set this color to selected color
        this.folderData.icon_color ? this.selectedColor = this.folderData.icon_color : ''

        // If folder have already set some emojit set this emoji to selected emoji
        this.folderData.icon_emoji ? this.selectedEmoji = this.folderData.icon_emoji : ''
    }
}
</script>

<style lang="scss" scoped>
@import "@assets/vue-file-manager/_inapp-forms.scss";
@import '@assets/vue-file-manager/_forms';

.color-pick-wrapper {
    .color-wrapper {
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fill, 32px);
        justify-content: space-between;
        gap: 7px;

        .single-color {
            height: 31px;
            list-style: none;
            border-radius: 8px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;

            .color-icon {
                z-index: 2;

                polyline {
                    stroke: white;
                }
            }

            .color-box {
                width: 100%;
                height: 100%;
                position: absolute;
                display: block;
            }
        }
    }
}

.main-label {
    @include font-size(14);
    font-weight: 700;
    margin-bottom: 8px;
    display: block;
}

.set-folder-icon {
    position: relative;
}

@media (prefers-color-scheme: dark) {
    .color-pick-wrapper {
        .color-wrapper {
            .single-color {
                &.active-color {
                    border: 2px solid;
                }

                &:hover {
                    border: 2px solid $dark_mode_text_primary;
                }
            }
        }
    }
}
</style>
