<template>
    <TabWrapper class="set-folder-icon">

        <!-- Emojis Picker -->
        <TabOption :selected="true" :title="$t('popup_rename.tab_emoji_title')" icon="emoji">
            <EmojiPicker :picked-emoji="pickedEmoji" v-model="selectedEmoji" />
        </TabOption>

        <!-- Colors Picker-->
        <TabOption :title="$t('popup_rename.tab_color_title')" icon="folder">
            <ColorPicker :picked-color="pickedColor" v-model="selectedColor" />
        </TabOption>
    </TabWrapper>
</template>

<script>
import EmojiPicker from '/resources/js/components/Others/EmojiPicker'
import ColorPicker from '/resources/js/components/Others/ColorPicker'
import TabWrapper from '/resources/js/components/Others/TabWrapper'
import TabOption from '/resources/js/components/Others/TabOption'
import {events} from '/resources/js/bus'

export default {
    name: 'SetFolderIcon',
    props: ['folderData'],
    components: {
        EmojiPicker,
        ColorPicker,
        TabWrapper,
        TabOption,
    },
    computed: {
        pickedEmoji() {
            // If is color not selected and emoji is selected, push picked emoji to EmojiPicker for the EmojiSelected input
            return !this.selectedColor && this.selectedEmoji ? this.selectedEmoji : undefined
        },
        pickedColor() {
            // If is emoji not selected and color is selected, push picked color to ColorPicker
            return !this.selectedEmoji && this.selectedColor ? this.selectedColor : undefined
        }
    },
    data() {
        return {
            selectedColor: undefined,
            selectedEmoji: undefined,
        }
    },
    watch: {
        selectedColor() {
            let color = {'color': this.selectedColor}

            if (this.selectedColor) {

                this.selectedEmoji = undefined
                events.$emit('setFolderIcon', color)
            }
        },
        selectedEmoji() {

            let emoji = {'emoji': this.selectedEmoji}

            if (this.selectedEmoji) {

                this.selectedColor = undefined
                events.$emit('setFolderIcon', this.selectedEmoji === 'default' ? 'default' : emoji)
            }
        },
    },
    created() {
        if (this.folderData) {

            // If folder have already set some color set this color to selected color
            this.folderData.color ? this.selectedColor = this.folderData.color : ''

            // If folder have already set some emojit set this emoji to selected emoji
            this.folderData.emoji ? this.selectedEmoji = this.folderData.emoji : ''
        }
    },
    destroyed() {
        if (this.folderData) {

            // After close SetFolderIcon set the saved folder icon for thumbnail
            let color = {'color': this.folderData.color}
            let emoji = {'emoji': this.folderData.emoji}

            events.$emit('setFolderIcon', this.folderData.emoji ? emoji : color)
        }
    }
}
</script>

<style lang="scss" scoped>
@import "resources/sass/vuefilemanager/_inapp-forms.scss";
@import '/resources/sass/vuefilemanager/_forms';

.set-folder-icon {
    position: relative;
}
</style>
