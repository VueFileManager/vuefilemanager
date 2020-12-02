<template>
    <div class="wrapper">
        <div class="icon-wrapper">   
            <CheckSquareIcon class="icon" size="21"/>
        </div>
        
        <div class="text" >
            <span class="title">{{title }}</span>
            <span class="count">{{subtitle }}</span>
        </div>
    </div>
</template>

<script>
import {CheckSquareIcon} from "vue-feather-icons";
import {mapGetters} from 'vuex'
import {events} from '@/bus'


    export default {
        name:'MultiSelected',
        props: [ 'title' , 'subtitle' ],
        components: {CheckSquareIcon},
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
}
</style>
