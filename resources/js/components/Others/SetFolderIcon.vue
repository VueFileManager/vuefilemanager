<template>
    <TabWrapper class="set-folder-icon">

        <!-- Emojis Picker -->
        <TabOption :selected="true" :title="$t('popup_rename.tab_emoji_title')" icon="emoji">
            <EmojiPicker :picked-emoji="pickedEmoji" v-model="selectedEmoji" />
        </TabOption>

        <!-- Colors Picker-->
        <TabOption :title="$t('popup_rename.tab_color_title')" icon="folder">
            <ColorPicker :picked-color="pickedColor" v-model="selectedColor"/>
        </TabOption>
    </TabWrapper>
</template>

<script>
import EmojiPicker from '@/components/Others/EmojiPicker'
import ColorPicker from '@/components/Others/ColorPicker'
import TabWrapper from '@/components/Others/TabWrapper'
import TabOption from '@/components/Others/TabOption'
import { events } from '@/bus'

export default {
    name: 'SetFolderIcon',
    props: [ 'folderData' ],
    components: {
        EmojiPicker,
        ColorPicker,
        TabWrapper,
        TabOption,
    },
    computed: {
        pickedEmoji () {
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
        selectedColor () {

            let color = {'color': this.selectedColor}

            if( this.selectedColor ) {

                this.selectedEmoji = undefined
                events.$emit('setFolderIcon', color )
            }
          
        },
        selectedEmoji () {

            let emoji = { 'emoji': this.selectedEmoji }

            if( this.selectedEmoji ) {

                this.selectedColor = undefined
                events.$emit('setFolderIcon', this.selectedEmoji ==='default' ? 'default' : emoji )
            }
        },
    },
    created() {
        if(this.folderData) {
            
            // If folder have already set some color set this color to selected color
            this.folderData.icon_color ? this.selectedColor = this.folderData.icon_color : ''

            // If folder have already set some emojit set this emoji to selected emoji
            this.folderData.icon_emoji ? this.selectedEmoji = this.folderData.icon_emoji : ''
        }
    },
    destroyed () {
        if(this.folderData) {
            
            // After close SetFolderIcon set the saved folder icon for thumbnail
            let color = {'color': this.folderData.icon_color }
            let emoji = {'emoji': this.folderData.icon_emoji }
    
            events.$emit('setFolderIcon', this.folderData.icon_emoji ? emoji : color  )
        }
    }
}
</script>

<style lang="scss" scoped>
@import "@assets/vue-file-manager/_inapp-forms.scss";
@import '@assets/vue-file-manager/_forms';

.set-folder-icon {
    position: relative;
}
</style>
