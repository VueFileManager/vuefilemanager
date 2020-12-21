<template>
    <PopupWrapper name="move">
        <!--Title-->
        <PopupHeader :title="$t('popup_move_item.title')" icon="move" />

        <!--Content-->
        <PopupContent type="height-limited" v-if="pickedItem">

            <!--Show Spinner when loading folders-->
            <Spinner v-if="isLoadingTree"/>

            <!--Folder tree-->
            <div v-if="! isLoadingTree && navigation">
                <ThumbnailItem v-if="fileInfoDetail.length < 2 || noSelectedItem" class="item-thumbnail" :item="pickedItem" info="location"/>

                <MultiSelected  class="multiple-selected" 
                                :title="$t('file_detail.selected_multiple')" 
                                :subtitle="this.fileInfoDetail.length + ' ' + $tc('file_detail.items', this.fileInfoDetail.length)"
                                v-if="fileInfoDetail.length > 1 && !noSelectedItem"/> 
                    
                <TreeMenu :disabled-by-id="pickedItem" :depth="1" :nodes="items" v-for="items in navigation" :key="items.unique_id"/>
            </div>
        </PopupContent>

        <!--Actions-->
        <PopupActions>
            <ButtonBase
                    class="popup-button"
                    @click.native="$closePopup()"
                    button-style="secondary"
            >{{ $t('popup_move_item.cancel') }}
            </ButtonBase>
            <ButtonBase
                    class="popup-button"
                    @click.native="moveItem"
                    :button-style="selectedFolder ? 'theme' : 'secondary'"
            >{{ $t('popup_move_item.submit') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
    import PopupWrapper from '@/components/Others/Popup/PopupWrapper'
    import PopupActions from '@/components/Others/Popup/PopupActions'
    import MultiSelected from '@/components/FilesView/MultiSelected'
    import PopupContent from '@/components/Others/Popup/PopupContent'
    import PopupHeader from '@/components/Others/Popup/PopupHeader'
    import ThumbnailItem from '@/components/Others/ThumbnailItem'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import Spinner from '@/components/FilesView/Spinner'
    import TreeMenu from '@/components/Others/TreeMenu'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'MoveItem',
        components: {
            ThumbnailItem,
            PopupWrapper,
            MultiSelected,
            PopupActions,
            PopupContent,
            PopupHeader,
            ButtonBase,
            TreeMenu,
            Spinner,
        },
        computed: {
            ...mapGetters(['navigation', 'fileInfoDetail']),
        },
        data() {
            return {
                selectedFolder: undefined,
                pickedItem: undefined,
                isLoadingTree: true,
                noSelectedItem: false
            }
        },
        methods: {
            moveItem() {
                // Prevent empty submit
                if (! this.selectedFolder) return

                //Prevent to move items to the same parent 
                if(this.fileInfoDetail.find(item => item.parent_id === this.selectedFolder.unique_id)) return

                // Move item 
                if(!this.noSelectedItem){
                    this.$store.dispatch('moveItem', {to_item:this.selectedFolder ,noSelectedItem: null})
                }

                if(this.noSelectedItem){
                    this.$store.dispatch('moveItem', {to_item:this.selectedFolder ,noSelectedItem:this.pickedItem})
                }
                
                // Close popup
                events.$emit('popup:close')

                // If is mobile, close the selecting mod after done the move action
                if(this.$isMobile())
                    events.$emit('mobileSelecting:stop')
            },
        },
        mounted() {

            // Select folder in tree
            events.$on('pick-folder', folder => {

                if (folder.unique_id === this.pickedItem.unique_id) {
                    this.selectedFolder = undefined
                } else {
                    this.selectedFolder = folder
                }
            })

            // Show Move item popup
            events.$on('popup:open', args => {

                if (args.name !== 'move') return
                // Show tree spinner
                this.isLoadingTree = true

                // Get folder tree and hide spinner
                this.$store.dispatch('getFolderTree').then(() => {
                    this.isLoadingTree = false
                })

                // Store picked item
                if(!this.fileInfoDetail.includes(args.item[0])){
                    this.pickedItem = args.item[0]
                    this.noSelectedItem = true
                }
                if(this.fileInfoDetail.includes(args.item[0])){
                    this.pickedItem = this.fileInfoDetail[0]
                    this.noSelectedItem = false
                }
            })

            // Close popup
            events.$on('popup:close', () => {

                // Clear selected folder
                setTimeout(() => {
                    this.selectedFolder = undefined
                }, 150)
            })
        }
    }
</script>

<style scoped lang="scss">
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

    .item-thumbnail {
        margin-bottom: 20px;
    }
    .multiple-selected { 
        padding: 0 20px;;
        margin-bottom: 20px;
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
    .multiple-selected {
        /deep/.text {
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
