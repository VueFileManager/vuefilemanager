<template>
    <div :class="[{'is-apple': $isApple()}, location]">
        <Emoji
            v-if="emoji"
            :emoji="emoji"
            class="emoji-icon"
        />
		<VueFolderIcon
			v-if="!emoji && !item.data.attributes.isTeamFolder"
			:class="[{'is-deleted':isDeleted},{'default-color': ! color && ! isDeleted}, 'folder-icon' ]"
			:style="{fill: color}"
		/>
		<VueFolderTeamIcon
			v-if="!emoji && item.data.attributes.isTeamFolder"
			:class="[{'is-deleted':isDeleted},{'default-color': ! color && ! isDeleted}, 'folder-icon' ]"
			:style="{fill: color}"
			style="width: 53px; height: 52px"
		/>
    </div>
</template>

<script>
    import Emoji from '/resources/js/components/Others/Emoji'
	import VueFolderIcon from "./Icons/VueFolderIcon"
	import VueFolderTeamIcon from "./Icons/VueFolderTeamIcon"

    export default {
        name: 'FolderIcon',
        props: [
            'folderIcon',
            'location',
            'item',
        ],
        components: {
			VueFolderTeamIcon,
			VueFolderIcon,
            Emoji,
        },
        computed: {
            isDeleted() {
                return this.item.data.attributes.deleted_at
            },
            emoji() {
                // Return emoji if is changed from rename popup
                if (this.folderIcon)
                    return this.folderIcon.emoji ? this.folderIcon.emoji : false

                // Return emoji if is already set
                return this.item.data.attributes.emoji ? this.item.data.attributes.emoji : false
            },
            color() {
                // Return color if is changed from rename popup
                if (this.folderIcon)
                    return this.folderIcon.color ? this.folderIcon.color : false

                // Return color if is already set
                return this.item.data.attributes.color ? this.item.data.attributes.color : false
            }
        }
    }
</script>

<style lang="scss" scoped>
@import '/resources/sass/vuefilemanager/_variables';
@import '/resources/sass/vuefilemanager/_mixins';

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

.folder-icon {

    path {
        fill: inherit;
    }

    &.is-deleted {
        path {
            fill: $dark_background;
        }
    }

	&.is-team {
		path {
			fill: transparent;
			stroke-width: 26px;
			stroke: inherit;
		}
	}
}

.dark {

    .folder-icon {
        &.is-deleted {
            path {
                fill: lighten($dark_mode_foreground, 5%);
            }
        }
    }
}
</style>
