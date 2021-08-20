import {events} from "../bus";
import {debounce, isArray} from "lodash";
import axios from "axios";
import i18n from "../i18n";
import store from "../store";

const itemHelpers = {
	install(Vue) {
		Vue.prototype.$renameFileOrFolder = function (entry) {
			events.$emit('popup:open', {name: 'rename-item', item: entry})
		}

		Vue.prototype.$moveFileOrFolder = function (entry) {
			events.$emit('popup:open', {name: 'move', item: [entry]})
		}

		Vue.prototype.$createTeamFolder = function (entry) {
			events.$emit('popup:open', {name: 'create-team-folder'})
		}

		Vue.prototype.$updateTeamFolder = function (entry) {
			events.$emit('popup:open', {name: 'create-team-folder', item: [entry]})
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