<template>
    <div :class="[this.moveItem ? 'move-item'  : 'wrapper' , this.dragedGhost ? 'ghost' : '']">
        <div class="icon-wrapper">   
            <CheckSquareIcon class="icon" size="21"/>
        </div>
        <!-- Multi select for the move item popup and file info -->
        <div class="text" v-if="!this.dragedGhost">
            <span class="title">{{ $t('file_detail.selected_multiple') }}</span>
            <span class="count">{{this.fileInfoDetail.length}}  {{ $tc('file_detail.items', this.fileInfoDetail.length) }}</span>
        </div>
        <!-- Multi select for the Drag & Drop -->
        <div class="text" v-if="this.dragedGhost">
            <div v-if="this.fileInfoDetail.length > 1 && !noSelectedItem">
                <span class="title">{{ $t('file_detail.selected_multiple') }}</span>
                <span class="count">{{this.fileInfoDetail.length}}  {{ $tc('file_detail.items', this.fileInfoDetail.length) }}</span>
            </div>

            <div v-if="this.fileInfoDetail.length < 2 || noSelectedItem">
                <span class="title">{{ this.dragedItem.name }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import {CheckSquareIcon} from "vue-feather-icons";
import {mapGetters} from 'vuex'

    export default {
        name:'MultiSelected',
        props: ['moveItem' , 'dragedGhost' , 'dragedItem'],
        components: {CheckSquareIcon},
        computed: {
            ...mapGetters(['fileInfoDetail']),
            // If the draged item is not in selected items
            noSelectedItem() {
                return  !this.fileInfoDetail.includes(this.dragedItem)
            }
        },
        
    }
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';
.ghost {
    // width: 200px !important;
    max-width: 300px;
    min-width: 250px;
    position: fixed;
    z-index: 10;
    pointer-events: none;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 7px 25px 1px rgba(0, 0, 0, 0.12);
    background:white;
}

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
    .ghost {
        background: $dark_mode_foreground;
    }
}
</style>
