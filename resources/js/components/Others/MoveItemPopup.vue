<template>
    <PopupWrapper name="move">
        <!--Title-->
        <PopupHeader :title="$t('popup_move_item.title')" icon="move" />

		<!--Content-->
        <PopupContent v-if="pickedItem" class="sm:overflow-y-auto md:pb-0 sm:max-h-96 h-full pb-6">

            <!--Show Spinner when loading folders-->
            <Spinner v-if="isLoadingTree" />

			<!--Folder tree-->
            <div v-if="! isLoadingTree && navigation">
                <ThumbnailItem v-if="clipboard.length < 2 || isSelectedItem" class="mb-5" :item="pickedItem" info="location" />

                <TitlePreview
					class="mb-4"
					icon="check-square"
					:title="$t('file_detail.selected_multiple')"
					:subtitle="this.clipboard.length + ' ' + $tc('file_detail.items', this.clipboard.length)"
					v-if="clipboard.length > 1 && !isSelectedItem"
				/>
                    
                <TreeMenu class="-mx-4" :disabled-by-id="pickedItem" :depth="1" :nodes="items" v-for="items in navigation" :key="items.id" />
            </div>
        </PopupContent>

		<!--Actions-->
        <PopupActions>
            <ButtonBase
				class="w-full"
				@click.native="$closePopup()"
				button-style="secondary"
			>{{ $t('popup_move_item.cancel') }}
            </ButtonBase>
            <ButtonBase
				class="w-full"
				@click.native="moveItem"
				:button-style="selectedFolder ? 'theme' : 'secondary'"
			>{{ $t('popup_move_item.submit') }}
            </ButtonBase>
        </PopupActions>
    </PopupWrapper>
</template>

<script>
    import PopupWrapper from '/resources/js/components/Others/Popup/PopupWrapper'
	import PopupActions from '/resources/js/components/Others/Popup/PopupActions'
	import TitlePreview from '/resources/js/components/FilesView/TitlePreview'
	import PopupContent from '/resources/js/components/Others/Popup/PopupContent'
	import PopupHeader from '/resources/js/components/Others/Popup/PopupHeader'
	import ThumbnailItem from '/resources/js/components/Others/ThumbnailItem'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import Spinner from '/resources/js/components/FilesView/Spinner'
	import TreeMenu from '/resources/js/components/Others/TreeMenu'
	import {isArray, isNull} from 'lodash'
	import {mapGetters} from 'vuex'
	import {events} from '/resources/js/bus'

	export default {
		name: 'MoveItemPopup',
		components: {
			ThumbnailItem,
			TitlePreview,
			PopupWrapper,
			PopupActions,
			PopupContent,
			PopupHeader,
			ButtonBase,
			TreeMenu,
			Spinner,
		},
		computed: {
			...mapGetters([
				'navigation',
				'clipboard',
			]),
		},
		data() {
			return {
				selectedFolder: undefined,
				pickedItem: undefined,
				isLoadingTree: true,
				isSelectedItem: false
			}
		},
		methods: {
			moveItem() {
				// Prevent empty submit
				if (!this.selectedFolder) return

				// Prevent to move items to the same parent
				if (isArray(this.selectedFolder) && this.clipboard.find(item => item.parent_id === this.selectedFolder.id)) return

				// Move item
				if (!this.isSelectedItem) {
					this.$store.dispatch('moveItem', {to_item: this.selectedFolder, isSelectedItem: null})
				}

				if (this.isSelectedItem) {
					this.$store.dispatch('moveItem', {to_item: this.selectedFolder, isSelectedItem: this.pickedItem})
				}

				// Close popup
				events.$emit('popup:close')

				// If is mobile, close the selecting mod after done the move action
				if (this.$isMobile())
					this.$store.commit('DISABLE_MULTISELECT_MODE')
			},
		},
		mounted() {
			events.$on('pick-folder', folder => {

				if (folder.id === this.pickedItem.data.id) {
					this.selectedFolder = undefined

				} else if (!folder.id && folder.location === 'base') {
					this.selectedFolder = 'base'

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
				if (this.$isThisRoute(this.$route, ['SharedWithMe'])) {

					this.$store.dispatch('getTeamFolderTree').then(() => {
						this.isLoadingTree = false
					})
				} else {

					this.$store.dispatch('getFolderTree').then(() => {
						this.isLoadingTree = false
					})
				}

				// Store picked item
				if (!this.clipboard.includes(args.item[0])) {
					this.pickedItem = args.item[0]
					this.isSelectedItem = true
				}

				if (this.clipboard.includes(args.item[0])) {
					this.pickedItem = this.clipboard[0]
					this.isSelectedItem = false
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
