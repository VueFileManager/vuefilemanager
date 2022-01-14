(window.webpackJsonp=window.webpackJsonp||[]).push([[46],{"+K/L":function(t,e,a){var i=a("uWhG");"string"==typeof i&&(i=[[t.i,i,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,s);i.locals&&(t.exports=i.locals)},"0ezt":function(t,e,a){var i=a("x7z+");"string"==typeof i&&(i=[[t.i,i,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,s);i.locals&&(t.exports=i.locals)},"4TWA":function(t,e,a){"use strict";var i=a("CjXH"),s=a("LvDl"),n={name:"SelectInput",props:["placeholder","options","isError","default"],components:{Edit2Icon:i.y,UserIcon:i.wb,ChevronDownIcon:i.i},watch:{query:Object(s.debounce)((function(t){this.searchedResults=Object(s.omitBy)(this.options,(function(e){return!e.label.toLowerCase().includes(t.toLowerCase())}))}),200)},computed:{isSearching:function(){return this.searchedResults&&""!==this.query},optionList:function(){return this.isSearching?this.searchedResults:this.options}},data:function(){return{searchedResults:void 0,selected:void 0,isOpen:!1,query:""}},methods:{selectOption:function(t){this.$emit("input",t.value),this.$emit("change",t.value),this.selected=t,this.isOpen=!1},openMenu:function(){var t=this;this.isOpen=!this.isOpen,this.isOpen&&this.$nextTick((function(){return t.$refs.search.focus()}))}},created:function(){var t=this;this.default&&(this.selected=this.options.find((function(e){return e.value===t.default})))}},r=(a("VBJ4"),a("KHd+")),o=Object(r.a)(n,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"select"},[a("div",{staticClass:"input-area bg-light-background rounded-lg",class:{"is-active":t.isOpen,"is-error":t.isError},on:{click:t.openMenu}},[t.selected?a("div",{staticClass:"selected"},[t.selected.icon?a("div",{staticClass:"option-icon"},["user"===t.selected.icon?a("user-icon",{staticClass:"text-theme dark-text-theme",attrs:{size:"14"}}):t._e(),t._v(" "),"user-edit"===t.selected.icon?a("edit2-icon",{staticClass:"text-theme dark-text-theme",attrs:{size:"14"}}):t._e()],1):t._e(),t._v(" "),a("span",{staticClass:"option-value"},[t._v(t._s(t.selected.label))])]):t._e(),t._v(" "),t.selected?t._e():a("div",{staticClass:"not-selected"},[a("span",{staticClass:"option-value placehoder"},[t._v(t._s(t.placeholder))])]),t._v(" "),a("chevron-down-icon",{staticClass:"chevron",attrs:{size:"19"}})],1),t._v(" "),a("transition",{attrs:{name:"slide-in"}},[t.isOpen?a("div",{staticClass:"input-options rounded-lg"},[t.options.length>5?a("div",{staticClass:"select-search"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.query,expression:"query"}],ref:"search",staticClass:"search-input focus-border-theme rounded-lg",attrs:{type:"text",placeholder:t.$t("select_search_placeholder")},domProps:{value:t.query},on:{input:function(e){e.target.composing||(t.query=e.target.value)}}})]):t._e(),t._v(" "),a("ul",{staticClass:"option-list"},t._l(t.optionList,(function(e,i){return a("li",{key:i,staticClass:"option-item",on:{click:function(a){return t.selectOption(e)}}},[e.icon?a("div",{staticClass:"option-icon"},["user"===e.icon?a("user-icon",{attrs:{size:"14"}}):t._e(),t._v(" "),"user-edit"===e.icon?a("edit2-icon",{attrs:{size:"14"}}):t._e()],1):t._e(),t._v(" "),a("span",{staticClass:"option-value"},[t._v(t._s(e.label))])])})),0)]):t._e()])],1)}),[],!1,null,"779355e6",null);e.a=o.exports},Il6k:function(t,e,a){"use strict";a("+K/L")},KnjL:function(t,e,a){"use strict";var i={name:"InfoBox",props:["type"]},s=(a("LQoL"),a("KHd+")),n=Object(s.a)(i,(function(){var t=this.$createElement;return(this._self._c||t)("div",{staticClass:"info-box",class:this.type},[this._t("default")],2)}),[],!1,null,"27ea1904",null);e.a=n.exports},LQoL:function(t,e,a){"use strict";a("0ezt")},UD3w:function(t,e,a){"use strict";var i={name:"AppInputText",props:["description","isLast","title","error"]},s=a("KHd+"),n=Object(s.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{class:{"mb-7":!t.isLast}},[t.title?a("label",{staticClass:"text-sm font-bold dark:text-gray-200 text-gray-700 mb-1.5 block"},[t._v("\n\t\t"+t._s(t.title)+":\n\t")]):t._e(),t._v(" "),t._t("default"),t._v(" "),t.description?a("small",{staticClass:"text-xs text-gray-500 pt-2 leading-4 block",domProps:{innerHTML:t._s(t.description)}}):t._e(),t._v(" "),t.error?a("span",{staticClass:"text-red pt-2 text-xs"},[t._v("\n\t\t"+t._s(t.error)+"\n\t")]):t._e()],2)}),[],!1,null,null,null);e.a=n.exports},VBJ4:function(t,e,a){"use strict";a("xmEC")},eZ9V:function(t,e,a){"use strict";var i=a("CjXH"),s={name:"FormLabel",props:["icon"],components:{UsersIcon:i.zb,CreditCardIcon:i.t,DollarSignIcon:i.v,SmartphoneIcon:i.mb,HardDriveIcon:i.L,BarChartIcon:i.c,SettingsIcon:i.jb,FileTextIcon:i.D,ShieldIcon:i.lb,FrownIcon:i.H,Edit2Icon:i.y,BellIcon:i.d,KeyIcon:i.Q}},n=a("KHd+"),r=Object(n.a)(s,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"flex items-center mb-8"},[t.icon?t._e():a("edit-2-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}),t._v(" "),"frown"===t.icon?a("frown-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),"file-text"===t.icon?a("file-text-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),"dollar"===t.icon?a("dollar-sign-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),"credit-card"===t.icon?a("credit-card-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),"bar-chart"===t.icon?a("bar-chart-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),"settings"===t.icon?a("settings-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),"hard-drive"===t.icon?a("hard-drive-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),"smartphone"===t.icon?a("smartphone-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),"shield"===t.icon?a("shield-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),"bell"===t.icon?a("bell-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),"key"===t.icon?a("key-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),"users"===t.icon?a("users-icon",{staticClass:"mr-3 vue-feather text-theme dark-text-theme",attrs:{size:"22"}}):t._e(),t._v(" "),a("b",{staticClass:"font-bold dark:text-gray-200 text-lg"},[t._t("default")],2)],1)}),[],!1,null,null,null);e.a=r.exports},fqo0:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".select[data-v-779355e6]{position:relative;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;width:100%}.select-search[data-v-779355e6]{background:#fff;position:sticky;top:0;padding:13px}.select-search .search-input[data-v-779355e6]{border:1px solid transparent;background:#f4f5f6;transition:all .15s ease;font-size:.875em;padding:13px 20px;-webkit-appearance:none;-moz-appearance:none;appearance:none;font-weight:700;outline:0;width:100%}.input-options[data-v-779355e6]{box-shadow:0 5px 15px rgba(0,0,0,.12);background:#fff;position:absolute;overflow:hidden;top:65px;left:0;right:0;z-index:9;max-height:295px;overflow-y:auto}.input-options .option-item[data-v-779355e6]{padding:13px 20px;display:block;cursor:pointer}.input-options .option-item[data-v-779355e6]:hover{color:#00bc7e;background:#f4f5f6}.input-options .option-item[data-v-779355e6]:last-child{border-bottom:none}.input-area[data-v-779355e6]{border:1px solid transparent;justify-content:space-between;align-items:center;padding:13px 20px;display:flex;outline:0;width:100%;cursor:pointer}.input-area[data-v-779355e6],.input-area .chevron[data-v-779355e6]{transition:all .15s ease}.input-area.is-active .chevron[data-v-779355e6]{transform:rotate(180deg)}.input-area.is-error[data-v-779355e6]{border-color:#fd397a;box-shadow:0 0 7px rgba(253,57,122,.3)}.option-icon[data-v-779355e6]{width:20px;display:inline-block;font-size:.625em}.option-icon svg[data-v-779355e6]{margin-top:-4px;vertical-align:middle}.option-icon svg circle[data-v-779355e6],.option-icon svg line[data-v-779355e6],.option-icon svg path[data-v-779355e6]{color:inherit}.option-value[data-v-779355e6]{font-size:.875em;font-weight:700;width:100%;vertical-align:middle}.option-value.placehoder[data-v-779355e6]{color:rgba(27,37,57,.5)}.slide-in-enter-active[data-v-779355e6]{transition:all .15s ease}.slide-in-enter[data-v-779355e6]{opacity:0;transform:translateY(-50px)}.dark .select-search[data-v-779355e6]{background:#1e2024}.dark .select-search .search-input[data-v-779355e6]{background:#151515}.dark .input-area[data-v-779355e6]{background:#1e2024;border-color:#1e2024}.dark .popup-wrapper .input-area[data-v-779355e6]{background:#25272c}.dark .input-options[data-v-779355e6]{background:#1e2024}.dark .input-options .option-item[data-v-779355e6]{border-bottom:none}.dark .input-options .option-item[data-v-779355e6]:hover{background:#2a2c32}.dark .input-options .option-item:hover .option-icon circle[data-v-779355e6],.dark .input-options .option-item:hover .option-icon path[data-v-779355e6]{color:inherit}.dark .input-options .option-item[data-v-779355e6]:last-child{border-bottom:none}.dark .option-value.placehoder[data-v-779355e6]{color:#7d858c}",""])},jH4x:function(t,e,a){"use strict";var i={name:"AppInputSwitch",props:["description","isLast","title","error"]},s=a("KHd+"),n=Object(s.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"flex items-center justify-between sm:space-x-8 space-x-2 w-full",class:{"mb-7":!t.isLast}},[a("div",{staticClass:"leading-5"},[a("label",{staticClass:"text-sm font-bold dark:text-gray-200 text-gray-700 mb-1.5 block"},[t._v("\n\t\t\t"+t._s(t.title)+":\n\t\t")]),t._v(" "),t.description?a("span",{staticClass:"text-xs text-gray-500 leading-4 block",domProps:{innerHTML:t._s(t.description)}}):t._e(),t._v(" "),t.error?a("span",{staticClass:"error-message"},[t._v("\n\t\t\t"+t._s(t.error)+"\n\t\t")]):t._e()]),t._v(" "),a("div",[t._t("default")],2)])}),[],!1,null,null,null);e.a=n.exports},qZtN:function(t,e,a){"use strict";a.r(e);var i=a("xxrA"),s=a("4TWA"),n=a("jH4x"),r=a("eZ9V"),o=a("UD3w"),l=a("KnjL"),c={name:"PlanFixedSettings",props:["plan"],components:{AppInputSwitch:n.a,AppInputText:o.a,SwitchInput:i.a,SelectInput:s.a,FormLabel:r.a,InfoBox:l.a},data:function(){return{visible:void 0}},created:function(){this.visible=this.plan.attributes.visible}},p=a("KHd+"),u=Object(p.a)(c,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"card shadow-card"},[a("FormLabel",[t._v("\n\t\t\t"+t._s(t.$t("Details"))+"\n\t\t")]),t._v(" "),a("AppInputSwitch",{attrs:{title:t.$t("admin_page_plans.form.status"),description:t.$t("admin_page_plans.form.status_help")}},[a("SwitchInput",{staticClass:"switch",attrs:{state:t.plan.attributes.visible},on:{input:function(e){return t.$updateInput("/subscriptions/admin/plans/"+t.$route.params.id,"visible",t.plan.attributes.visible)}},model:{value:t.plan.attributes.visible,callback:function(e){t.$set(t.plan.attributes,"visible",e)},expression:"plan.attributes.visible"}})],1),t._v(" "),a("AppInputText",{attrs:{title:t.$t("admin_page_plans.form.name")}},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.plan.attributes.name,expression:"plan.attributes.name"}],staticClass:"focus-border-theme input-dark",attrs:{placeholder:t.$t("admin_page_plans.form.name_plac"),type:"text"},domProps:{value:t.plan.attributes.name},on:{input:[function(e){e.target.composing||t.$set(t.plan.attributes,"name",e.target.value)},function(e){return t.$updateInput("/subscriptions/admin/plans/"+t.$route.params.id,"name",t.plan.attributes.name)}]}})]),t._v(" "),a("AppInputText",{attrs:{title:t.$t("admin_page_plans.form.description")}},[a("textarea",{directives:[{name:"model",rawName:"v-model",value:t.plan.attributes.description,expression:"plan.attributes.description"}],staticClass:"focus-border-theme input-dark",attrs:{placeholder:t.$t("admin_page_plans.form.description_plac")},domProps:{value:t.plan.attributes.description},on:{input:[function(e){e.target.composing||t.$set(t.plan.attributes,"description",e.target.value)},function(e){return t.$updateInput("/subscriptions/admin/plans/"+t.$route.params.id,"description",t.plan.attributes.description)}]}})]),t._v(" "),a("InfoBox",{staticStyle:{"margin-bottom":"0"}},[a("p",[t._v(t._s(t.$t("Price change is not possible. If you would like to change your price or currency, please feel free to create a new plan.")))])])],1),t._v(" "),a("div",{staticClass:"card shadow-card"},[a("FormLabel",[t._v("\n\t\t\t"+t._s(t.$t("Features"))+"\n\t\t")]),t._v(" "),a("AppInputText",{attrs:{title:t.$t("admin_page_plans.form.storage"),description:t.$t("admin_page_plans.form.storage_helper")}},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.plan.attributes.features.max_storage_amount,expression:"plan.attributes.features.max_storage_amount"}],staticClass:"focus-border-theme input-dark",attrs:{placeholder:t.$t("admin_page_plans.form.storage_plac"),type:"number",min:"1",max:"999999999"},domProps:{value:t.plan.attributes.features.max_storage_amount},on:{input:[function(e){e.target.composing||t.$set(t.plan.attributes.features,"max_storage_amount",e.target.value)},function(e){return t.$updateInput("/subscriptions/admin/plans/"+t.$route.params.id+"/features","max_storage_amount",t.plan.attributes.features.max_storage_amount)}]}})]),t._v(" "),a("AppInputText",{attrs:{title:t.$t("Max Team Members"),"is-last":"true"}},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.plan.attributes.features.max_team_members,expression:"plan.attributes.features.max_team_members"}],staticClass:"focus-border-theme input-dark",attrs:{placeholder:t.$t("Add max team members in number"),type:"number",min:"1",max:"999999999"},domProps:{value:t.plan.attributes.features.max_team_members},on:{input:[function(e){e.target.composing||t.$set(t.plan.attributes.features,"max_team_members",e.target.value)},function(e){return t.$updateInput("/subscriptions/admin/plans/"+t.$route.params.id+"/features","max_team_members",t.plan.attributes.features.max_team_members)}]}})])],1)])}),[],!1,null,null,null);e.default=u.exports},qoac:function(t,e,a){"use strict";a.r(e);var i=a("xxrA"),s=a("4TWA"),n=a("jH4x"),r=a("eZ9V"),o=a("UD3w"),l=a("KnjL"),c={name:"PlanMeteredSettings",props:["plan"],components:{AppInputSwitch:n.a,AppInputText:o.a,SwitchInput:i.a,SelectInput:s.a,FormLabel:r.a,InfoBox:l.a},data:function(){return{visible:void 0}},methods:{formatCurrency:function(t,e){return new Intl.NumberFormat("en-US",{style:"currency",currency:t}).format(e)}},created:function(){this.visible=this.plan.attributes.visible}},p=a("KHd+"),u=Object(p.a)(c,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"card shadow-card"},[a("FormLabel",[t._v("\n\t\t\t"+t._s(t.$t("Details"))+"\n\t\t")]),t._v(" "),a("AppInputText",{attrs:{title:t.$t("admin_page_plans.form.name")}},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.plan.attributes.name,expression:"plan.attributes.name"}],staticClass:"focus-border-theme input-dark",attrs:{placeholder:t.$t("admin_page_plans.form.name_plac"),type:"text"},domProps:{value:t.plan.attributes.name},on:{input:[function(e){e.target.composing||t.$set(t.plan.attributes,"name",e.target.value)},function(e){return t.$updateInput("/subscriptions/admin/plans/"+t.$route.params.id,"name",t.plan.attributes.name)}]}})]),t._v(" "),a("AppInputText",{attrs:{title:t.$t("admin_page_plans.form.description"),"is-last":!0}},[a("textarea",{directives:[{name:"model",rawName:"v-model",value:t.plan.attributes.description,expression:"plan.attributes.description"}],staticClass:"focus-border-theme input-dark",attrs:{placeholder:t.$t("admin_page_plans.form.description_plac")},domProps:{value:t.plan.attributes.description},on:{input:[function(e){e.target.composing||t.$set(t.plan.attributes,"description",e.target.value)},function(e){return t.$updateInput("/subscriptions/admin/plans/"+t.$route.params.id,"description",t.plan.attributes.description)}]}})])],1),t._v(" "),a("div",{staticClass:"card shadow-card"},[a("FormLabel",[t._v("\n\t\t\t"+t._s(t.$t("Charged Features"))+"\n\t\t")]),t._v(" "),t.plan.attributes.features.bandwidth?a("AppInputText",{staticClass:"w-full",attrs:{title:t.$t("Bandwidth Price per 1GB"),description:t.$t("Charge your user by the amount of data he upload or download.")}},[a("input",{staticClass:"focus-border-theme input-dark",attrs:{type:"text",disabled:""},domProps:{value:t.formatCurrency(t.plan.attributes.currency,t.plan.attributes.features.bandwidth.tiers[0].per_unit)}})]):t._e(),t._v(" "),t.plan.attributes.features.storage?a("AppInputText",{staticClass:"w-full",attrs:{title:t.$t("Storage Price per 1GB"),description:t.$t("Charge your user by the amount of data he has stored on the disk per 1GB.")}},[a("input",{staticClass:"focus-border-theme input-dark",attrs:{type:"text",disabled:""},domProps:{value:t.formatCurrency(t.plan.attributes.currency,t.plan.attributes.features.storage.tiers[0].per_unit)}})]):t._e(),t._v(" "),t.plan.attributes.features.member?a("AppInputText",{staticClass:"w-full",attrs:{title:t.$t("Price per 1 Member"),description:t.$t("Charge your user by the total members he use in his Team Folders.")}},[a("input",{staticClass:"focus-border-theme input-dark",attrs:{type:"text",disabled:""},domProps:{value:t.formatCurrency(t.plan.attributes.currency,t.plan.attributes.features.member.tiers[0].per_unit)}})]):t._e(),t._v(" "),t.plan.attributes.features.flatFee?a("AppInputText",{staticClass:"w-full",attrs:{title:t.$t("Flat Fee per Cycle"),description:t.$t("Charge monthly flat fee.")}},[a("input",{staticClass:"focus-border-theme input-dark",attrs:{type:"text",disabled:""},domProps:{value:t.formatCurrency(t.plan.attributes.currency,t.plan.attributes.features.flatFee.tiers[0].per_unit)}})]):t._e(),t._v(" "),a("InfoBox",{staticStyle:{"margin-bottom":"0"}},[a("p",[t._v(t._s(t.$t("Price change is not possible. If you would like to change your price or currency, please feel free to create a new plan.")))])])],1)])}),[],!1,null,null,null);e.default=u.exports},uWhG:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".input-wrapper[data-v-6ecf530e]{display:flex;width:100%}.input-wrapper .input-label[data-v-6ecf530e]{color:#1b2539}.input-wrapper .switch-content[data-v-6ecf530e]{width:100%}.input-wrapper .switch-content[data-v-6ecf530e]:last-child{width:80px}.switch[data-v-6ecf530e]{width:50px;height:28px;background:#f1f1f5;position:relative}.switch[data-v-6ecf530e],.switch .switch-button[data-v-6ecf530e]{border-radius:50px;display:block;transition:all .3s ease}.switch .switch-button[data-v-6ecf530e]{width:22px;height:22px;background:#fff;position:absolute;top:3px;left:3px;box-shadow:0 2px 4px rgba(37,38,94,.1);cursor:pointer}.switch.active .switch-button[data-v-6ecf530e]{left:25px}.dark .switch[data-v-6ecf530e]{background:#1e2024}.dark .popup-wrapper .switch[data-v-6ecf530e]{background:#25272c}",""])},"x7z+":function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".info-box[data-v-27ea1904]{padding:20px;border-radius:10px;margin-bottom:32px;background:#f4f5f6;text-align:left}.info-box.error[data-v-27ea1904]{background:rgba(253,57,122,.1)}.info-box.error a[data-v-27ea1904],.info-box.error p[data-v-27ea1904]{color:#fd397a}.info-box.error a[data-v-27ea1904]{text-decoration:underline}.info-box p[data-v-27ea1904]{line-height:1.6;word-break:break-word;font-weight:600}.info-box p[data-v-27ea1904],.info-box p[data-v-27ea1904] a{font-size:15px}.info-box p[data-v-27ea1904] b{font-size:15px;font-weight:700}.info-box a[data-v-27ea1904],.info-box b[data-v-27ea1904]{font-weight:700}.info-box a[data-v-27ea1904]{font-size:.9375em;line-height:1.6}.info-box ul[data-v-27ea1904]{margin-top:15px}.info-box ul[data-v-27ea1904],.info-box ul li[data-v-27ea1904],.info-box ul li a[data-v-27ea1904]{display:block}@media only screen and (max-width:690px){.info-box[data-v-27ea1904]{padding:15px}}.dark .info-box[data-v-27ea1904]{background:#1e2024}.dark .info-box.error[data-v-27ea1904]{background:rgba(253,57,122,.1)}.dark .info-box.error a[data-v-27ea1904],.dark .info-box.error p[data-v-27ea1904]{color:#fd397a}.dark .info-box.error a[data-v-27ea1904]{text-decoration:underline}.dark .info-box p[data-v-27ea1904],.dark .info-box ul li[data-v-27ea1904]{color:#bec6cf}",""])},xmEC:function(t,e,a){var i=a("fqo0");"string"==typeof i&&(i=[[t.i,i,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,s);i.locals&&(t.exports=i.locals)},xxrA:function(t,e,a){"use strict";var i={name:"SwitchInput",props:["label","name","state","info","input"],data:function(){return{isSwitched:void 0}},methods:{changeState:function(){this.isSwitched=!this.isSwitched,this.$emit("input",this.isSwitched)}},mounted:function(){this.isSwitched=this.state}},s=(a("Il6k"),a("KHd+")),n=Object(s.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"input-wrapper"},[a("div",{staticClass:"switch-content"},[t.label?a("label",{staticClass:"input-label"},[t._v("\n\t\t\t\t"+t._s(t.label)+":\n\t\t\t")]):t._e(),t._v(" "),t.info?a("small",{staticClass:"input-info"},[t._v("\n\t\t\t\t"+t._s(t.info)+"\n\t\t\t")]):t._e()]),t._v(" "),a("div",{staticClass:"switch-content text-right"},[a("div",{staticClass:"switch",class:{active:t.state},on:{click:t.changeState}},[a("div",{staticClass:"switch-button"})])])])}),[],!1,null,"6ecf530e",null);e.a=n.exports}}]);