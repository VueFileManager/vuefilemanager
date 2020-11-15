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

                <MultiSelected class="multiple-selected" moveItem="true" v-if="fileInfoDetail.length > 1 && !noSelectedItem"/> 
                    
                <TreeMenu :disabled-by-id="pickedItem.unique_id" :depth="1" :nodes="items" v-for="items in navigation" :key="items.unique_id"/>
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

                // Move item
                if(!this.noSelectedItem){
                    this.$store.dispatch('moveItem', {to_item:this.selectedFolder ,noSelectedItem: null})
                }

                if(this.noSelectedItem){
                    this.$store.dispatch('moveItem', {to_item:this.selectedFolder ,noSelectedItem:this.pickedItem})
                }
                // Close popup
                events.$emit('popup:close')
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
                // console.log(args.item[0])
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

                // this.pickedItem = args.item[0]
                // this.totalItems = args.item
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

    .item-thumbnail {
        margin-bottom: 20px;
    }
    .multiple-selected { 
        padding: 0 20px;;
        margin-bottom: 20px;
    }
</style>
