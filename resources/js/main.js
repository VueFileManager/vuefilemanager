require('./bootstrap');
import Vue from 'vue'
import VueI18n from 'vue-i18n'
import { languages } from './i18n/index.js'
import App from './App.vue'
import store from './store/index'
import Helpers from './helpers'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {
	faFileAudio,
	faFileVideo,
	faSyncAlt,
	faShare,
	faHome,
	faEyeSlash,
	faBars,
	faSearch,
	faEllipsisV,
	faChevronLeft,
	faChevronRight,
	faUpload,
	faFolderPlus,
	faTh,
	faThList,
	faInfo,
	faFolder,
	faFile,
	faFileImage,
	faTimes,
	faSort,
	faTrashAlt,
	faHdd,
	faEllipsisH,
	faPencilAlt,
} from '@fortawesome/free-solid-svg-icons'

library.add(
	faFileAudio,
	faFileVideo,
	faHdd,
	faSyncAlt,
	faShare,
	faHome,
	faEyeSlash,
	faBars,
	faSearch,
	faEllipsisV,
	faChevronLeft,
	faChevronRight,
	faUpload,
	faTrashAlt,
	faFolderPlus,
	faTh,
	faThList,
	faInfo,
	faFolder,
	faFile,
	faFileImage,
	faTimes,
	faSort,
	faEllipsisH,
	faPencilAlt,
)
Vue.component('FontAwesomeIcon', FontAwesomeIcon)

Vue.use(Helpers)
Vue.use(VueI18n)

Vue.config.productionTip = false

const messages = Object.assign(languages)

const i18n = new VueI18n({
	locale: config.locale,
	messages
})

var vueFileManager = new Vue({
	i18n,
	store,
	data: {
		config,
	},
	render: h => h(App)
}).$mount('#app')

