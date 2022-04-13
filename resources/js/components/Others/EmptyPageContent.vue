<template>
    <div class="empty-page-content">
        <div class="content">
            <div class="icon inline-block">
                <file-icon v-if="icon === 'file'" size="38" />
                <file-text-icon v-if="icon === 'file-text'" size="38" />
                <settings-icon v-if="icon === 'settings'" size="38" />
            </div>
            <div class="header">
                <h1 class="title">{{ title }}</h1>
                <h2 class="description">{{ description }}</h2>
            </div>
            <slot />
        </div>
    </div>
</template>

<script>
import { FileIcon, FileTextIcon, SettingsIcon } from 'vue-feather-icons'

export default {
    name: 'EmptyPageContent',
    props: ['icon', 'title', 'description'],
    components: {
        SettingsIcon,
        FileTextIcon,
        FileIcon,
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.empty-page-content {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    text-align: center;

    .content {
        margin: 0 auto;
        max-width: 360px;

        /deep/ .button-base {
            margin: 0 auto;
        }
    }

    .icon {
        path,
        polyline,
        line,
        circle {
            stroke: $theme;
        }
    }

    .header {
        margin-top: 15px;
        margin-bottom: 25px;
    }

    .title {
        @include font-size(23);
        font-weight: 700;
        padding-bottom: 5px;
    }

    .description {
        @include font-size(16);
        font-weight: 500;
    }
}
</style>
