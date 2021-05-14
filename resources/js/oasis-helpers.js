import i18n from '@/i18n/index'
import {debounce} from 'lodash'
import {events} from './bus'
import store from "./store";
import router from "./router";

const OasisHelpers = {
	install(Vue) {

		Vue.prototype.$goToInvoice = function () {
			router.push({name: 'InvoicesList'})

			store.commit('STORE_CURRENT_FOLDER', {
				name: 'Invoices',
				id: undefined,
				location: 'regular-invoice',
			})

			store.dispatch('getRegularInvoices')
		}

		Vue.prototype.$getInvoiceDataByLocation = function () {

			let currentLocation = store.getters.currentFolder && store.getters.currentFolder.location
				? store.getters.currentFolder.location
				: undefined

			let actions = {
				'regular-invoice': 'getRegularInvoices',
				'advance-invoice': 'getAdvanceInvoices',
				'clients': 'getClients',
			}

			store.dispatch(actions[currentLocation])
		}

		Vue.prototype.$shareInvoice = function (entry) {
			events.$emit('popup:open', {
				name: 'share-invoice',
				item: entry
			})
		}

		Vue.prototype.$editInvoice = function (entry) {
			router.push({name: 'EditInvoice', params: {id: entry.id}})
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
			router.push({name: 'ClientDetail', params: {id: entry.client_id ?? entry.id}})

			events.$emit('file-preview:hide')
		}

		Vue.prototype.$searchInvoices = debounce(function (value) {

			if (value !== '' && typeof value !== 'undefined') {

				if (['regular-invoice', 'advance-invoice'].includes(store.getters.currentFolder.location)) {
					store.dispatch('getSearchResultForInvoices', value)
				} else {
					store.dispatch('getSearchResultForClients', value)
				}
			} else if (typeof value !== 'undefined') {

				let locations = {
					'regular-invoice': 'getRegularInvoices',
					'advance-invoice': 'getAdvanceInvoices',
					'clients': 'getClients',
				}

				store.dispatch(locations[store.getters.currentFolder.location])

				store.commit('CHANGE_SEARCHING_STATE', false)
			}
		}, 300)
	}
}

export default OasisHelpers