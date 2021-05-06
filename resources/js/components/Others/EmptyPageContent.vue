<template>
    <div class="empty-page-content">
        <div class="content">
            <div class="icon">
                <file-icon v-if="icon === 'file'" size="38" class="text-theme" />
                <file-text-icon v-if="icon === 'file-text'" size="38" class="text-theme" />
                <settings-icon v-if="icon === 'settings'" size="38" class="text-theme" />
                <edit2-icon v-if="icon === 'edit'" size="38" class="text-theme" />
            </div>
            <div class="header">
                <h1 class="title">{{ title }}</h1>
                <h2 class="description">{{ description }}</h2>
            </div>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    import { FileIcon, FileTextIcon, SettingsIcon, Edit2Icon } from 'vue-feather-icons'

    export default {
        name: 'EmptyPageContent',
        props: ['icon','title','description'],
        components: {
            SettingsIcon,
            FileTextIcon,
			Edit2Icon,
            FileIcon,
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

    .empty-page-content {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        text-align: center;

        .content {
            margin: -70px auto 0;
            max-width: 360px;

            /deep/ .button-base {
                margin: 0 auto;
            }
        }

        .icon {
            path, polyline, line, circle {
                color: inherit;
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
