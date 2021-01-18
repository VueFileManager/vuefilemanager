<template>
    <div :class="{'active' : active}"> 
        <div class="wrapper">
            <mail-icon v-if="icon === 'email'" class="icon" size="17"/> 
            <link-icon v-if="icon === 'link'" class="icon" size="17"/>
            <smile-icon v-if="icon === 'emoji'" class="icon" size="17"/>
            <folder-icon v-if="icon === 'folder'" class="icon" size="17"/>
            <b>{{title}}</b>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    import {
        LinkIcon,
        MailIcon,
        SmileIcon,
        FolderIcon } from 'vue-feather-icons'

    export default {
        name: "TableOption",
        props: ['title', 'icon', 'active'],
        components: {
            LinkIcon,
            MailIcon,
            SmileIcon,
            FolderIcon
        }
        
    }
</script>

<style scoped lang="scss">
    @import "@assets/vue-file-manager/_inapp-forms.scss";
    @import '@assets/vue-file-manager/_forms';

    .wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon {
            margin-right: 10px;
            path,
            polyline {
                color: $theme !important;
            }
        }
     .active {
            background: $text;
                b {
                    color: $light_background !important;
                }
        }

    @media (prefers-color-scheme: dark) {
        
        .active {
            background: $dark_mode_text_primary;
                h1 {
                    color: $dark_mode_foreground !important;
                }
        }
    }

</style>
