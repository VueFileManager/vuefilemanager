(window.webpackJsonp=window.webpackJsonp||[]).push([[45],{"+NaO":function(t,a,e){"use strict";e.r(a);var n=e("CjXH"),i=e("D62o"),s=e("THmQ"),r=e("2Sb1"),o=e("zTYo"),l=e("vDqi"),c=e.n(l),d={name:"Plan",components:{UsersIcon:n.gb,Trash2Icon:n.ab,SettingsIcon:n.W,SectionTitle:s.a,MobileHeader:i.a,PageHeader:r.a,Spinner:o.a},data:function(){return{isLoading:!0,plan:void 0}},created:function(){var t=this;c.a.get("/api/admin/plans/"+this.$route.params.id).then((function(a){t.plan=a.data.data,t.isLoading=!1}))}},p=(e("2hc5"),e("KHd+")),m=Object(p.a)(d,(function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{attrs:{id:"single-page"}},[t.isLoading?t._e():e("div",{attrs:{id:"page-content"}},[e("MobileHeader",{attrs:{title:t.$t(t.$router.currentRoute.meta.title)}}),t._v(" "),e("PageHeader",{attrs:{"can-back":!0,title:t.$t(t.$router.currentRoute.meta.title)}}),t._v(" "),e("div",{staticClass:"content-page"},[e("div",{staticClass:"menu-list-wrapper horizontal"},[e("router-link",{staticClass:"menu-list-item link link border-bottom-theme",attrs:{replace:"",to:{name:"PlanSettings",params:{id:t.plan.id}}}},[e("div",{staticClass:"icon text-theme"},[e("settings-icon",{attrs:{size:"17"}})],1),t._v(" "),e("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("admin_page_plans.tabs.settings"))+"\n                    ")])]),t._v(" "),e("router-link",{staticClass:"menu-list-item link link border-bottom-theme",attrs:{replace:"",to:{name:"PlanSubscribers",params:{id:t.plan.id}}}},[e("div",{staticClass:"icon text-theme"},[e("users-icon",{attrs:{size:"17"}})],1),t._v(" "),e("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("admin_page_plans.tabs.subscribers"))+"\n                    ")])]),t._v(" "),e("router-link",{staticClass:"menu-list-item link link border-bottom-theme",attrs:{replace:"",to:{name:"PlanDelete",params:{id:t.plan.id}}}},[e("div",{staticClass:"icon text-theme"},[e("trash2-icon",{attrs:{size:"17"}})],1),t._v(" "),e("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("admin_page_plans.tabs.delete"))+"\n                    ")])])],1),t._v(" "),e("router-view",{attrs:{plan:t.plan}})],1)],1),t._v(" "),t.isLoading?e("div",{attrs:{id:"loader"}},[e("Spinner")],1):t._e()])}),[],!1,null,"9ae15358",null);a.default=m.exports},"2Sb1":function(t,a,e){"use strict";var n={name:"PageHeader",props:["title","canBack"],components:{ChevronLeftIcon:e("CjXH").g}},i=(e("JOXf"),e("KHd+")),s=Object(i.a)(n,(function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"page-header"},[t.canBack?e("div",{staticClass:"go-back",on:{click:function(a){return t.$router.back()}}},[e("chevron-left-icon",{attrs:{size:"17"}})],1):t._e(),t._v(" "),e("div",{staticClass:"content"},[e("h1",{staticClass:"title"},[t._v(t._s(t.title))])])])}),[],!1,null,"9fd0a424",null);a.a=s.exports},"2hc5":function(t,a,e){"use strict";var n=e("o/4R");e.n(n).a},"3eeM":function(t,a,e){(t.exports=e("I1BE")(!1)).push([t.i,".page-header[data-v-9fd0a424] {\n  display: flex;\n  align-items: center;\n  background: white;\n  z-index: 9;\n  width: 100%;\n  position: -webkit-sticky;\n  position: sticky;\n  top: 0;\n  padding-top: 20px;\n  padding-bottom: 20px;\n}\n.page-header .title[data-v-9fd0a424] {\n  font-size: 1.125em;\n  font-weight: 700;\n  color: #1B2539;\n}\n.page-header .go-back[data-v-9fd0a424] {\n  margin-right: 10px;\n  cursor: pointer;\n}\n.page-header .go-back svg[data-v-9fd0a424] {\n  vertical-align: middle;\n  margin-top: -4px;\n}\n@media only screen and (max-width: 960px) {\n.page-header .title[data-v-9fd0a424] {\n    font-size: 1.125em;\n}\n}\n@media only screen and (max-width: 690px) {\n.page-header[data-v-9fd0a424] {\n    display: none;\n}\n}\n@media (prefers-color-scheme: dark) {\n.page-header[data-v-9fd0a424] {\n    background: #141414;\n}\n.page-header .title[data-v-9fd0a424] {\n    color: #bec6cf;\n}\n.page-header .icon path[data-v-9fd0a424] {\n    fill: #00BC7E;\n}\n}\n",""])},GuTZ:function(t,a,e){(t.exports=e("I1BE")(!1)).push([t.i,".user-thumbnail[data-v-9ae15358] {\n  display: flex;\n  align-items: center;\n  cursor: pointer;\n}\n.user-thumbnail .avatar[data-v-9ae15358] {\n  margin-right: 20px;\n}\n.user-thumbnail .avatar img[data-v-9ae15358] {\n  line-height: 0;\n  width: 62px;\n  height: 62px;\n  border-radius: 12px;\n}\n.user-thumbnail .info .name[data-v-9ae15358] {\n  display: block;\n  font-size: 1.0625em;\n  line-height: 1;\n}\n.user-thumbnail .info .email[data-v-9ae15358] {\n  color: rgba(27, 37, 57, 0.7);\n  font-size: 0.875em;\n}\n@media (prefers-color-scheme: dark) {\n.user-thumbnail .info .email[data-v-9ae15358] {\n    color: #7d858c;\n}\n}\n",""])},JOXf:function(t,a,e){"use strict";var n=e("nr4+");e.n(n).a},THmQ:function(t,a,e){"use strict";var n={name:"SectionTitle"},i=(e("UHE7"),e("KHd+")),s=Object(i.a)(n,(function(){var t=this.$createElement;return(this._self._c||t)("b",{staticClass:"text-label"},[this._t("default")],2)}),[],!1,null,"6d799cf2",null);a.a=s.exports},UHE7:function(t,a,e){"use strict";var n=e("UmJ6");e.n(n).a},UmJ6:function(t,a,e){var n=e("vFyo");"string"==typeof n&&(n=[[t.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(t.exports=n.locals)},"nr4+":function(t,a,e){var n=e("3eeM");"string"==typeof n&&(n=[[t.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(t.exports=n.locals)},"o/4R":function(t,a,e){var n=e("GuTZ");"string"==typeof n&&(n=[[t.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(t.exports=n.locals)},vFyo:function(t,a,e){(t.exports=e("I1BE")(!1)).push([t.i,".text-label[data-v-6d799cf2] {\n  font-size: 0.75em;\n  color: #AFAFAF;\n  font-weight: 700;\n  display: block;\n  margin-bottom: 20px;\n}\n@media (prefers-color-scheme: dark) {\n.text-label[data-v-6d799cf2] {\n    color: #00BC7E;\n}\n}\n",""])}}]);