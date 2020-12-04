<template>
        <MultiSelected :title="title" :subtitle="subtitle" id="multi-select-ui" v-show="dragged" />
</template>

<script>
import MultiSelected from '@/components/FilesView/MultiSelected'
import {mapGetters} from 'vuex'
import {events} from '@/bus'

    export default {
        name:"DragUI",
        components: {MultiSelected},
        computed: {
            ...mapGetters(['fileInfoDetail']),
            title(){
                if(this.fileInfoDetail.length > 1 && this.fileInfoDetail.includes(this.draggedItem)) {
                    return this.$t('file_detail.selected_multiple')
                }

                if((this.fileInfoDetail.length < 2 || !this.fileInfoDetail.includes(this.draggedItem)) && this.draggedItem ) {
                    return this.draggedItem.name
                }
            },
            subtitle(){
                if(this.fileInfoDetail.length > 1 && this.fileInfoDetail.includes(this.draggedItem) ) {
                    return this.fileInfoDetail.length + ' ' + this.$tc('file_detail.items', this.fileInfoDetail.length)
                }

                if((this.fileInfoDetail.length < 2 || !this.fileInfoDetail.includes(this.draggedItem)) && this.draggedItem && this.draggedItem.mimetype) {
                    return '.'+this.draggedItem.mimetype
                }
            },
        },
        data () {
            return {
                dragged: false,
                draggedItem: undefined
            }
        },
        mounted () {

            // Hnadle Drag & Drop Ghost show
            
            events.$on('dragstart', (data) => {
                setTimeout(() => {
                    this.dragged = true
                }, 50);
                this.draggedItem = data
            })
            events.$on('drop', () => {
                this.dragged = false
            })
            
        }
        
    }
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

#multi-select-ui {
    max-width: 300px;
    min-width: 250px;
    position: fixed;
    z-index: 10;
    pointer-events: none;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 7px 25px 1px rgba(0, 0, 0, 0.12);
    background:white;
     /deep/.text{
            .title {
                color: $text;
            }
            .count {
                color: $text-muted;
            }
        }
    /deep/.icon-wrapper {
            .icon { 
                stroke: $theme;
            }
        }
}

@media (prefers-color-scheme: dark) {
    #multi-select-ui {
        background: $dark_mode_foreground;
        /deep/.text {
            .title {
                color: $dark_mode_text_primary;
            }
            .count {
                color: $dark_mode_text_secondary;
            }
        }    
        /deep/.icon-wrapper {
            .icon { 
                stroke: $theme;
            }   
        }  
    }
}

</style>
