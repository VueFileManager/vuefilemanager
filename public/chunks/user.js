(window.webpackJsonp=window.webpackJsonp||[]).push([[53],{"+Pqb":function(e,a,t){"use strict";var i={name:"ProgressBar",props:["progress"]},s=(t("2jb4"),t("KHd+")),r=Object(s.a)(i,(function(){var e=this.$createElement,a=this._self._c||e;return a("div",{staticClass:"progress-bar"},[a("span",{style:{width:this.progress+"%"}})])}),[],!1,null,"9f98bf3e",null);a.a=r.exports},"2Sb1":function(e,a,t){"use strict";var i={name:"PageHeader",props:["title","canBack"],components:{ChevronLeftIcon:t("CjXH").g}},s=(t("qf9i"),t("KHd+")),r=Object(s.a)(i,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",{staticClass:"page-header"},[e.canBack?t("div",{staticClass:"go-back",on:{click:function(a){return e.$router.back()}}},[t("chevron-left-icon",{attrs:{size:"17"}})],1):e._e(),e._v(" "),t("div",{staticClass:"content"},[t("h1",{staticClass:"title"},[e._v(e._s(e.title))])])])}),[],!1,null,"aafe7e24",null);a.a=r.exports},"2jb4":function(e,a,t){"use strict";var i=t("i5u3");t.n(i).a},"3Idf":function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".page-header[data-v-aafe7e24]{display:flex;align-items:center;background:#fff;z-index:9;width:100%;position:-webkit-sticky;position:sticky;top:0;padding-top:20px;padding-bottom:20px}.page-header .title[data-v-aafe7e24]{font-size:1.125em;font-weight:700;color:#1b2539}.page-header .go-back[data-v-aafe7e24]{margin-right:10px;cursor:pointer}.page-header .go-back svg[data-v-aafe7e24]{vertical-align:middle;margin-top:-4px}@media only screen and (max-width:960px){.page-header .title[data-v-aafe7e24]{font-size:1.125em}}@media only screen and (max-width:690px){.page-header[data-v-aafe7e24]{display:none}}@media (prefers-color-scheme:dark){.page-header[data-v-aafe7e24]{background:#131414}.page-header .title[data-v-aafe7e24]{color:#bec6cf}.page-header .icon path[data-v-aafe7e24]{fill:#00bc7e}}",""])},"3OWm":function(e,a,t){"use strict";t.r(a);var i=t("CjXH"),s=t("xnZf"),r=t("D62o"),o=t("THmQ"),n=t("2Sb1"),d=t("kPoH"),l=t("zTYo"),c=t("L2JU"),f=t("vDqi"),v=t.n(f);function p(e,a){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);a&&(i=i.filter((function(a){return Object.getOwnPropertyDescriptor(e,a).enumerable}))),t.push.apply(t,i)}return t}function b(e,a,t){return a in e?Object.defineProperty(e,a,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[a]=t,e}var g={name:"Profile",components:{CreditCardIcon:i.m,HardDriveIcon:i.y,StorageItemDetail:s.a,SectionTitle:o.a,FileTextIcon:i.u,MobileHeader:r.a,PageHeader:n.a,ColorLabel:d.a,Trash2Icon:i.T,UserIcon:i.X,LockIcon:i.F,Spinner:l.a},computed:function(e){for(var a=1;a<arguments.length;a++){var t=null!=arguments[a]?arguments[a]:{};a%2?p(Object(t),!0).forEach((function(a){b(e,a,t[a])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(t)):p(Object(t)).forEach((function(a){Object.defineProperty(e,a,Object.getOwnPropertyDescriptor(t,a))}))}return e}({admin:function(){return this.$store.getters.user?this.$store.getters.user.data.attributes:void 0}},Object(c.b)(["config"])),data:function(){return{isLoading:!0,user:void 0}},methods:{fetchUser:function(){var e=this;v.a.get("/api/users/"+this.$route.params.id+"/detail").then((function(a){e.user=a.data,e.isLoading=!1}))}},created:function(){this.fetchUser()}},u=(t("6D6u"),t("KHd+")),m=Object(u.a)(g,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",{attrs:{id:"single-page"}},[e.isLoading?e._e():t("div",{attrs:{id:"page-content"}},[t("MobileHeader",{attrs:{title:e.$router.currentRoute.meta.title}}),e._v(" "),t("PageHeader",{attrs:{"can-back":!0,title:e.$router.currentRoute.meta.title}}),e._v(" "),t("div",{staticClass:"content-page"},[t("div",{staticClass:"user-thumbnail"},[t("div",{staticClass:"avatar"},[t("img",{attrs:{src:e.user.data.attributes.avatar,alt:e.user.data.attributes.name}})]),e._v(" "),t("div",{staticClass:"info"},[t("b",{staticClass:"name"},[e._v("\n                        "+e._s(e.user.data.attributes.name)+"\n                        "),t("ColorLabel",{attrs:{color:"purple"}},[e._v("\n                            "+e._s(e.user.data.attributes.role)+"\n                        ")])],1),e._v(" "),t("span",{staticClass:"email"},[e._v(e._s(e.user.data.attributes.email))])])]),e._v(" "),t("div",{staticClass:"menu-list-wrapper horizontal"},[t("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"UserDetail"}}},[t("div",{staticClass:"icon"},[t("user-icon",{attrs:{size:"17"}})],1),e._v(" "),t("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("admin_page_user.tabs.detail"))+"\n                    ")])]),e._v(" "),t("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"UserStorage"}}},[t("div",{staticClass:"icon"},[t("hard-drive-icon",{attrs:{size:"17"}})],1),e._v(" "),t("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("admin_page_user.tabs.storage"))+"\n                    ")])]),e._v(" "),e.config.isSaaS?t("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"UserSubscription"}}},[t("div",{staticClass:"icon"},[t("credit-card-icon",{attrs:{size:"17"}})],1),e._v(" "),t("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("admin_page_user.tabs.subscription"))+"\n                    ")])]):e._e(),e._v(" "),e.config.isSaaS?t("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"UserInvoices"}}},[t("div",{staticClass:"icon"},[t("file-text-icon",{attrs:{size:"17"}})],1),e._v(" "),t("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("admin_page_user.tabs.invoices"))+"\n                    ")])]):e._e(),e._v(" "),t("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"UserPassword"}}},[t("div",{staticClass:"icon"},[t("lock-icon",{attrs:{size:"17"}})],1),e._v(" "),t("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("admin_page_user.tabs.password"))+"\n                    ")])]),e._v(" "),e.user.data.attributes.name!==e.admin.name?t("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"UserDelete"}}},[t("div",{staticClass:"icon"},[t("trash2-icon",{attrs:{size:"17"}})],1),e._v(" "),t("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("admin_page_user.tabs.delete"))+"\n                    ")])]):e._e()],1),e._v(" "),t("router-view",{attrs:{user:e.user},on:{"reload-user":e.fetchUser}})],1)],1),e._v(" "),e.isLoading?t("div",{attrs:{id:"loader"}},[t("Spinner")],1):e._e()])}),[],!1,null,"3c66d64e",null);a.default=m.exports},"5BDI":function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".progress-bar[data-v-9f98bf3e]{width:100%;height:5px;background:#f4f5f6;margin-top:5px;border-radius:10px}.progress-bar span[data-v-9f98bf3e]{background:#00bc7e;display:block;height:100%;border-radius:10px;max-width:100%}@media (prefers-color-scheme:dark){.progress-bar[data-v-9f98bf3e]{background:#1e2024}}@media only screen and (min-width:680px) and (prefers-color-scheme:dark){.progress-bar[data-v-9f98bf3e]{background:#1e2024}}",""])},"6D6u":function(e,a,t){"use strict";var i=t("OW4Q");t.n(i).a},"92Jy":function(e,a,t){"use strict";var i=t("qphJ");t.n(i).a},F11w:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".color-label[data-v-ffcb2882]{text-transform:capitalize;font-size:.75em;display:inline-block;border-radius:6px;font-weight:700;padding:4px 6px}.color-label.purple[data-v-ffcb2882]{color:#9d66fe;background:rgba(157,102,254,.1)}.color-label.yellow[data-v-ffcb2882]{color:#ffbd2d;background:rgba(255,189,45,.1)}.color-label.green[data-v-ffcb2882]{color:#00bc7e;background:rgba(0,188,126,.1)}.color-label.red[data-v-ffcb2882]{color:#fd397a;background:rgba(253,57,122,.1)}",""])},GkQO:function(e,a,t){"use strict";var i=t("J5Z7");t.n(i).a},J5Z7:function(e,a,t){var i=t("F11w");"string"==typeof i&&(i=[[e.i,i,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,s);i.locals&&(e.exports=i.locals)},KPNK:function(e,a,t){var i=t("3Idf");"string"==typeof i&&(i=[[e.i,i,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,s);i.locals&&(e.exports=i.locals)},LE5O:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".detail-storage-item[data-v-56af1b6e]{margin-bottom:35px}.detail-storage-item.disk .icon circle[data-v-56af1b6e],.detail-storage-item.disk .icon line[data-v-56af1b6e],.detail-storage-item.disk .icon path[data-v-56af1b6e],.detail-storage-item.disk .icon polygon[data-v-56af1b6e],.detail-storage-item.disk .icon polyline[data-v-56af1b6e],.detail-storage-item.disk .icon rect[data-v-56af1b6e]{stroke:#1b2539}.detail-storage-item.disk .storage-progress[data-v-56af1b6e] span{background:#1b2539}.detail-storage-item.images .icon circle[data-v-56af1b6e],.detail-storage-item.images .icon line[data-v-56af1b6e],.detail-storage-item.images .icon path[data-v-56af1b6e],.detail-storage-item.images .icon polygon[data-v-56af1b6e],.detail-storage-item.images .icon polyline[data-v-56af1b6e],.detail-storage-item.images .icon rect[data-v-56af1b6e]{stroke:#9d66fe}.detail-storage-item.images .storage-progress[data-v-56af1b6e] span{background:#9d66fe}.detail-storage-item.videos .icon circle[data-v-56af1b6e],.detail-storage-item.videos .icon line[data-v-56af1b6e],.detail-storage-item.videos .icon path[data-v-56af1b6e],.detail-storage-item.videos .icon polygon[data-v-56af1b6e],.detail-storage-item.videos .icon polyline[data-v-56af1b6e],.detail-storage-item.videos .icon rect[data-v-56af1b6e]{stroke:#ffbd2d}.detail-storage-item.videos .storage-progress[data-v-56af1b6e] span{background:#ffbd2d}.detail-storage-item.audios .icon circle[data-v-56af1b6e],.detail-storage-item.audios .icon line[data-v-56af1b6e],.detail-storage-item.audios .icon path[data-v-56af1b6e],.detail-storage-item.audios .icon polygon[data-v-56af1b6e],.detail-storage-item.audios .icon polyline[data-v-56af1b6e],.detail-storage-item.audios .icon rect[data-v-56af1b6e]{stroke:#fe66a1}.detail-storage-item.audios .storage-progress[data-v-56af1b6e] span{background:#fe66a1}.detail-storage-item.documents .icon circle[data-v-56af1b6e],.detail-storage-item.documents .icon line[data-v-56af1b6e],.detail-storage-item.documents .icon path[data-v-56af1b6e],.detail-storage-item.documents .icon polygon[data-v-56af1b6e],.detail-storage-item.documents .icon polyline[data-v-56af1b6e],.detail-storage-item.documents .icon rect[data-v-56af1b6e]{stroke:#fe6057}.detail-storage-item.documents .storage-progress[data-v-56af1b6e] span{background:#fe6057}.detail-storage-item.others .icon circle[data-v-56af1b6e],.detail-storage-item.others .icon line[data-v-56af1b6e],.detail-storage-item.others .icon path[data-v-56af1b6e],.detail-storage-item.others .icon polygon[data-v-56af1b6e],.detail-storage-item.others .icon polyline[data-v-56af1b6e],.detail-storage-item.others .icon rect[data-v-56af1b6e]{stroke:#1b2539}.detail-storage-item.others .storage-progress[data-v-56af1b6e] span{background:#1b2539}.header-storage-item[data-v-56af1b6e]{display:flex;align-items:flex-start;margin-bottom:10px}.header-storage-item .icon[data-v-56af1b6e]{width:35px}.header-storage-item .type[data-v-56af1b6e]{font-size:.9375em;color:#1b2539}.header-storage-item .total-size[data-v-56af1b6e]{font-size:.625em;display:block;color:rgba(27,37,57,.7)}@media (prefers-color-scheme:dark){.header-storage-item .type[data-v-56af1b6e]{color:#bec6cf}.header-storage-item .total-size[data-v-56af1b6e]{color:#7d858c}.detail-storage-item.disk .icon circle[data-v-56af1b6e],.detail-storage-item.disk .icon line[data-v-56af1b6e],.detail-storage-item.disk .icon path[data-v-56af1b6e],.detail-storage-item.disk .icon polygon[data-v-56af1b6e],.detail-storage-item.disk .icon polyline[data-v-56af1b6e],.detail-storage-item.disk .icon rect[data-v-56af1b6e],.detail-storage-item.others .icon circle[data-v-56af1b6e],.detail-storage-item.others .icon line[data-v-56af1b6e],.detail-storage-item.others .icon path[data-v-56af1b6e],.detail-storage-item.others .icon polygon[data-v-56af1b6e],.detail-storage-item.others .icon polyline[data-v-56af1b6e],.detail-storage-item.others .icon rect[data-v-56af1b6e]{stroke:#41454e}.detail-storage-item.disk .storage-progress[data-v-56af1b6e] span,.detail-storage-item.others .storage-progress[data-v-56af1b6e] span{background:#41454e}}",""])},OW4Q:function(e,a,t){var i=t("RPZz");"string"==typeof i&&(i=[[e.i,i,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,s);i.locals&&(e.exports=i.locals)},RPZz:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".user-thumbnail[data-v-3c66d64e]{display:flex;align-items:center;cursor:pointer;padding-bottom:10px;padding-top:15px}.user-thumbnail .avatar[data-v-3c66d64e]{margin-right:20px;position:relative}.user-thumbnail .avatar img[data-v-3c66d64e]{line-height:0;width:62px;height:62px;border-radius:12px;z-index:1;position:relative}.user-thumbnail .avatar img.blurred[data-v-3c66d64e]{position:absolute;left:0;top:2px;z-index:0;filter:blur(8px);opacity:.5;top:0}.user-thumbnail .info .name[data-v-3c66d64e]{display:block;font-size:1.0625em;line-height:1}.user-thumbnail .info .email[data-v-3c66d64e]{color:rgba(27,37,57,.7);font-size:.875em}@media (prefers-color-scheme:dark){.user-thumbnail .info .email[data-v-3c66d64e]{color:#7d858c}}",""])},THmQ:function(e,a,t){"use strict";var i={name:"SectionTitle"},s=(t("92Jy"),t("KHd+")),r=Object(s.a)(i,(function(){var e=this.$createElement;return(this._self._c||e)("b",{staticClass:"text-label"},[this._t("default")],2)}),[],!1,null,"69d97df2",null);a.a=r.exports},b0xl:function(e,a,t){"use strict";var i=t("oyp5");t.n(i).a},i5u3:function(e,a,t){var i=t("5BDI");"string"==typeof i&&(i=[[e.i,i,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,s);i.locals&&(e.exports=i.locals)},kPoH:function(e,a,t){"use strict";var i={name:"ColorLabel",props:["color"]},s=(t("GkQO"),t("KHd+")),r=Object(s.a)(i,(function(){var e=this.$createElement;return(this._self._c||e)("b",{staticClass:"color-label",class:this.color},[this._t("default")],2)}),[],!1,null,"ffcb2882",null);a.a=r.exports},lW02:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".text-label[data-v-69d97df2]{font-size:.75em;color:#afafaf;font-weight:700;display:block;margin-bottom:20px}@media (prefers-color-scheme:dark){.text-label[data-v-69d97df2]{color:#00bc7e}}",""])},oyp5:function(e,a,t){var i=t("LE5O");"string"==typeof i&&(i=[[e.i,i,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,s);i.locals&&(e.exports=i.locals)},qf9i:function(e,a,t){"use strict";var i=t("KPNK");t.n(i).a},qphJ:function(e,a,t){var i=t("lW02");"string"==typeof i&&(i=[[e.i,i,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,s);i.locals&&(e.exports=i.locals)},xnZf:function(e,a,t){"use strict";var i=t("+Pqb"),s=t("CjXH"),r={name:"StorageItemDetail",props:["percentage","title","type","used"],components:{HardDriveIcon:s.y,FileTextIcon:s.u,ProgressBar:i.a,MusicIcon:s.K,VideoIcon:s.ab,ImageIcon:s.A,FileIcon:s.t}},o=(t("b0xl"),t("KHd+")),n=Object(o.a)(r,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("article",{staticClass:"detail-storage-item",class:e.type},[t("div",{staticClass:"header-storage-item"},[t("div",{staticClass:"icon"},["images"==e.type?t("image-icon",{attrs:{size:"23"}}):e._e(),e._v(" "),"videos"==e.type?t("video-icon",{attrs:{size:"23"}}):e._e(),e._v(" "),"audios"==e.type?t("music-icon",{attrs:{size:"23"}}):e._e(),e._v(" "),"documents"==e.type?t("file-text-icon",{attrs:{size:"23"}}):e._e(),e._v(" "),"others"==e.type?t("file-icon",{attrs:{size:"23"}}):e._e(),e._v(" "),"disk"==e.type?t("hard-drive-icon",{attrs:{size:"23"}}):e._e()],1),e._v(" "),t("div",{staticClass:"title"},[t("b",{staticClass:"type"},[e._v(e._s(e.title))]),e._v(" "),t("span",{staticClass:"total-size"},[e._v(e._s(e.used))])])]),e._v(" "),t("ProgressBar",{staticClass:"storage-progress",attrs:{progress:e.percentage}})],1)}),[],!1,null,"56af1b6e",null);a.a=n.exports}}]);