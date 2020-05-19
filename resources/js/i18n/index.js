import Vue from 'vue';
import VueI18n from 'vue-i18n';

import en from './lang/en.json'
import sk from './lang/sk.json'
//import cn from './lang/cn.json'

Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: config.locale,
    messages: Object.assign({
        en, sk
    }),
});

export default i18n;
