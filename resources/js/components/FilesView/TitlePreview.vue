<template>
    <div class="wrapper">
        <div class="icon-wrapper">
            <CheckSquareIcon v-if="icon === 'check-square'" class="icon text-theme vue-feather" size="21" />
			<image-icon v-if="icon === 'image'" class="icon text-theme vue-feather" size="21" />
			<video-icon v-if="icon === 'video'" class="icon text-theme vue-feather" size="21" />
			<folder-icon v-if="icon === 'folder'" class="icon text-theme vue-feather" size="21" />
			<file-icon v-if="icon === 'file'" class="icon text-theme vue-feather" size="21" />
        </div>
        <div class="text">
            <span class="title">{{ title }}</span>
            <span class="subtitle">{{ subtitle }}</span>
        </div>
    </div>
</template>

<script>
import {
	CheckSquareIcon,
	FolderIcon,
	ImageIcon,
	VideoIcon,
	FileIcon,
} from "vue-feather-icons"

export default {
    name: 'TitlePreview',
    props: [
        'subtitle',
        'title',
        'icon',
    ],
    components: {
        CheckSquareIcon,
		FolderIcon,
		ImageIcon,
		VideoIcon,
		FileIcon,
    },
}
</script>

<style lang="scss" scoped>
@import '/resources/sass/vuefilemanager/_variables';
@import '/resources/sass/vuefilemanager/_mixins';

.wrapper {
    display: flex;
    align-items: flex-start;

    .text {
        padding-left: 10px;
        width: 100%;
        word-break: break-all;

        .title {
            @include font-size(14);
            font-weight: 700;
            line-height: 1.4;
            display: block;
            color: $text;
        }

        .subtitle {
            @include font-size(12);
            font-weight: 600;
            color: $text-muted;
            display: block;
        }
    }

    .icon-wrapper {

        polyline, path, rect, circle, polyline {
            color: inherit;
        }
    }
}

.dark {
    .wrapper {
        .text {
            .title {
                color: $dark_mode_text_primary;
            }

            .subtitle {
                color: $dark_mode_text_secondary;
            }
        }
    }
}
</style>
