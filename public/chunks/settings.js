(window.webpackJsonp=window.webpackJsonp||[]).push([[45],{"0ppm":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".content-sidebar[data-v-7f84dd9c]{background:rgba(244,245,246,.6);-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;padding-top:25px;overflow-y:auto;flex:0 0 225px}@media only screen and (max-width:1024px){.content-sidebar[data-v-7f84dd9c]{flex:0 0 205px}}@media only screen and (max-width:690px){.content-sidebar[data-v-7f84dd9c]{display:none}}@media (prefers-color-scheme:dark){.content-sidebar[data-v-7f84dd9c]{background:rgba(30,32,36,.2)}}",""])},"0rhn":function(t,e,a){var i=a("MWZw");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},"2Sb1":function(t,e,a){"use strict";var i={name:"PageHeader",props:["title","canBack"],components:{ChevronLeftIcon:a("CjXH").h}},n=(a("JOXf"),a("KHd+")),o=Object(n.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"page-header"},[t.canBack?a("div",{staticClass:"go-back",on:{click:function(e){return t.$router.back()}}},[a("chevron-left-icon",{attrs:{size:"17"}})],1):t._e(),t._v(" "),a("div",{staticClass:"content"},[a("h1",{staticClass:"title"},[t._v(t._s(t.title))])])])}),[],!1,null,"9fd0a424",null);e.a=o.exports},"3YzG":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".dropzone[data-v-1b35e671]{position:relative;line-height:0}.dropzone input[type=file][data-v-1b35e671]{opacity:0;position:absolute;top:0;left:0;right:0;bottom:0;z-index:2;width:100%;cursor:pointer}.dropzone .image-preview[data-v-1b35e671]{width:62px;height:62px;-o-object-fit:cover;object-fit:cover;border-radius:8px;z-index:1;position:relative}.dropzone .blurred[data-v-1b35e671]{position:absolute;left:0;top:2px;z-index:0;filter:blur(8px);opacity:.5}",""])},"3eeM":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".page-header[data-v-9fd0a424]{display:flex;align-items:center;background:#fff;z-index:9;width:100%;position:sticky;top:0;padding-top:20px;padding-bottom:20px}.page-header .title[data-v-9fd0a424]{font-size:1.125em;font-weight:700;color:#1b2539}.page-header .go-back[data-v-9fd0a424]{margin-right:10px;cursor:pointer}.page-header .go-back svg[data-v-9fd0a424]{vertical-align:middle;margin-top:-4px}@media only screen and (max-width:960px){.page-header .title[data-v-9fd0a424]{font-size:1.125em}}@media only screen and (max-width:690px){.page-header[data-v-9fd0a424]{display:none}}@media (prefers-color-scheme:dark){.page-header[data-v-9fd0a424]{background:#131414}.page-header .title[data-v-9fd0a424]{color:#bec6cf}.page-header .icon path[data-v-9fd0a424]{fill:#00bc7e}}",""])},"9JAZ":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".text-label[data-v-943e24b4]{padding-left:25px;font-size:.75em;color:#afafaf;font-weight:700;display:block;margin-bottom:5px}@media only screen and (max-width:1024px){.text-label[data-v-943e24b4]{padding-left:20px}}@media (prefers-color-scheme:dark){.text-label[data-v-943e24b4]{opacity:.35}}",""])},D62o:function(t,e,a){"use strict";var i=a("xCqy"),n=a("CjXH"),o={name:"MobileHeader",props:["title"],components:{ChevronLeftIcon:n.h,MenuIcon:n.M},methods:{showMobileNavigation:function(){i.a.$emit("mobile-menu:show","user-navigation")},goBack:function(){this.$router.back()}}},r=(a("R6Y3"),a("KHd+")),s=Object(r.a)(o,(function(){var t=this.$createElement,e=this._self._c||t;return e("header",{staticClass:"mobile-header"},[e("div",{staticClass:"go-back",on:{click:this.goBack}},[e("chevron-left-icon",{staticClass:"icon",attrs:{size:"17"}})],1),this._v(" "),e("div",{staticClass:"location-name"},[this._v(this._s(this.title))]),this._v(" "),e("div",{staticClass:"mobile-menu",on:{click:this.showMobileNavigation}},[e("menu-icon",{staticClass:"icon",attrs:{size:"17"}})],1)])}),[],!1,null,"699fe34a",null);e.a=s.exports},GgfX:function(t,e,a){"use strict";a.r(e);var i=a("LtV2"),n=a("hXay"),o=a("Rbea"),r=a("D62o"),s=a("Nv84"),l=a("KnjL"),c=a("2Sb1"),d=a("kPoH"),p=a("zTYo"),u=a("L2JU"),f=a("CjXH");function v(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(t);e&&(i=i.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,i)}return a}function m(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?v(Object(a),!0).forEach((function(e){b(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):v(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}function b(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var g={name:"Settings",components:{ContentSidebar:i.a,CreditCardIcon:f.o,UserImageInput:o.a,HardDriveIcon:f.D,FileTextIcon:f.x,MobileHeader:r.a,ContentGroup:n.a,ButtonBase:s.a,ColorLabel:d.a,PageHeader:c.a,CloudIcon:f.k,UserIcon:f.gb,LockIcon:f.K,Spinner:p.a,InfoBox:l.a},computed:m(m({},Object(u.b)(["user","config"])),{},{subscriptionStatus:function(){return this.user.data.attributes.subscription?this.$t("global.premium"):this.$t("global.free")},subscriptionColor:function(){return this.user.data.attributes.subscription?"green":"purple"},canShowSubscriptionSettings:function(){return this.config.isSaaS&&this.config.app_payments_active},canShowUpgradeWarning:function(){return this.config.storageLimit&&this.user.data.attributes.storage.used>95},canShowIncompletePayment:function(){return this.user.data.attributes.incomplete_payment}}),data:function(){return{avatar:void 0,isLoading:!1}}},h=(a("my7I"),a("KHd+")),x=Object(h.a)(g,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("section",{attrs:{id:"viewport"}},[a("ContentSidebar",[a("ContentGroup",{staticClass:"navigator",attrs:{title:"Menu"}},[a("div",{staticClass:"menu-list-wrapper vertical"},[a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"Profile"}}},[a("div",{staticClass:"icon text-theme"},[a("user-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("menu.profile"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"Storage"}}},[a("div",{staticClass:"icon text-theme"},[a("hard-drive-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("menu.storage"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"Password"}}},[a("div",{staticClass:"icon text-theme"},[a("lock-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("menu.password"))+"\n                    ")])])],1)]),t._v(" "),t.canShowSubscriptionSettings?a("ContentGroup",{staticClass:"navigator",attrs:{title:"Subscription"}},[a("div",{staticClass:"menu-list-wrapper vertical"},[a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"Subscription"}}},[a("div",{staticClass:"icon text-theme"},[a("cloud-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("menu.subscription"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"PaymentMethods"}}},[a("div",{staticClass:"icon text-theme"},[a("credit-card-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("menu.payment_cards"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{replace:"",to:{name:"Invoice"}}},[a("div",{staticClass:"icon text-theme"},[a("file-text-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("menu.invoices"))+"\n                    ")])])],1)]):t._e()],1),t._v(" "),t.user?a("div",{attrs:{id:"single-page"}},[t.isLoading?t._e():a("div",{staticClass:"medium-width",attrs:{id:"page-content"}},[a("MobileHeader",{attrs:{title:t.$t(t.$router.currentRoute.meta.title)}}),t._v(" "),a("div",{staticClass:"content-page"},[a("div",{staticClass:"page-detail-headline"},[a("div",{staticClass:"user-thumbnail"},[a("div",{staticClass:"avatar"},[a("UserImageInput",{attrs:{avatar:t.user.data.relationships.settings.data.attributes.avatar},model:{value:t.avatar,callback:function(e){t.avatar=e},expression:"avatar"}})],1),t._v(" "),a("div",{staticClass:"info"},[a("b",{staticClass:"name"},[t._v("\n                                "+t._s(t.user.data.relationships.settings.data.attributes.name)+"\n                                "),t.config.isSaaS?a("ColorLabel",{attrs:{color:t.subscriptionColor}},[t._v("\n                                    "+t._s(t.subscriptionStatus)+"\n                                ")]):t._e()],1),t._v(" "),a("span",{staticClass:"email"},[t._v(t._s(t.user.data.attributes.email))])])]),t._v(" "),t.config.storageLimit&&t.config.isSaaS&&t.config.app_payments_active&&!t.canShowIncompletePayment?a("div",{staticClass:"headline-actions"},[a("router-link",{attrs:{to:{name:"UpgradePlan"}}},[a("ButtonBase",{staticClass:"upgrade-button",attrs:{"button-style":"secondary",type:"button"}},[t._v("\n                                "+t._s(t.$t("global.upgrade_plan"))+"\n                            ")])],1)],1):t._e()]),t._v(" "),t.canShowIncompletePayment?a("InfoBox",{staticClass:"message-box",attrs:{type:"error"}},[a("i18n",{attrs:{path:"incomplete_payment.description",tag:"p"}},[a("a",{attrs:{href:t.user.data.attributes.incomplete_payment}},[t._v(t._s(t.$t("incomplete_payment.href")))])])],1):t._e(),t._v(" "),t.canShowUpgradeWarning&&!t.canShowIncompletePayment?a("InfoBox",{staticClass:"message-box",attrs:{type:"error"}},[a("p",[t._v(t._s(t.$t("upgrade_banner.title")))])]):t._e(),t._v(" "),a("router-view",{attrs:{user:t.user}})],1)],1),t._v(" "),t.isLoading?a("div",{attrs:{id:"loader"}},[a("Spinner")],1):t._e()]):t._e()],1)}),[],!1,null,"40837ba3",null);e.default=x.exports},HD0u:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".content-group[data-v-5efd3df6]{margin-bottom:15px;transition:all .3s}.content-group .group-title[data-v-5efd3df6]{display:flex;justify-content:space-between;align-items:center;margin-bottom:5px}.content-group .group-title .title[data-v-5efd3df6]{margin-bottom:0}.content-group .group-title .icon[data-v-5efd3df6]{margin-right:19px;opacity:.25;transition:all .3s ease}.content-group.collapsable .group-title[data-v-5efd3df6]{cursor:pointer}.content-group.is-collapsed[data-v-5efd3df6]{margin-bottom:15px}.content-group.is-collapsed .icon[data-v-5efd3df6]{transform:rotate(180deg)}.list-enter[data-v-5efd3df6],.list-leave-to[data-v-5efd3df6]{visibility:hidden;height:0;margin:0;padding:0;opacity:0}.list-enter-active[data-v-5efd3df6],.list-leave-active[data-v-5efd3df6]{transition:all .3s}",""])},JOXf:function(t,e,a){"use strict";a("nr4+")},KnjL:function(t,e,a){"use strict";var i={name:"InfoBox",props:["type"]},n=(a("pFam"),a("KHd+")),o=Object(n.a)(i,(function(){var t=this.$createElement;return(this._self._c||t)("div",{staticClass:"info-box",class:this.type},[this._t("default")],2)}),[],!1,null,"8e7c42f6",null);e.a=o.exports},LtV2:function(t,e,a){"use strict";var i={name:"ContentSidebar"},n=(a("p4YY"),a("KHd+")),o=Object(n.a)(i,(function(){var t=this.$createElement;return(this._self._c||t)("section",{staticClass:"content-sidebar",attrs:{id:"content-sidebar"}},[this._t("default")],2)}),[],!1,null,"7f84dd9c",null);e.a=o.exports},MWZw:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".color-label[data-v-5c508dbf]{text-transform:capitalize;font-size:.75em;display:inline-block;border-radius:6px;font-weight:700;padding:4px 6px}.color-label.purple[data-v-5c508dbf]{color:#9d66fe;background:rgba(157,102,254,.1)}.color-label.yellow[data-v-5c508dbf]{color:#ffbd2d;background:rgba(255,189,45,.1)}.color-label.green[data-v-5c508dbf]{color:#00bc7e;background:rgba(0,188,126,.1)}.color-label.red[data-v-5c508dbf]{color:#fd397a;background:rgba(253,57,122,.1)}",""])},"Qqv+":function(t,e,a){var i=a("biqn");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},R6Y3:function(t,e,a){"use strict";a("Xm12")},Rbea:function(t,e,a){"use strict";var i={props:["label","name","avatar","info","error"],data:function(){return{imagePreview:void 0}},watch:{imagePreview:function(t){this.$store.commit("UPDATE_AVATAR",t)}},methods:{showImagePreview:function(t){var e=this,a=t.target.files[0].name,i=a.substring(a.lastIndexOf(".")+1).toLowerCase();if(["png","jpg","jpeg"].includes(i)){var n=t.target.files[0],o=new FileReader;o.onload=function(){return e.imagePreview=o.result},o.readAsDataURL(n),this.$updateImage("/user/relationships/settings","avatar",t.target.files[0])}else alert(this.$t("validation_errors.wrong_image"))}},created:function(){this.avatar&&(this.imagePreview=this.avatar)}},n=(a("da90"),a("KHd+")),o=Object(n.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"dropzone",class:{"is-error":t.error}},[a("input",{ref:"file",staticClass:"dummy",attrs:{type:"file",name:t.name},on:{change:function(e){return t.showImagePreview(e)}}}),t._v(" "),t.imagePreview?a("img",{ref:"image",staticClass:"image-preview",attrs:{src:t.imagePreview}}):t._e(),t._v(" "),t.imagePreview?a("img",{ref:"image",staticClass:"image-preview blurred",attrs:{src:t.imagePreview}}):t._e()])}),[],!1,null,"1b35e671",null);e.a=o.exports},Xm12:function(t,e,a){var i=a("q8nf");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},a97j:function(t,e,a){"use strict";a("r1Xz")},"b+Fu":function(t,e,a){var i=a("w5mO");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},biqn:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".info-box[data-v-8e7c42f6]{padding:20px;border-radius:8px;margin-bottom:32px;background:#f4f5f6;text-align:left}.info-box.error[data-v-8e7c42f6]{background:rgba(253,57,122,.1)}.info-box.error a[data-v-8e7c42f6],.info-box.error p[data-v-8e7c42f6]{color:#fd397a}.info-box.error a[data-v-8e7c42f6]{text-decoration:underline}.info-box p[data-v-8e7c42f6]{line-height:1.6;word-break:break-word;font-weight:600}.info-box p[data-v-8e7c42f6],.info-box p[data-v-8e7c42f6] a{font-size:15px}.info-box p[data-v-8e7c42f6] b{font-size:15px;font-weight:700}.info-box a[data-v-8e7c42f6],.info-box b[data-v-8e7c42f6]{font-weight:700}.info-box a[data-v-8e7c42f6]{font-size:.9375em;line-height:1.6}.info-box ul[data-v-8e7c42f6]{margin-top:15px}.info-box ul[data-v-8e7c42f6],.info-box ul li[data-v-8e7c42f6],.info-box ul li a[data-v-8e7c42f6]{display:block}@media only screen and (max-width:690px){.info-box[data-v-8e7c42f6]{padding:15px}}@media (prefers-color-scheme:dark){.info-box[data-v-8e7c42f6]{background:#1e2024}.info-box p[data-v-8e7c42f6],.info-box ul li[data-v-8e7c42f6]{color:#bec6cf}}",""])},dP6t:function(t,e,a){var i=a("0ppm");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},da90:function(t,e,a){"use strict";a("ffqf")},fC5u:function(t,e,a){var i=a("9JAZ");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},ffqf:function(t,e,a){var i=a("3YzG");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},hXay:function(t,e,a){"use strict";var i={name:"TextLabel"},n=(a("wD4H"),a("KHd+")),o=Object(n.a)(i,(function(){var t=this.$createElement;return(this._self._c||t)("b",{staticClass:"text-label"},[this._t("default")],2)}),[],!1,null,"943e24b4",null).exports,r={name:"ContentGroup",props:["title","canCollapse","slug"],components:{ChevronUpIcon:a("CjXH").j,TextLabel:o},data:function(){return{isVisible:!0,canCollapseWrapper:!1}},methods:{hideGroup:function(){this.canCollapseWrapper&&(this.isVisible=!this.isVisible,localStorage.setItem("panel-group-"+this.slug,this.isVisible))}},created:function(){if(this.canCollapse){var t=localStorage.getItem("panel-group-"+this.slug);this.isVisible=!t||!!JSON.parse(String(t).toLowerCase()),this.canCollapseWrapper=!0}}},s=(a("a97j"),Object(n.a)(r,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"content-group",class:{"is-collapsed":!t.isVisible,collapsable:t.canCollapse}},[a("div",{staticClass:"group-title",on:{click:t.hideGroup}},[a("TextLabel",{staticClass:"title"},[t._v(t._s(t.title))]),t._v(" "),t.canCollapseWrapper?a("chevron-up-icon",{staticClass:"icon",attrs:{size:"12"}}):t._e()],1),t._v(" "),a("transition",{attrs:{name:"list"}},[a("div",{directives:[{name:"show",rawName:"v-show",value:t.isVisible,expression:"isVisible"}],staticClass:"wrapper"},[t._t("default")],2)])],1)}),[],!1,null,"5efd3df6",null));e.a=s.exports},kPoH:function(t,e,a){"use strict";var i={name:"ColorLabel",props:["color"]},n=(a("m6y/"),a("KHd+")),o=Object(n.a)(i,(function(){var t=this.$createElement;return(this._self._c||t)("b",{staticClass:"color-label",class:this.color},[this._t("default")],2)}),[],!1,null,"5c508dbf",null);e.a=o.exports},"m6y/":function(t,e,a){"use strict";a("0rhn")},my7I:function(t,e,a){"use strict";a("b+Fu")},"nr4+":function(t,e,a){var i=a("3eeM");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},p4YY:function(t,e,a){"use strict";a("dP6t")},pFam:function(t,e,a){"use strict";a("Qqv+")},q8nf:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".mobile-header[data-v-699fe34a]{padding:10px 0;text-align:center;background:#fff;position:sticky;display:none;z-index:6;top:0}.mobile-header>div[data-v-699fe34a]{flex-grow:1;align-self:center;white-space:nowrap}.mobile-header .go-back[data-v-699fe34a]{text-align:left}.mobile-header .location-name[data-v-699fe34a]{line-height:1;text-align:center;width:100%;vertical-align:middle;font-size:.9375em;color:#1b2539;font-weight:700;max-width:220px;overflow:hidden;text-overflow:ellipsis;display:inline-block}.mobile-header .mobile-menu[data-v-699fe34a]{text-align:right}.mobile-header .icon[data-v-699fe34a]{vertical-align:middle;margin-top:-4px}@media only screen and (max-width:690px){.mobile-header[data-v-699fe34a]{display:flex;margin-bottom:15px}}@media (prefers-color-scheme:dark){.mobile-header[data-v-699fe34a]{background:#131414}.mobile-header .location-name[data-v-699fe34a]{color:#bec6cf}}",""])},r1Xz:function(t,e,a){var i=a("HD0u");"string"==typeof i&&(i=[[t.i,i,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,n);i.locals&&(t.exports=i.locals)},w5mO:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".page-detail-headline[data-v-40837ba3]{display:flex;justify-content:space-between;margin-bottom:50px;margin-top:30px}.user-thumbnail[data-v-40837ba3]{display:flex;align-items:center;cursor:pointer}.user-thumbnail .avatar[data-v-40837ba3]{margin-right:20px}.user-thumbnail .avatar img[data-v-40837ba3]{line-height:0;width:62px;height:62px;border-radius:12px;z-index:1;position:relative}.user-thumbnail .info .name[data-v-40837ba3]{display:block;font-size:1.0625em;line-height:1}.user-thumbnail .info .email[data-v-40837ba3]{color:rgba(27,37,57,.7);font-size:.875em}.message-box[data-v-40837ba3]{margin-top:-15px}@media (prefers-color-scheme:dark){.user-thumbnail .info .name[data-v-40837ba3]{color:#bec6cf}.user-thumbnail .info .email[data-v-40837ba3]{color:#7d858c}}@media only screen and (max-width:690px){.page-detail-headline[data-v-40837ba3]{display:block;margin-bottom:30px;margin-top:10px}.page-detail-headline .headline-actions[data-v-40837ba3]{margin-top:20px}.page-detail-headline .headline-actions .upgrade-button[data-v-40837ba3]{width:100%}}",""])},wD4H:function(t,e,a){"use strict";a("fC5u")}}]);