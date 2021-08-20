<template>
	<div>
		<ContextMenu>

		</ContextMenu>
		<FileBrowser/>
	</div>
</template>

<script>
	import FileBrowser from '/resources/js/components/FilesView/FileBrowser'
	import ContextMenu from '/resources/js/components/FilesView/ContextMenu'

	import { mapGetters } from 'vuex'

	export default {
		name: 'FilesView',
		components: {
			FileBrowser,
			ContextMenu,
		},
		computed: {
			...mapGetters([

			]),
		},
		data() {
			return {

			}
		},
		created() {
			this.$store.dispatch('getFolder', this.$route.params.id)
		}
	}
</script>
