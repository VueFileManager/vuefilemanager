<template>
    <div :class="[{'is-apple': $isApple()}, location]">
        <Emoji
            v-if="emoji"
            :emoji="emoji"
            class="emoji-icon"
        />
        <FontAwesomeIcon
            v-if="!emoji"
            :class="[{ 'is-deleted': isDeleted },{'default-color' : ! color && ! isDeleted}, 'folder-icon' ]"
            :style="{fill: color}"
            icon="folder"
        />
    </div>
</template>

<script>
    import Emoji from '@/components/Others/Emoji'

    export default {
        name: 'FolderIcon',
        props: [
            'item',
            'folderIcon',
            'location'
        ],
        components: {
            Emoji
        },
        computed: {
            isDeleted() {
                return this.item.deleted_at ? true : false
            },
            emoji() {
                // Return emoji if is changed from rename popup
                if (this.folderIcon)
                    return this.folderIcon.emoji ? this.folderIcon.emoji : false

                // Return emoji if is already set
                return this.item.icon_emoji ? this.item.icon_emoji : false
            },
            color() {
                // Return color if is changed from rename popup
                if (this.folderIcon)
                    return this.folderIcon.color ? this.folderIcon.color : false

                // Return color if is already set
                return this.item.icon_color ? this.item.icon_color : false
            }
        }
    }
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

// Locations
.file-item-list {
    &.is-apple .emoji-icon {
        font-size: 50px;
        line-height: 1.1;
    }
}

.file-item-grid {
    &.is-apple .emoji-icon {
        font-size: 80px;
        line-height: 1.1;
    }
}

.thumbnail-item {
    &.is-apple .emoji-icon {
        font-size: 36px;
        line-height: 1.1;
    }
}

.emoji-picker-preview {
    &.is-apple .emoji-icon {
        font-size: 22px;
        line-height: 1.1;
    }
}

.default-color {
    path {
        fill: $theme !important;
    }
}

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
