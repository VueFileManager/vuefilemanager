(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{"35KB":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".text-label[data-v-4c36e0a9]{padding-left:25px;font-size:.75em;color:#afafaf;font-weight:700;display:block;margin-bottom:5px}@media only screen and (max-width:1024px){.text-label[data-v-4c36e0a9]{padding-left:20px}}@media (prefers-color-scheme:dark){.text-label[data-v-4c36e0a9]{color:#00bc7e}}",""])},BNKf:function(t,e,a){"use strict";a.r(e);var s=a("CjXH"),i=a("LtV2"),n=a("hXay"),o=a("L2JU");function r(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,s)}return a}function l(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var c={name:"Settings",computed:function(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?r(Object(a),!0).forEach((function(e){l(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):r(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}({},Object(o.b)(["config"])),components:{MonitorIcon:s.G,BoxIcon:s.a,DatabaseIcon:s.l,CreditCardIcon:s.k,FileTextIcon:s.s,SettingsIcon:s.O,ContentSidebar:i.a,ContentGroup:n.a,UsersIcon:s.X}},u=(a("fTs+"),a("KHd+")),d=Object(u.a)(c,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("section",{attrs:{id:"viewport"}},[a("ContentSidebar",[a("ContentGroup",{staticClass:"navigator",attrs:{title:t.$t("global.admin")}},[a("div",{staticClass:"menu-list-wrapper vertical"},[a("router-link",{staticClass:"menu-list-item link",attrs:{to:{name:"Dashboard"}}},[a("div",{staticClass:"icon"},[a("box-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("admin_menu.dashboard"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{to:{name:"Users"}}},[a("div",{staticClass:"icon"},[a("users-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("admin_menu.users"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{to:{name:"AppOthers"}}},[a("div",{staticClass:"icon"},[a("settings-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("admin_menu.settings"))+"\n                    ")])])],1)]),t._v(" "),t.config.isSaaS?a("ContentGroup",{staticClass:"navigator",attrs:{title:"Subscription"}},[a("div",{staticClass:"menu-list-wrapper vertical"},[a("router-link",{staticClass:"menu-list-item link",attrs:{to:{name:"Plans"}}},[a("div",{staticClass:"icon"},[a("database-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("admin_menu.plans"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{to:{name:"Invoices"}}},[a("div",{staticClass:"icon"},[a("file-text-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("admin_menu.invoices"))+"\n                    ")])]),t._v(" "),a("router-link",{staticClass:"menu-list-item link",attrs:{to:{name:"Pages"}}},[a("div",{staticClass:"icon"},[a("monitor-icon",{attrs:{size:"17"}})],1),t._v(" "),a("div",{staticClass:"label"},[t._v("\n                        "+t._s(t.$t("admin_menu.pages"))+"\n                    ")])])],1)]):t._e()],1),t._v(" "),a("router-view")],1)}),[],!1,null,"f66ada38",null);e.default=d.exports},JoIu:function(t,e,a){var s=a("nSuU");"string"==typeof s&&(s=[[t.i,s,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,i);s.locals&&(t.exports=s.locals)},LtV2:function(t,e,a){"use strict";var s={name:"ContentSidebar"},i=(a("u5fQ"),a("KHd+")),n=Object(i.a)(s,(function(){var t=this.$createElement;return(this._self._c||t)("section",{staticClass:"content-sidebar"},[this._t("default")],2)}),[],!1,null,"5101d4ac",null);e.a=n.exports},Q8fg:function(t,e,a){var s=a("35KB");"string"==typeof s&&(s=[[t.i,s,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,i);s.locals&&(t.exports=s.locals)},SqfT:function(t,e,a){"use strict";var s=a("cf9B");a.n(s).a},cf9B:function(t,e,a){var s=a("lsZg");"string"==typeof s&&(s=[[t.i,s,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,i);s.locals&&(t.exports=s.locals)},"fTs+":function(t,e,a){"use strict";var s=a("hmRb");a.n(s).a},hXay:function(t,e,a){"use strict";var s={name:"TextLabel"},i=(a("wu+2"),a("KHd+")),n=Object(i.a)(s,(function(){var t=this.$createElement;return(this._self._c||t)("b",{staticClass:"text-label"},[this._t("default")],2)}),[],!1,null,"4c36e0a9",null).exports,o={name:"ContentGroup",props:["title","canCollapse","slug"],components:{ChevronUpIcon:a("CjXH").g,TextLabel:n},data:function(){return{isVisible:!0,canCollapseWrapper:!1}},methods:{hideGroup:function(){this.canCollapseWrapper&&(this.isVisible=!this.isVisible,localStorage.setItem("panel-group-"+this.slug,this.isVisible))}},created:function(){if(this.canCollapse){var t=localStorage.getItem("panel-group-"+this.slug);this.isVisible=!t||!!JSON.parse(String(t).toLowerCase()),this.canCollapseWrapper=!0}}},r=(a("SqfT"),Object(i.a)(o,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"content-group",class:{"is-collapsed":!t.isVisible,collapsable:t.canCollapse}},[a("div",{staticClass:"group-title",on:{click:t.hideGroup}},[a("TextLabel",{staticClass:"title"},[t._v(t._s(t.title))]),t._v(" "),t.canCollapseWrapper?a("chevron-up-icon",{staticClass:"icon",attrs:{size:"12"}}):t._e()],1),t._v(" "),a("transition",{attrs:{name:"list"}},[a("div",{directives:[{name:"show",rawName:"v-show",value:t.isVisible,expression:"isVisible"}],staticClass:"wrapper"},[t._t("default")],2)])],1)}),[],!1,null,"a21919d8",null));e.a=r.exports},hmRb:function(t,e,a){var s=a("urUc");"string"==typeof s&&(s=[[t.i,s,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,i);s.locals&&(t.exports=s.locals)},lsZg:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".content-group[data-v-a21919d8]{margin-bottom:30px;transition:all .3s}.content-group .group-title[data-v-a21919d8]{display:flex;justify-content:space-between;align-items:center;margin-bottom:5px}.content-group .group-title .title[data-v-a21919d8]{margin-bottom:0}.content-group .group-title .icon[data-v-a21919d8]{margin-right:19px;opacity:.25;transition:all .3s ease}.content-group.collapsable .group-title[data-v-a21919d8]{cursor:pointer}.content-group.is-collapsed[data-v-a21919d8]{margin-bottom:15px}.content-group.is-collapsed .icon[data-v-a21919d8]{transform:rotate(180deg)}.list-enter[data-v-a21919d8],.list-leave-to[data-v-a21919d8]{visibility:hidden;height:0;margin:0;padding:0;opacity:0}.list-enter-active[data-v-a21919d8],.list-leave-active[data-v-a21919d8]{transition:all .3s}",""])},nSuU:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".content-sidebar[data-v-5101d4ac]{background:rgba(244,245,246,.6);-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;padding-top:25px;overflow-y:auto;flex:0 0 225px}@media only screen and (max-width:1024px){.content-sidebar[data-v-5101d4ac]{flex:0 0 205px}}@media only screen and (max-width:690px){.content-sidebar[data-v-5101d4ac]{display:none}}@media (prefers-color-scheme:dark){.content-sidebar[data-v-5101d4ac]{background:rgba(30,32,36,.2)}}",""])},u5fQ:function(t,e,a){"use strict";var s=a("JoIu");a.n(s).a},urUc:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".user-headline[data-v-f66ada38]{margin-bottom:38px}",""])},"wu+2":function(t,e,a){"use strict";var s=a("Q8fg");a.n(s).a}}]);