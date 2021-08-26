import {events} from "../bus";

const itemHelpers = {
	install(Vue) {
		Vue.prototype.$emptyTrash = function () {
			this.$store.dispatch('emptyTrash')
		}

		Vue.prototype.$shareCancel = function () {
			this.$store.dispatch('shareCancel')
		}

		Vue.prototype.$renameFileOrFolder = function (entry) {
			events.$emit('popup:open', {name: 'rename-item', item: entry})
		}

		Vue.prototype.$moveFileOrFolder = function (entry) {
			events.$emit('popup:open', {name: 'move', item: [entry]})
		}

		Vue.prototype.$createFolder = function () {
			this.$store.dispatch('createFolder', {
				name: this.$t('popup_create_folder.folder_default_name')
			})
		}

		Vue.prototype.$downloadSelection = function (item) {
			let clipboard = this.$store.getters.clipboard

			if (clipboard.length > 1 || (clipboard.length === 1 && clipboard[0].type === 'folder'))
				this.$store.dispatch('downloadZip')
			else {
				Vue.prototype.$downloadFile(item.file_url, item.name + '.' + item.mimetype)
			}
		}

		Vue.prototype.$dissolveTeamFolder = function () {
			events.$emit('confirm:open', {
				title: this.$t('Are you sure you want to dissolve this team?'),
				message: this.$t('All team members will lose access to your files and existing folder will be moved into your "Files" section.'),
				action: {
					id: 'token.id',
					operation: 'dissolve-team-folder'
				}
			})
		},

		Vue.prototype.$createTeamFolder = function (entry) {
			events.$emit('popup:open', {name: 'create-team-folder'})
		}

		Vue.prototype.$updateTeamFolder = function (entry) {
			events.$emit('popup:open', {name: 'update-team-folder', item: entry})
		}

		Vue.prototype.$removeFavourite = function (folder) {
			this.$store.dispatch('removeFromFavourites', folder)
		}

		Vue.prototype.$deleteFileOrFolder = function (entry) {
			if (!this.$store.getters.clipboard.includes(entry)) {
				this.$store.dispatch('deleteItem', entry)
			}

			if (this.$store.getters.clipboard.includes(entry)) {
				this.$store.dispatch('deleteItem')
			}
		}

		Vue.prototype.$restoreFileOrFolder = function (entry) {
			if (!this.$store.getters.clipboard.includes(entry))
				this.$store.dispatch('restoreItem', entry)

			if (this.$store.getters.clipboard.includes(entry))
				this.$store.dispatch('restoreItem', null)
		}

		Vue.prototype.$shareFileOrFolder = function (entry) {
			let event = entry.shared
				? 'share-edit'
				: 'share-create'

			events.$emit('popup:open', {
				name: event,
				item: entry
			})
		}
	}
}

export default itemHelpers