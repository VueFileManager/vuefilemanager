import i18n from '@/i18n/index'
import {debounce} from 'lodash'
import {events} from './bus'

const OasisHelpers = {
	install(Vue) {

		Vue.prototype.$editInvoice = function (entry) {
			this.$router.push({name: 'EditInvoice', params: {id: entry.id}})
			events.$emit('file-preview:hide')
		}

		Vue.prototype.$downloadInvoice = function (entry) {
			Vue.prototype.$downloadFile(entry.file_url, entry.name + '.' + entry.mimetype)
		}

		Vue.prototype.$deleteInvoice = function (entry) {
			events.$emit('confirm:open', {
				title: i18n.t('in.popup.delete_invoice.title', {number: entry.invoice_number}),
				message: i18n.t('in.popup.delete_invoice.message'),
				buttonColor: 'danger-solid',
				action: {
					id: entry.id,
					operation: 'delete-invoice'
				}
			})
		}

		Vue.prototype.$deleteClient = function (entry) {
			events.$emit('confirm:open', {
				title: i18n.t('in.popup.delete_client.title', {name: entry.name}),
				message: i18n.t('in.popup.delete_client.message'),
				buttonColor: 'danger-solid',
				action: {
					id: entry.id,
					operation: 'delete-client'
				}
			})
		}

		Vue.prototype.$goToCompany = function (entry) {
			this.$router.push({name: 'ClientDetail', params: {id: entry.client_id ?? entry.id}})

			events.$emit('file-preview:hide')
		}

		Vue.prototype.$searchInvoices = debounce(function (value) {

			if (value !== '' && typeof value !== 'undefined') {

				if (['regular-invoice', 'advance-invoice'].includes(this.$store.getters.currentFolder.location)) {
					this.$store.dispatch('getSearchResultForInvoices', value)
				} else {
					this.$store.dispatch('getSearchResultForClients', value)
				}
			} else if (typeof value !== 'undefined') {

				let locations = {
					'regular-invoice': 'getRegularInvoices',
					'advance-invoice': 'getAdvanceInvoices',
					'clients': 'getClients',
				}

				this.$store.dispatch(locations[this.$store.getters.currentFolder.location])

				this.$store.commit('CHANGE_SEARCHING_STATE', false)
			}
		}, 300)
	}
}

export default OasisHelpers