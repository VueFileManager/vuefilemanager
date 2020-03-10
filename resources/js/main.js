require('./bootstrap');
import Vue from 'vue'
import App from './App.vue'
import store from './store/index'
import Helpers from './helpers'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import axios from 'axios'
import {
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
} from '@fortawesome/free-solid-svg-icons'

library.add(
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
)
Vue.component('FontAwesomeIcon', FontAwesomeIcon)

Vue.use(Helpers)

Vue.config.productionTip = false

var vueFileManager = new Vue({
	store,
	data: {
		config,
	},
	render: h => h(App)
}).$mount('#app')

