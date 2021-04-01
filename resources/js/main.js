require("./bootstrap");
import Vue from "vue";
import i18n from "./i18n/index.js";
import VueRouter from "vue-router";
import router from "./router";
import App from "./App.vue";
import store from "./store";
import {events} from "./bus";
import Helpers from "./helpers";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
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
} from "@fortawesome/free-solid-svg-icons";

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
  faPencilAlt
);
Vue.component("FontAwesomeIcon", FontAwesomeIcon);

Vue.use(VueRouter);
Vue.use(Helpers);

Vue.config.productionTip = false;

// Handle position of Drag & Drop Ghost
document.addEventListener('drag', event => {
  let multiSelect = document.getElementById('multi-select-ui')

  multiSelect.style.top = event.clientY + 20 + 'px'
  multiSelect.style.left = event.clientX + 'px'

},false)

// Handle for drop 
document.addEventListener("dragend", () => {
  events.$emit('drop')
}, false);

var vueFileManager = new Vue({
  i18n,
  store,
  router,
  data: {
    config,
  },
  render: (h) => h(App),
}).$mount("#app");


