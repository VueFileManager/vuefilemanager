<template>
    <div>
        <Emoji v-if="folderEmoji" :emoji="folderEmoji"/>
        <FontAwesomeIcon v-if="!folderEmoji" :class="{ 'is-deleted': isDeleted }" class="folder-icon" icon="folder" :style="{fill: folderColor}"/>
    </div>
</template>


<script>
import Emoji from '@/components/Others/Emoji'
export default {
    name:"FolderIcon",
    props: ['item' , 'folderIcon'],
    components: {Emoji},
    computed: {
        isDeleted() {
            return this.item.deleted_at ? true : false
        },
        folderEmoji(){
            // Return emoji if is changed from rename popup 
            if(this.folderIcon)
                return this.folderIcon.emoji ? this.folderIcon.emoji : false
            
            // Return emoji if is already set
            return this.item.icon_emoji ? this.item.icon_emoji : false
        },
        folderColor() {
            // Return color if is changed from rename popup 
            if(this.folderIcon)
                return this.folderIcon.color ? this.folderIcon.color : '#00BC7E'
            
            // Return color if is already set
            return this.item.icon_color ? this.item.icon_color : '#00BC7E'    
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

.folder-icon {

    path {
        fill: inherit;
    }

    &.is-deleted {
        path {
            fill: $dark_background;
        }
    }
}

@media (prefers-color-scheme: dark) {
   
    .folder-icon {
        &.is-deleted {
            path {
                fill: lighten($dark_mode_foreground, 5%);
            }
        }
    }
}
</style>
