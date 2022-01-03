require("./bootstrap");
import Vue from "vue";
import i18n from "./i18n/index.js";
import VueRouter from "vue-router";
import router from "./router";
import App from "./App.vue";
import store from "./store";
import {events} from "./bus";

import SubscriptionHelpers from "./helpers/SubscriptionHelpers";
import ValidatorHelpers from "./helpers/ValidatorHelpers";
import functionHelpers from "./helpers/functionHelpers";
import itemHelpers from "./helpers/itemHelpers"

Vue.use(VueRouter);
Vue.use(SubscriptionHelpers);
Vue.use(ValidatorHelpers);
Vue.use(functionHelpers);
Vue.use(itemHelpers);

Vue.config.productionTip = false;

// Handle position of Drag & Drop Ghost
document.addEventListener('drag', event => {
  let multiSelect = document.getElementById('drag-ui')

  multiSelect.style.top = event.clientY + 20 + 'px'
  multiSelect.style.left = event.clientX + 'px'

},false)

// Handle for drop 
document.addEventListener("dragend", () => {
  events.$emit('drop')
}, false);

let vueFileManager = new Vue({
  i18n,
  store,
  router,
  data: {
    config,
  },
  render: (h) => h(App),
}).$mount("#app");


