(window.webpackJsonp=window.webpackJsonp||[]).push([[19],{"2Sb1":function(t,e,n){"use strict";var a={name:"PageHeader",props:["title","canBack"],components:{ChevronLeftIcon:n("CjXH").g}},i=(n("JOXf"),n("KHd+")),s=Object(i.a)(a,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"page-header"},[t.canBack?n("div",{staticClass:"go-back",on:{click:function(e){return t.$router.back()}}},[n("chevron-left-icon",{attrs:{size:"17"}})],1):t._e(),t._v(" "),n("div",{staticClass:"content"},[n("h1",{staticClass:"title"},[t._v(t._s(t.title))])])])}),[],!1,null,"9fd0a424",null);e.a=s.exports},"3eeM":function(t,e,n){(t.exports=n("I1BE")(!1)).push([t.i,".page-header[data-v-9fd0a424] {\n  display: flex;\n  align-items: center;\n  background: white;\n  z-index: 9;\n  width: 100%;\n  position: -webkit-sticky;\n  position: sticky;\n  top: 0;\n  padding-top: 20px;\n  padding-bottom: 20px;\n}\n.page-header .title[data-v-9fd0a424] {\n  font-size: 1.125em;\n  font-weight: 700;\n  color: #1B2539;\n}\n.page-header .go-back[data-v-9fd0a424] {\n  margin-right: 10px;\n  cursor: pointer;\n}\n.page-header .go-back svg[data-v-9fd0a424] {\n  vertical-align: middle;\n  margin-top: -4px;\n}\n@media only screen and (max-width: 960px) {\n.page-header .title[data-v-9fd0a424] {\n    font-size: 1.125em;\n}\n}\n@media only screen and (max-width: 690px) {\n.page-header[data-v-9fd0a424] {\n    display: none;\n}\n}\n@media (prefers-color-scheme: dark) {\n.page-header[data-v-9fd0a424] {\n    background: #131414;\n}\n.page-header .title[data-v-9fd0a424] {\n    color: #bec6cf;\n}\n.page-header .icon path[data-v-9fd0a424] {\n    fill: #00BC7E;\n}\n}\n",""])},"6NZK":function(t,e,n){(t.exports=n("I1BE")(!1)).push([t.i,"",""])},"Dj+f":function(t,e,n){"use strict";var a=n("aDPF");n.n(a).a},JOXf:function(t,e,n){"use strict";var a=n("nr4+");n.n(a).a},OLOt:function(t,e,n){"use strict";n.r(e);var a=n("CjXH"),i=n("D62o"),s=n("THmQ"),r=n("2Sb1"),o=n("L2JU");function c(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function l(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var d={name:"AppSettings",components:{HomeIcon:a.B,CreditCardIcon:a.n,CodeIcon:a.l,MailIcon:a.I,FileTextIcon:a.w,EyeIcon:a.t,UsersIcon:a.fb,Trash2Icon:a.Z,SettingsIcon:a.V,SectionTitle:s.a,MobileHeader:i.a,PageHeader:r.a},computed:function(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?c(Object(n),!0).forEach((function(e){l(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):c(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}({},Object(o.b)(["config"]))},p=(n("Dj+f"),n("KHd+")),m=Object(p.a)(d,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{attrs:{id:"single-page"}},[n("div",{attrs:{id:"page-content"}},[n("MobileHeader",{attrs:{title:t.$router.currentRoute.meta.title}}),t._v(" "),n("PageHeader",{attrs:{"can-back":!0,title:t.$router.currentRoute.meta.title}}),t._v(" "),n("div",{staticClass:"content-page"},[n("div",{staticClass:"menu-list-wrapper horizontal"},[n("router-link",{staticClass:"menu-list-item link border-bottom-theme",attrs:{replace:"",to:{name:"AppOthers"}}},[n("div",{staticClass:"icon text-theme"},[n("settings-icon",{attrs:{size:"17"}})],1),t._v(" "),n("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("admin_settings.tabs.others"))+"\n                    ")])]),t._v(" "),n("router-link",{staticClass:"menu-list-item link link border-bottom-theme",attrs:{replace:"",to:{name:"AppAppearance"}}},[n("div",{staticClass:"icon text-theme"},[n("eye-icon",{attrs:{size:"17"}})],1),t._v(" "),n("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("admin_settings.tabs.appearance"))+"\n                    ")])]),t._v(" "),t.config.isSaaS?n("router-link",{staticClass:"menu-list-item link link border-bottom-theme",attrs:{replace:"",to:{name:"AppBillings"}}},[n("div",{staticClass:"icon text-theme"},[n("file-text-icon",{attrs:{size:"17"}})],1),t._v(" "),n("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("admin_settings.tabs.billings"))+"\n                    ")])]):t._e(),t._v(" "),t.config.isSaaS?n("router-link",{staticClass:"menu-list-item link link border-bottom-theme",attrs:{replace:"",to:{name:"AppPayments"}}},[n("div",{staticClass:"icon text-theme"},[n("credit-card-icon",{attrs:{size:"17"}})],1),t._v(" "),n("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("admin_settings.tabs.payments"))+"\n                    ")])]):t._e(),t._v(" "),n("router-link",{staticClass:"menu-list-item link link border-bottom-theme",attrs:{replace:"",to:{name:"AppIndex"}}},[n("div",{staticClass:"icon text-theme"},[n("home-icon",{attrs:{size:"17"}})],1),t._v(" "),n("div",{staticClass:"label text-theme"},[t._v("\n                        Homepage\n                    ")])]),t._v(" "),n("router-link",{staticClass:"menu-list-item link link border-bottom-theme",attrs:{replace:"",to:{name:"AppEmail"}}},[n("div",{staticClass:"icon text-theme"},[n("mail-icon",{attrs:{size:"17"}})],1),t._v(" "),n("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("admin_settings.tabs.email"))+"\n                    ")])])],1),t._v(" "),n("router-view")],1)],1)])}),[],!1,null,"3c70dcea",null);e.default=m.exports},THmQ:function(t,e,n){"use strict";var a={name:"SectionTitle"},i=(n("UHE7"),n("KHd+")),s=Object(i.a)(a,(function(){var t=this.$createElement;return(this._self._c||t)("b",{staticClass:"text-label"},[this._t("default")],2)}),[],!1,null,"6d799cf2",null);e.a=s.exports},UHE7:function(t,e,n){"use strict";var a=n("UmJ6");n.n(a).a},UmJ6:function(t,e,n){var a=n("vFyo");"string"==typeof a&&(a=[[t.i,a,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(a,i);a.locals&&(t.exports=a.locals)},aDPF:function(t,e,n){var a=n("6NZK");"string"==typeof a&&(a=[[t.i,a,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(a,i);a.locals&&(t.exports=a.locals)},"nr4+":function(t,e,n){var a=n("3eeM");"string"==typeof a&&(a=[[t.i,a,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(a,i);a.locals&&(t.exports=a.locals)},vFyo:function(t,e,n){(t.exports=n("I1BE")(!1)).push([t.i,".text-label[data-v-6d799cf2] {\n  font-size: 0.75em;\n  color: #AFAFAF;\n  font-weight: 700;\n  display: block;\n  margin-bottom: 20px;\n}\n@media (prefers-color-scheme: dark) {\n.text-label[data-v-6d799cf2] {\n    color: #00BC7E;\n}\n}\n",""])}}]);