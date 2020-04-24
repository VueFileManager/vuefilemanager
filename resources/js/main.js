require('./bootstrap');
import Vue from 'vue'
import VueRouter from 'vue-router'
import router from './router'
import i18n from './i18n/index.js'
import App from './App.vue'
import store from './store'
import Helpers from './helpers'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {
	faLock,
	faLockOpen,
	faDownload,
	faUserFriends,
	faCheck,
	faLink,
	faUserEdit,
	faUser,
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
	faChevronDown,
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
	faLock,
	faLockOpen,
	faDownload,
	faUserFriends,
	faCheck,
	faLink,
	faUserEdit,
	faUser,
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
	faChevronDown,
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

Vue.use(VueRouter)
Vue.use(Helpers)

Vue.config.productionTip = false

var vueFileManager = new Vue({
	i18n,
	store,
	router,
	data: {
		config,
	},
	render: h => h(App)
}).$mount('#app')

