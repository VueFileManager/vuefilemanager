(window.webpackJsonp=window.webpackJsonp||[]).push([[35],{"2Sb1":function(t,e,a){"use strict";var i={name:"PageHeader",props:["title","canBack"],components:{ChevronLeftIcon:a("CjXH").g}},n=(a("qf9i"),a("KHd+")),r=Object(n.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"page-header"},[t.canBack?a("div",{staticClass:"go-back",on:{click:function(e){return t.$router.back()}}},[a("chevron-left-icon",{attrs:{size:"17"}})],1):t._e(),t._v(" "),a("div",{staticClass:"content"},[a("h1",{staticClass:"title"},[t._v(t._s(t.title))])])])}),[],!1,null,"aafe7e24",null);e.a=r.exports},"35KB":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".text-label[data-v-4c36e0a9]{padding-left:25px;font-size:.75em;color:#afafaf;font-weight:700;display:block;margin-bottom:5px}@media only screen and (max-width:1024px){.text-label[data-v-4c36e0a9]{padding-left:20px}}@media (prefers-color-scheme:dark){.text-label[data-v-4c36e0a9]{color:#00bc7e}}",""])},"3Idf":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".page-header[data-v-aafe7e24]{display:flex;align-items:center;background:#fff;z-index:9;width:100%;position:-webkit-sticky;position:sticky;top:0;padding-top:20px;padding-bottom:20px}.page-header .title[data-v-aafe7e24]{font-size:1.125em;font-weight:700;color:#1b2539}.page-header .go-back[data-v-aafe7e24]{margin-right:10px;cursor:pointer}.page-header .go-back svg[data-v-aafe7e24]{vertical-align:middle;margin-top:-4px}@media only screen and (max-width:960px){.page-header .title[data-v-aafe7e24]{font-size:1.125em}}@media only screen and (max-width:690px){.page-header[data-v-aafe7e24]{display:none}}@media (prefers-color-scheme:dark){.page-header[data-v-aafe7e24]{background:#131414}.page-header .title[data-v-aafe7e24]{color:#bec6cf}.page-header .icon path[data-v-aafe7e24]{fill:#00bc7e}}",""])},"5cfm":function(t,e,a){"use strict";var i=a("psU+");a.n(i).a},"6gqG":function(t,e,a){var i=a("ydEr");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},"7OGm":function(t,e,a){"use strict";var i=a("6gqG");a.n(i).a},F11w:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".color-label[data-v-ffcb2882]{text-transform:capitalize;font-size:.75em;display:inline-block;border-radius:6px;font-weight:700;padding:4px 6px}.color-label.purple[data-v-ffcb2882]{color:#9d66fe;background:rgba(157,102,254,.1)}.color-label.yellow[data-v-ffcb2882]{color:#ffbd2d;background:rgba(255,189,45,.1)}.color-label.green[data-v-ffcb2882]{color:#00bc7e;background:rgba(0,188,126,.1)}.color-label.red[data-v-ffcb2882]{color:#fd397a;background:rgba(253,57,122,.1)}",""])},GgfX:function(t,e,a){"use strict";a.r(e);var i=a("LtV2"),n=a("hXay"),r=a("Rbea"),o=a("D62o"),s=a("Nv84"),l=a("KnjL"),c=a("2Sb1"),d=a("kPoH"),p=a("zTYo"),u=a("L2JU"),f=a("CjXH");function v(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(t);e&&(i=i.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,i)}return a}function b(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?v(Object(a),!0).forEach((function(e){m(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):v(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}function m(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var g={name:"Settings",components:{ContentSidebar:i.a,CreditCardIcon:f.n,UserImageInput:r.a,HardDriveIcon:f.z,FileTextIcon:f.v,MobileHeader:o.a,ContentGroup:n.a,ButtonBase:s.a,ColorLabel:d.a,PageHeader:c.a,CloudIcon:f.j,UserIcon:f.Z,LockIcon:f.G,Spinner:p.a,InfoBox:l.a},computed:b(b({},Object(u.b)(["user","config"])),{},{subscriptionStatus:function(){return this.user.data.attributes.subscription?this.$t("global.premium"):this.$t("global.free")},subscriptionColor:function(){return this.user.data.attributes.subscription?"green":"purple"},canShowSubscriptionSettings:function(){return this.config.isSaaS&&this.config.app_payments_active},canShowUpgradeWarning:function(){return this.config.storageLimit&&this.user.relationships.storage.data.attributes.used>95},canShowIncompletePayment:function(){return this.user.data.attributes.incomplete_payment}}),data:function(){return{avatar:void 0,isLoading:!1}}},h=(a("5cfm"),a("KHd+")),x=Object(h.a)(g,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("section",{attrs:{id:"viewport"}},[a("ContentSidebar",[a("ContentGroup",{staticClass:"navigator",attrs:{title:"Menu"}},[a("div",{staticClass:"menu-list-wrapper vertical"},[a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"Profile"}}},[a("div",{staticClass:"icon"},[a("user-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("menu.profile"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"Storage"}}},[a("div",{staticClass:"icon"},[a("hard-drive-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("menu.storage"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"Password"}}},[a("div",{staticClass:"icon"},[a("lock-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("menu.password"))+"\n                    ")])])],1)]),t._v(" "),t.canShowSubscriptionSettings?a("ContentGroup",{staticClass:"navigator",attrs:{title:"Subscription"}},[a("div",{staticClass:"menu-list-wrapper vertical"},[a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"Subscription"}}},[a("div",{staticClass:"icon"},[a("cloud-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("menu.subscription"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"PaymentMethods"}}},[a("div",{staticClass:"icon"},[a("credit-card-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("menu.payment_cards"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"Invoice"}}},[a("div",{staticClass:"icon"},[a("file-text-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("menu.invoices"))+"\n                    ")])])],1)]):t._e()],1),t._v(" "),t.user?a("div",{attrs:{id:"single-page"}},[t.isLoading?t._e():a("div",{staticClass:"medium-width",attrs:{id:"page-content"}},[a("MobileHeader",{attrs:{title:t.$router.currentRoute.meta.title}}),t._v(" "),a("div",{staticClass:"content-page"},[a("div",{staticClass:"page-detail-headline"},[a("div",{staticClass:"user-thumbnail"},[a("div",{staticClass:"avatar"},[a("UserImageInput",{attrs:{avatar:t.user.data.attributes.avatar},model:{value:t.avatar,callback:function(e){t.avatar=e},expression:"avatar"}})],1),t._v(" "),a("div",{staticClass:"info"},[a("b",{staticClass:"name"},[t._v("\n                                "+t._s(t.user.data.attributes.name)+"\n                                "),t.config.isSaaS?a("ColorLabel",{attrs:{color:t.subscriptionColor}},[t._v("\n                                    "+t._s(t.subscriptionStatus)+"\n                                ")]):t._e()],1),t._v(" "),a("span",{staticClass:"email"},[t._v(t._s(t.user.data.attributes.email))])])]),t._v(" "),t.config.storageLimit&&t.config.isSaaS&&t.config.app_payments_active&&!t.canShowIncompletePayment?a("div",{staticClass:"headline-actions"},[a("router-link",{attrs:{to:{name:"UpgradePlan"}}},[a("ButtonBase",{staticClass:"upgrade-button",attrs:{"button-style":"secondary",type:"button"}},[t._v("\n                                "+t._s(t.$t("global.upgrade_plan"))+"\n                            ")])],1)],1):t._e()]),t._v(" "),t.canShowIncompletePayment?a("InfoBox",{staticClass:"message-box",attrs:{type:"error"}},[a("i18n",{attrs:{path:"incomplete_payment.description",tag:"p"}},[a("a",{attrs:{href:t.user.data.attributes.incomplete_payment}},[t._v(t._s(t.$t("incomplete_payment.href")))])])],1):t._e(),t._v(" "),t.canShowUpgradeWarning&&!t.canShowIncompletePayment?a("InfoBox",{staticClass:"message-box",attrs:{type:"error"}},[a("p",[t._v(t._s(t.$t("upgrade_banner.title")))])]):t._e(),t._v(" "),a("router-view",{attrs:{user:t.user}})],1)],1),t._v(" "),t.isLoading?a("div",{attrs:{id:"loader"}},[a("Spinner")],1):t._e()]):t._e()],1)}),[],!1,null,"e77fd34a",null);e.default=x.exports},GkQO:function(t,e,a){"use strict";var i=a("J5Z7");a.n(i).a},J5Z7:function(t,e,a){var i=a("F11w");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},JHkA:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".page-detail-headline[data-v-e77fd34a]{display:flex;justify-content:space-between;margin-bottom:50px;margin-top:30px}.user-thumbnail[data-v-e77fd34a]{display:flex;align-items:center;cursor:pointer}.user-thumbnail .avatar[data-v-e77fd34a]{margin-right:20px}.user-thumbnail .avatar img[data-v-e77fd34a]{line-height:0;width:62px;height:62px;border-radius:12px;z-index:1;position:relative}.user-thumbnail .info .name[data-v-e77fd34a]{display:block;font-size:1.0625em;line-height:1}.user-thumbnail .info .email[data-v-e77fd34a]{color:rgba(27,37,57,.7);font-size:.875em}.message-box[data-v-e77fd34a]{margin-top:-15px}@media (prefers-color-scheme:dark){.user-thumbnail .info .name[data-v-e77fd34a]{color:#bec6cf}.user-thumbnail .info .email[data-v-e77fd34a]{color:#7d858c}}@media only screen and (max-width:690px){.page-detail-headline[data-v-e77fd34a]{display:block;margin-bottom:30px;margin-top:10px}.page-detail-headline .headline-actions[data-v-e77fd34a]{margin-top:20px}.page-detail-headline .headline-actions .upgrade-button[data-v-e77fd34a]{width:100%}}",""])},JoIu:function(t,e,a){var i=a("nSuU");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},KPNK:function(t,e,a){var i=a("3Idf");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},KnjL:function(t,e,a){"use strict";var i={name:"InfoBox",props:["type"]},n=(a("7OGm"),a("KHd+")),r=Object(n.a)(i,(function(){var t=this.$createElement;return(this._self._c||t)("div",{staticClass:"info-box",class:this.type},[this._t("default")],2)}),[],!1,null,"bf43be5e",null);e.a=r.exports},LtV2:function(t,e,a){"use strict";var i={name:"ContentSidebar"},n=(a("u5fQ"),a("KHd+")),r=Object(n.a)(i,(function(){var t=this.$createElement;return(this._self._c||t)("section",{staticClass:"content-sidebar"},[this._t("default")],2)}),[],!1,null,"5101d4ac",null);e.a=r.exports},Q8fg:function(t,e,a){var i=a("35KB");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},QNGj:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".dropzone[data-v-fa58d4b2]{position:relative;line-height:0}.dropzone input[type=file][data-v-fa58d4b2]{opacity:0;position:absolute;top:0;left:0;right:0;bottom:0;z-index:2;width:100%;cursor:pointer}.dropzone .image-preview[data-v-fa58d4b2]{width:62px;height:62px;-o-object-fit:cover;object-fit:cover;border-radius:8px;z-index:1;position:relative}.dropzone .blurred[data-v-fa58d4b2]{position:absolute;left:0;top:2px;z-index:0;filter:blur(8px);opacity:.5}",""])},Rbea:function(t,e,a){"use strict";var i={props:["label","name","avatar","info","error"],data:function(){return{imagePreview:void 0}},watch:{imagePreview:function(t){this.$store.commit("UPDATE_AVATAR",t)}},methods:{showImagePreview:function(t){var e=this,a=t.target.files[0].name,i=a.substring(a.lastIndexOf(".")+1).toLowerCase();if(["png","jpg","jpeg"].includes(i)){var n=t.target.files[0],r=new FileReader;r.onload=function(){return e.imagePreview=r.result},r.readAsDataURL(n),this.$updateImage("/user/profile","avatar",t.target.files[0])}else alert(this.$t("validation_errors.wrong_image"))}},created:function(){this.avatar&&(this.imagePreview=this.avatar)}},n=(a("WSFd"),a("KHd+")),r=Object(n.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"dropzone",class:{"is-error":t.error}},[a("input",{ref:"file",staticClass:"dummy",attrs:{type:"file",name:t.name},on:{change:function(e){return t.showImagePreview(e)}}}),t._v(" "),t.imagePreview?a("img",{ref:"image",staticClass:"image-preview",attrs:{src:t.imagePreview}}):t._e(),t._v(" "),t.imagePreview?a("img",{ref:"image",staticClass:"image-preview blurred",attrs:{src:t.imagePreview}}):t._e()])}),[],!1,null,"fa58d4b2",null);e.a=r.exports},SqfT:function(t,e,a){"use strict";var i=a("cf9B");a.n(i).a},WSFd:function(t,e,a){"use strict";var i=a("z2eR");a.n(i).a},cf9B:function(t,e,a){var i=a("lsZg");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},hXay:function(t,e,a){"use strict";var i={name:"TextLabel"},n=(a("wu+2"),a("KHd+")),r=Object(n.a)(i,(function(){var t=this.$createElement;return(this._self._c||t)("b",{staticClass:"text-label"},[this._t("default")],2)}),[],!1,null,"4c36e0a9",null).exports,o={name:"ContentGroup",props:["title","canCollapse","slug"],components:{ChevronUpIcon:a("CjXH").i,TextLabel:r},data:function(){return{isVisible:!0,canCollapseWrapper:!1}},methods:{hideGroup:function(){this.canCollapseWrapper&&(this.isVisible=!this.isVisible,localStorage.setItem("panel-group-"+this.slug,this.isVisible))}},created:function(){if(this.canCollapse){var t=localStorage.getItem("panel-group-"+this.slug);this.isVisible=!t||!!JSON.parse(String(t).toLowerCase()),this.canCollapseWrapper=!0}}},s=(a("SqfT"),Object(n.a)(o,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"content-group",class:{"is-collapsed":!t.isVisible,collapsable:t.canCollapse}},[a("div",{staticClass:"group-title",on:{click:t.hideGroup}},[a("TextLabel",{staticClass:"title"},[t._v(t._s(t.title))]),t._v(" "),t.canCollapseWrapper?a("chevron-up-icon",{staticClass:"icon",attrs:{size:"12"}}):t._e()],1),t._v(" "),a("transition",{attrs:{name:"list"}},[a("div",{directives:[{name:"show",rawName:"v-show",value:t.isVisible,expression:"isVisible"}],staticClass:"wrapper"},[t._t("default")],2)])],1)}),[],!1,null,"a21919d8",null));e.a=s.exports},kPoH:function(t,e,a){"use strict";var i={name:"ColorLabel",props:["color"]},n=(a("GkQO"),a("KHd+")),r=Object(n.a)(i,(function(){var t=this.$createElement;return(this._self._c||t)("b",{staticClass:"color-label",class:this.color},[this._t("default")],2)}),[],!1,null,"ffcb2882",null);e.a=r.exports},lsZg:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".content-group[data-v-a21919d8]{margin-bottom:30px;transition:all .3s}.content-group .group-title[data-v-a21919d8]{display:flex;justify-content:space-between;align-items:center;margin-bottom:5px}.content-group .group-title .title[data-v-a21919d8]{margin-bottom:0}.content-group .group-title .icon[data-v-a21919d8]{margin-right:19px;opacity:.25;transition:all .3s ease}.content-group.collapsable .group-title[data-v-a21919d8]{cursor:pointer}.content-group.is-collapsed[data-v-a21919d8]{margin-bottom:15px}.content-group.is-collapsed .icon[data-v-a21919d8]{transform:rotate(180deg)}.list-enter[data-v-a21919d8],.list-leave-to[data-v-a21919d8]{visibility:hidden;height:0;margin:0;padding:0;opacity:0}.list-enter-active[data-v-a21919d8],.list-leave-active[data-v-a21919d8]{transition:all .3s}",""])},nSuU:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".content-sidebar[data-v-5101d4ac]{background:rgba(244,245,246,.6);-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;padding-top:25px;overflow-y:auto;flex:0 0 225px}@media only screen and (max-width:1024px){.content-sidebar[data-v-5101d4ac]{flex:0 0 205px}}@media only screen and (max-width:690px){.content-sidebar[data-v-5101d4ac]{display:none}}@media (prefers-color-scheme:dark){.content-sidebar[data-v-5101d4ac]{background:rgba(30,32,36,.2)}}",""])},"psU+":function(t,e,a){var i=a("JHkA");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},qf9i:function(t,e,a){"use strict";var i=a("KPNK");a.n(i).a},u5fQ:function(t,e,a){"use strict";var i=a("JoIu");a.n(i).a},"wu+2":function(t,e,a){"use strict";var i=a("Q8fg");a.n(i).a},ydEr:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".info-box[data-v-bf43be5e]{padding:20px;border-radius:8px;margin-bottom:32px;background:#f4f5f6;text-align:left}.info-box.error[data-v-bf43be5e]{background:rgba(253,57,122,.1)}.info-box.error a[data-v-bf43be5e],.info-box.error p[data-v-bf43be5e]{color:#fd397a}.info-box.error a[data-v-bf43be5e]{text-decoration:underline}.info-box p[data-v-bf43be5e]{font-size:.9375em;line-height:1.6;word-break:break-word;font-weight:600}.info-box p[data-v-bf43be5e] a{color:#00bc7e}.info-box a[data-v-bf43be5e],.info-box b[data-v-bf43be5e]{font-weight:700;color:#00bc7e}.info-box a[data-v-bf43be5e]{font-size:.9375em;line-height:1.6}.info-box ul[data-v-bf43be5e]{margin-top:15px}.info-box ul[data-v-bf43be5e],.info-box ul li[data-v-bf43be5e],.info-box ul li a[data-v-bf43be5e]{display:block}@media only screen and (max-width:690px){.info-box[data-v-bf43be5e]{padding:15px}}@media (prefers-color-scheme:dark){.info-box[data-v-bf43be5e]{background:#1e2024}.info-box p[data-v-bf43be5e],.info-box ul li[data-v-bf43be5e]{color:#bec6cf}}",""])},z2eR:function(t,e,a){var i=a("QNGj");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)}}]);