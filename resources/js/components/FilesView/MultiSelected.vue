<template>
    <div :class="this.moveItem ? 'move-item'  : 'wrapper'">
        <div class="icon-wrapper">   
            <CheckSquareIcon class="icon" size="21"/>
        </div>
        <div class="text">
            <span class="title">{{ $t('file_detail.selected_multiple') }}</span>
            <span class="count">{{this.fileInfoDetail.length}}  {{ $tc('file_detail.items', this.fileInfoDetail.length) }}</span>
        </div>
    </div>
</template>

<script>
import {CheckSquareIcon} from "vue-feather-icons";
import {mapGetters} from 'vuex'

    export default {
        name:'MultiSelected',
        props: ['moveItem'],
        components: {CheckSquareIcon},
        computed: {
            ...mapGetters(['fileInfoDetail'])
        },
        
    }
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

.wrapper {
    display: flex;
    justify-content: center;
    .text{
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
        .count {
             @include font-size(12);
            font-weight: 600;
            color: $theme;
            display: block;
        }
    }
    .icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        text-align: center;
        cursor: pointer;
        white-space: nowrap;
        outline: none;
        border: none;
        .icon { 
           stroke: $text;
        }
    }
}
.move-item {
    display: flex;
    justify-content: center;
    .text{
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
        .count {
             @include font-size(12);
            font-weight: 600;
            color: $text-muted;
            display: block;
        }
    }
    .icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        text-align: center;
        white-space: nowrap;
        outline: none;
        border: none;
        .icon { 
            stroke: $theme;
        }
    }
}
@media (prefers-color-scheme: dark) {
    .wrapper {
        .text {
            .title {
                color: $dark_mode_text_primary;
            }
            .count {
                color: $dark_mode_text_secondary;
            }
        }    
        .icon-wrapper {
            .icon { 
                stroke: $theme;
            }   
        }  
    }
    .move-item {
        .text {
            .title {
                color: $dark_mode_text_primary;
            }
            .count {
                color: $dark_mode_text_secondary;
            }
        }      
    }
}
</style>
