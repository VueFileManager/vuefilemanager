(window.webpackJsonp=window.webpackJsonp||[]).push([[50],{"+UVI":function(t,a,n){var e=n("SEx2");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},"0rhn":function(t,a,n){var e=n("MWZw");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},"2Sb1":function(t,a,n){"use strict";var e={name:"PageHeader",props:["title","canBack"],components:{ChevronLeftIcon:n("CjXH").g}},i=(n("JOXf"),n("KHd+")),s=Object(i.a)(e,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("div",{staticClass:"page-header"},[t.canBack?n("div",{staticClass:"go-back",on:{click:function(a){return t.$router.back()}}},[n("chevron-left-icon",{attrs:{size:"17"}})],1):t._e(),t._v(" "),n("div",{staticClass:"content"},[n("h1",{staticClass:"title"},[t._v(t._s(t.title))])])])}),[],!1,null,"9fd0a424",null);a.a=s.exports},"3eeM":function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".page-header[data-v-9fd0a424] {\n  display: flex;\n  align-items: center;\n  background: white;\n  z-index: 9;\n  width: 100%;\n  position: -webkit-sticky;\n  position: sticky;\n  top: 0;\n  padding-top: 20px;\n  padding-bottom: 20px;\n}\n.page-header .title[data-v-9fd0a424] {\n  font-size: 1.125em;\n  font-weight: 700;\n  color: #1B2539;\n}\n.page-header .go-back[data-v-9fd0a424] {\n  margin-right: 10px;\n  cursor: pointer;\n}\n.page-header .go-back svg[data-v-9fd0a424] {\n  vertical-align: middle;\n  margin-top: -4px;\n}\n@media only screen and (max-width: 960px) {\n.page-header .title[data-v-9fd0a424] {\n    font-size: 1.125em;\n}\n}\n@media only screen and (max-width: 690px) {\n.page-header[data-v-9fd0a424] {\n    display: none;\n}\n}\n@media (prefers-color-scheme: dark) {\n.page-header[data-v-9fd0a424] {\n    background: #141414;\n}\n.page-header .title[data-v-9fd0a424] {\n    color: #bec6cf;\n}\n.page-header .icon path[data-v-9fd0a424] {\n    fill: #00BC7E;\n}\n}\n",""])},"5EXa":function(t,a,n){var e=n("EX7C");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},"6TPS":function(t,a,n){"use strict";var e=n("CjXH"),i={props:["data"],computed:{normalizedColumns:function(){return this.data.id&&delete this.data.id,Object.values(this.data)}}},s=(n("YLwN"),n("KHd+")),o=Object(s.a)(i,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("tr",{staticClass:"table-row"},t._l(t.normalizedColumns,(function(a,e){return n("td",{key:e,staticClass:"table-cell"},[n("span",[t._v(t._s(a))])])})),0)}),[],!1,null,"b0f3a8d0",null).exports,r=(n("LvDl"),n("vDqi")),l=n.n(r),d={name:"DatatableWrapper",props:["columns","scope","paginator","api","tableData"],components:{ChevronRightIcon:e.h,ChevronLeftIcon:e.g,DatatableCell:o,ChevronUpIcon:e.i},computed:{hasData:function(){return this.data&&this.data.data&&this.data.data.length>0},floatPages:function(){return[this.pageIndex-1,this.pageIndex,this.pageIndex+1]}},data:function(){return{data:void 0,isLoading:!0,pageIndex:1,filter:{sort:"DESC",field:void 0}}},methods:{goToPage:function(t){t>this.data.meta.last_page||0===t||(this.pageIndex=t,this.getPage(t))},sort:function(t,a){a&&(this.filter.field=t,"DESC"===this.filter.sort?this.filter.sort="ASC":"ASC"===this.filter.sort&&(this.filter.sort="DESC"),this.getPage(this.pageIndex))},getPage:function(t){var a=this;this.URI=this.api,this.paginator&&(this.URI=this.URI+"?page="+t),this.filter.field&&(this.URI=this.URI+(this.paginator?"&":"?")+"sort="+this.filter.field+"&direction="+this.filter.sort),this.isLoading=!0,l.a.get(this.URI).then((function(t){a.data=t.data,a.$emit("data",t.data)})).catch((function(){return a.$isSomethingWrong()})).finally((function(){a.$emit("init",!0),a.isLoading=!1}))}},created:function(){this.api&&this.getPage(this.pageIndex),this.tableData&&(this.data=this.tableData,this.isLoading=!1)}},c=(n("pAxR"),Object(s.a)(d,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("div",{staticClass:"datatable"},[t.hasData?n("table",{staticClass:"table"},[n("thead",{staticClass:"table-header"},[n("tr",t._l(t.columns,(function(a,e){return a.hidden?t._e():n("th",{key:e,class:{sortable:a.sortable},on:{click:function(n){return t.sort(a.field,a.sortable)}}},[n("span",{staticClass:"text-theme"},[t._v(t._s(a.label))]),t._v(" "),a.sortable?n("chevron-up-icon",{staticClass:"filter-arrow",class:{"arrow-down":"ASC"===t.filter.sort},attrs:{size:"14"}}):t._e()],1)})),0)]),t._v(" "),n("tbody",{staticClass:"table-body"},[t._l(t.data.data,(function(a){return t._t("default",[n("DatatableCell",{key:a.id,attrs:{data:a}})],{row:a})}))],2)]):t._e(),t._v(" "),t.isLoading||t.hasData?t._e():t._t("empty-page"),t._v(" "),t.paginator&&t.hasData?n("div",{staticClass:"paginator-wrapper"},[t.data.meta.total>20&&t.data.meta.last_page<=6?n("ul",{staticClass:"pagination"},[n("li",{staticClass:"page-item previous"},[n("a",{staticClass:"page-link",class:{disabled:0==t.pageIndex},on:{click:function(a){return t.goToPage(t.pageIndex-1)}}},[n("chevron-left-icon",{staticClass:"icon",attrs:{size:"14"}})],1)]),t._v(" "),t._l(6,(function(a,e){return n("li",{key:e,staticClass:"page-item",on:{click:function(n){return t.goToPage(a)}}},[n("a",{staticClass:"page-link",class:{active:t.pageIndex===a}},[t._v("\n                    "+t._s(a)+"\n                ")])])})),t._v(" "),n("li",{staticClass:"page-item next"},[n("a",{staticClass:"page-link",class:{disabled:t.pageIndex+1==t.data.meta.last_page},on:{click:function(a){return t.goToPage(t.pageIndex+1)}}},[n("chevron-right-icon",{staticClass:"icon",attrs:{size:"14"}})],1)])],2):t._e(),t._v(" "),t.data.meta.total>20&&t.data.meta.last_page>6?n("ul",{staticClass:"pagination"},[n("li",{staticClass:"page-item previous"},[n("a",{staticClass:"page-link",class:{disabled:0==t.pageIndex},on:{click:function(a){return t.goToPage(t.pageIndex-1)}}},[n("chevron-left-icon",{staticClass:"icon",attrs:{size:"14"}})],1)]),t._v(" "),t.pageIndex>=5?n("li",{staticClass:"page-item",on:{click:function(a){return t.goToPage(1)}}},[n("a",{staticClass:"page-link"},[t._v("\n                    1\n                ")])]):t._e(),t._v(" "),t._l(5,(function(a,e){return t.pageIndex<5?n("li",{key:e,staticClass:"page-item",on:{click:function(n){return t.goToPage(a)}}},[n("a",{staticClass:"page-link",class:{active:t.pageIndex===a}},[t._v("\n                    "+t._s(a)+"\n                ")])]):t._e()})),t._v(" "),t.pageIndex>=5?n("li",{staticClass:"page-item"},[n("a",{staticClass:"page-link"},[t._v("...")])]):t._e(),t._v(" "),t._l(t.floatPages,(function(a,e){return t.pageIndex>=5&&t.pageIndex<t.data.meta.last_page-3?n("li",{key:e,staticClass:"page-item",on:{click:function(n){return t.goToPage(a)}}},[n("a",{staticClass:"page-link",class:{active:t.pageIndex===a}},[t._v("\n                    "+t._s(a)+"\n                ")])]):t._e()})),t._v(" "),t.pageIndex<t.data.meta.last_page-3?n("li",{staticClass:"page-item"},[n("a",{staticClass:"page-link"},[t._v("...")])]):t._e(),t._v(" "),t._l(5,(function(a,e){return t.pageIndex>t.data.meta.last_page-4?n("li",{key:e,staticClass:"page-item",on:{click:function(a){t.goToPage(t.data.meta.last_page-(4-e))}}},[n("a",{staticClass:"page-link",class:{active:t.pageIndex===t.data.meta.last_page-(4-e)}},[t._v("\n                    "+t._s(t.data.meta.last_page-(4-e))+"\n                ")])]):t._e()})),t._v(" "),t.pageIndex<t.data.meta.last_page-3?n("li",{staticClass:"page-item",on:{click:function(a){return t.goToPage(t.data.meta.last_page)}}},[n("a",{staticClass:"page-link"},[t._v("\n                    "+t._s(t.data.meta.last_page)+"\n                ")])]):t._e(),t._v(" "),n("li",{staticClass:"page-item next"},[n("a",{staticClass:"page-link",class:{disabled:t.pageIndex+1==t.data.meta.last_page},on:{click:function(a){return t.goToPage(t.pageIndex+1)}}},[n("chevron-right-icon",{staticClass:"icon",attrs:{size:"14"}})],1)])],2):t._e(),t._v(" "),n("span",{staticClass:"paginator-info"},[t._v(t._s(t.$t("datatable.paginate_info",{visible:t.data.meta.per_page,total:t.data.meta.total})))])]):t._e()],2)}),[],!1,null,"6d4d441a",null));a.a=c.exports},CK9m:function(t,a,n){var e=n("Wx07");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},Cbtr:function(t,a,n){"use strict";var e=n("5EXa");n.n(e).a},EX7C:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".table-tools[data-v-ea4dd90c] {\n  background: white;\n  display: flex;\n  justify-content: space-between;\n  padding: 15px 0 10px;\n  position: -webkit-sticky;\n  position: sticky;\n  top: 40px;\n  z-index: 9;\n}\n.table .cell-item[data-v-ea4dd90c] {\n  font-size: 0.9375em;\n  white-space: nowrap;\n}\n.table .name[data-v-ea4dd90c] {\n  font-weight: 700;\n  cursor: pointer;\n}\n@media only screen and (max-width: 690px) {\n.table-tools[data-v-ea4dd90c] {\n    padding: 0 0 5px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.table-tools[data-v-ea4dd90c] {\n    background: #141414;\n}\n.action-icons .icon[data-v-ea4dd90c] {\n    cursor: pointer;\n}\n.action-icons .icon circle[data-v-ea4dd90c], .action-icons .icon path[data-v-ea4dd90c], .action-icons .icon line[data-v-ea4dd90c], .action-icons .icon polyline[data-v-ea4dd90c] {\n    stroke: #bec6cf;\n}\n.user-thumbnail .info .email[data-v-ea4dd90c] {\n    color: #7d858c;\n}\n}\n",""])},Fqzo:function(t,a,n){"use strict";n("N10U");var e=n("KHd+"),i=Object(e.a)({},(function(){var t=this.$createElement,a=this._self._c||t;return a("svg",{staticClass:"preview-list-icon",attrs:{fill:"none",stroke:"currentColor","stroke-width":"1.5","fill-rule":"evenodd","stroke-linecap":"round","stroke-linejoin":"round",width:"15px",height:"15px",viewBox:"0 0 20 18",version:"1.1",xmlns:"http://www.w3.org/2000/svg","xmlns:xlink":"http://www.w3.org/1999/xlink"}},[a("rect",{attrs:{x:"9.77777778",y:"0",width:"6.22222222",height:"6.22222222"}}),this._v(" "),a("rect",{attrs:{x:"9.77777778",y:"9.77777778",width:"6.22222222",height:"6.22222222"}}),this._v(" "),a("line",{attrs:{x1:"0",y1:"2",x2:"6",y2:"2"}}),this._v(" "),a("line",{attrs:{x1:"0",y1:"8",x2:"6",y2:"8"}}),this._v(" "),a("line",{attrs:{x1:"0",y1:"14",x2:"6",y2:"14"}})])}),[],!1,null,null,null);a.a=i.exports},"GAT/":function(t,a,n){"use strict";n.r(a);var e=n("6TPS"),i=n("t5U/"),s=n("KfIT"),o=n("xxrA"),r=n("D62o"),l=n("THmQ"),d=n("Nv84"),c=n("CjXH"),p=n("2Sb1"),u=n("kPoH"),g=n("zTYo"),v=n("L2JU");n("vDqi");function b(t,a){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var e=Object.getOwnPropertySymbols(t);a&&(e=e.filter((function(a){return Object.getOwnPropertyDescriptor(t,a).enumerable}))),n.push.apply(n,e)}return n}function f(t,a,n){return a in t?Object.defineProperty(t,a,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[a]=n,t}var h={name:"Plans",components:{MobileActionButton:i.a,EmptyPageContent:s.a,DatatableWrapper:e.a,SectionTitle:l.a,MobileHeader:r.a,SwitchInput:o.a,Trash2Icon:c.ab,PageHeader:p.a,ButtonBase:d.a,ColorLabel:u.a,Edit2Icon:c.r,Spinner:g.a},computed:function(t){for(var a=1;a<arguments.length;a++){var n=null!=arguments[a]?arguments[a]:{};a%2?b(Object(n),!0).forEach((function(a){f(t,a,n[a])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):b(Object(n)).forEach((function(a){Object.defineProperty(t,a,Object.getOwnPropertyDescriptor(n,a))}))}return t}({},Object(v.b)(["config"]),{isEmptyPlans:function(){return!this.isLoading&&0===this.plans.length&&this.config.stripe_public_key},stripeIsNotConfigured:function(){return!this.config.stripe_public_key},stripeConfiguredWithPlans:function(){return!this.isLoading&&this.config.stripe_public_key}}),data:function(){return{isLoading:!0,plans:[],columns:[{label:this.$t("admin_page_plans.table.status"),field:"data.attributes.status",sortable:!1},{label:this.$t("admin_page_plans.table.name"),field:"data.attributes.name",sortable:!1},{label:this.$t("admin_page_plans.table.subscribers"),field:"data.attributes.subscribers",sortable:!1},{label:this.$t("admin_page_plans.table.price"),field:"data.attributes.price",sortable:!1},{label:this.$t("admin_page_plans.table.storage_capacity"),field:"data.attributes.capacity",sortable:!1},{label:this.$t("admin_page_user.table.action"),sortable:!1}]}},methods:{changeStatus:function(t,a){this.$updateText("/admin/plans/"+a,"is_active",t)}},created:function(){this.config.stripe_public_key||(this.isLoading=!1)}},m=(n("Cbtr"),n("KHd+")),_=Object(m.a)(h,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("div",{attrs:{id:"single-page"}},[n("div",{directives:[{name:"show",rawName:"v-show",value:t.stripeConfiguredWithPlans,expression:"stripeConfiguredWithPlans"}],attrs:{id:"page-content"}},[n("MobileHeader",{attrs:{title:t.$t(t.$router.currentRoute.meta.title)}}),t._v(" "),n("PageHeader",{attrs:{title:t.$t(t.$router.currentRoute.meta.title)}}),t._v(" "),t.config.stripe_public_key?n("div",{staticClass:"content-page"},[n("div",{staticClass:"table-tools"},[n("div",{staticClass:"buttons"},[n("router-link",{attrs:{to:{name:"PlanCreate"}}},[n("MobileActionButton",{attrs:{icon:"plus"}},[t._v("\n                            "+t._s(t.$t("admin_page_plans.create_plan_button"))+"\n                        ")])],1)],1),t._v(" "),n("div",{staticClass:"searching"})]),t._v(" "),n("DatatableWrapper",{staticClass:"table table-users",attrs:{api:"/api/admin/plans",paginator:!1,columns:t.columns},on:{data:function(a){t.plans=a},init:function(a){t.isLoading=!1}},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.row;return[n("tr",[n("td",{staticStyle:{"max-width":"80px"}},[n("span",{staticClass:"cell-item"},[n("SwitchInput",{staticClass:"switch",attrs:{state:e.data.attributes.status},on:{input:function(a){return t.changeStatus(a,e.data.id)}}})],1)]),t._v(" "),n("td",{staticClass:"name",staticStyle:{"min-width":"120px"}},[n("router-link",{staticClass:"cell-item",attrs:{to:{name:"PlanSettings",params:{id:e.data.id}},tag:"div"}},[n("span",[t._v(t._s(e.data.attributes.name))])])],1),t._v(" "),n("td",[n("span",{staticClass:"cell-item"},[t._v("\n                                "+t._s(e.data.attributes.subscribers)+"\n                            ")])]),t._v(" "),n("td",[n("span",{staticClass:"cell-item"},[t._v("\n                                "+t._s(e.data.attributes.price_formatted)+"\n                            ")])]),t._v(" "),n("td",[n("span",{staticClass:"cell-item"},[t._v("\n                                "+t._s(e.data.attributes.capacity_formatted)+"\n                            ")])]),t._v(" "),n("td",[n("div",{staticClass:"action-icons"},[n("router-link",{attrs:{to:{name:"PlanSettings",params:{id:e.data.id}}}},[n("edit-2-icon",{staticClass:"icon icon-edit",attrs:{size:"15"}})],1),t._v(" "),n("router-link",{attrs:{to:{name:"PlanDelete",params:{id:e.data.id}}}},[n("trash2-icon",{staticClass:"icon icon-trash",attrs:{size:"15"}})],1)],1)])])]}}],null,!1,3279855567)})],1):t._e()],1),t._v(" "),t.isEmptyPlans?n("EmptyPageContent",{attrs:{icon:"file",title:t.$t("admin_page_plans.empty.title"),description:t.$t("admin_page_plans.empty.description")}},[n("router-link",{attrs:{to:{name:"PlanCreate"},tag:"p"}},[n("ButtonBase",{attrs:{"button-style":"theme"}},[t._v(t._s(t.$t("admin_page_plans.empty.button")))])],1)],1):t._e(),t._v(" "),t.stripeIsNotConfigured?n("EmptyPageContent",{attrs:{icon:"settings",title:t.$t("activation.stripe.title"),description:t.$t("activation.stripe.description")}},[n("router-link",{attrs:{to:{name:"AppPayments"}}},[n("ButtonBase",{attrs:{"button-style":"theme"}},[t._v(t._s(t.$t("activation.stripe.button")))])],1)],1):t._e(),t._v(" "),t.isLoading?n("div",{attrs:{id:"loader"}},[n("Spinner")],1):t._e()],1)}),[],!1,null,"ea4dd90c",null);a.default=_.exports},IMud:function(t,a,n){var e=n("Kivv");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},JOXf:function(t,a,n){"use strict";var e=n("nr4+");n.n(e).a},KfIT:function(t,a,n){"use strict";var e=n("CjXH"),i={name:"EmptyPageContent",props:["icon","title","description"],components:{SettingsIcon:e.W,FileTextIcon:e.w,FileIcon:e.v}},s=(n("d0UK"),n("KHd+")),o=Object(s.a)(i,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("div",{staticClass:"empty-page-content"},[n("div",{staticClass:"content"},[n("div",{staticClass:"icon"},["file"===t.icon?n("file-icon",{attrs:{size:"38"}}):t._e(),t._v(" "),"file-text"===t.icon?n("file-text-icon",{attrs:{size:"38"}}):t._e(),t._v(" "),"settings"===t.icon?n("settings-icon",{attrs:{size:"38"}}):t._e()],1),t._v(" "),n("div",{staticClass:"header"},[n("h1",{staticClass:"title"},[t._v(t._s(t.title))]),t._v(" "),n("h2",{staticClass:"description"},[t._v(t._s(t.description))])]),t._v(" "),t._t("default")],2)])}),[],!1,null,"62696380",null);a.a=o.exports},Kivv:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".datatable[data-v-6d4d441a] {\n  height: 100%;\n}\n.table-row[data-v-6d4d441a] {\n  transition: 0.3s all ease;\n}\n.table-row-enter[data-v-6d4d441a],\n.table-row-leave-to[data-v-6d4d441a] {\n  opacity: 0;\n  transform: translateY(-100%);\n}\n.table-row-leave-active[data-v-6d4d441a] {\n  position: absolute;\n}\n.table[data-v-6d4d441a] {\n  width: 100%;\n  border-collapse: collapse;\n  overflow-x: auto;\n}\n.table tr[data-v-6d4d441a] {\n  width: 100%;\n}\n.table tr td[data-v-6d4d441a]:first-child, .table tr th[data-v-6d4d441a]:first-child {\n  padding-left: 15px;\n}\n.table tr td[data-v-6d4d441a]:last-child, .table tr th[data-v-6d4d441a]:last-child {\n  padding-right: 15px;\n  text-align: right;\n}\n.table .table-header[data-v-6d4d441a] {\n  margin-bottom: 10px;\n}\n.table .table-header tr td[data-v-6d4d441a], .table .table-header tr th[data-v-6d4d441a] {\n  padding: 12px;\n  text-align: left;\n}\n.table .table-header tr td span[data-v-6d4d441a], .table .table-header tr th span[data-v-6d4d441a] {\n  font-weight: 700;\n  font-size: 0.75em;\n  white-space: nowrap;\n}\n.table .table-header tr td.sortable[data-v-6d4d441a], .table .table-header tr th.sortable[data-v-6d4d441a] {\n  cursor: pointer;\n}\n.table .table-header tr td.sortable:hover .filter-arrow[data-v-6d4d441a], .table .table-header tr th.sortable:hover .filter-arrow[data-v-6d4d441a] {\n  opacity: 1;\n}\n.table .table-header tr td[data-v-6d4d441a]:last-child, .table .table-header tr th[data-v-6d4d441a]:last-child {\n  text-align: right;\n}\n.table .table-header .filter-arrow[data-v-6d4d441a] {\n  vertical-align: middle;\n  margin-left: 8px;\n  transition: 0.3s all ease;\n  opacity: 0;\n}\n.table .table-header .filter-arrow path[data-v-6d4d441a] {\n  fill: rgba(27, 37, 57, 0.7);\n}\n.table .table-header .filter-arrow.arrow-down[data-v-6d4d441a] {\n  transform: rotate(180deg);\n}\n.table .table-header span[data-v-6d4d441a] {\n  font-size: 13px;\n  font-weight: bold;\n}\n.table .table-body tr[data-v-6d4d441a] {\n  border-radius: 8px;\n}\n.table .table-body tr[data-v-6d4d441a]:hover {\n  background: #f4f5f6;\n}\n.table .table-body tr td[data-v-6d4d441a], .table .table-body tr th[data-v-6d4d441a] {\n  padding: 12px;\n}\n.table .table-body tr td:last-child button[data-v-6d4d441a], .table .table-body tr th:last-child button[data-v-6d4d441a] {\n  margin-right: 0;\n}\n.table .table-body span[data-v-6d4d441a], .table .table-body a.page-link[data-v-6d4d441a] {\n  font-size: 0.9375em;\n  font-weight: 700;\n  padding: 10px 35px 10px 0;\n  display: block;\n  white-space: nowrap;\n}\n.pagination .page-item[data-v-6d4d441a] {\n  padding: 3px;\n  display: inline-block;\n}\n.pagination .page-link[data-v-6d4d441a] {\n  width: 30px;\n  height: 30px;\n  display: block;\n  color: #1B2539;\n  border-radius: 6px;\n  text-align: center;\n  line-height: 2.4;\n  font-weight: bold;\n  font-size: 13px;\n  cursor: pointer;\n  transition: 0.15s all ease;\n}\n.pagination .page-link .icon[data-v-6d4d441a] {\n  vertical-align: middle;\n  margin-top: -2px;\n}\n.pagination .page-link[data-v-6d4d441a]:hover:not(.disabled) {\n  background: #f4f5f6;\n  color: #1B2539;\n}\n.pagination .page-link.active[data-v-6d4d441a] {\n  color: #1B2539;\n  background: #f4f5f6;\n}\n.pagination .page-link.disabled[data-v-6d4d441a] {\n  background: transparent;\n  cursor: default;\n}\n.pagination .page-link.disabled svg path[data-v-6d4d441a] {\n  fill: rgba(27, 37, 57, 0.7);\n}\n.paginator-wrapper[data-v-6d4d441a] {\n  margin-top: 30px;\n  margin-bottom: 40px;\n  display: flex;\n  justify-content: space-between;\n  align-items: center;\n}\n.paginator-wrapper .paginator-info[data-v-6d4d441a] {\n  font-size: 13px;\n  color: rgba(27, 37, 57, 0.7);\n}\n.user-preview[data-v-6d4d441a] {\n  display: flex;\n  align-items: center;\n  cursor: pointer;\n}\n.user-preview img[data-v-6d4d441a] {\n  width: 45px;\n  margin-right: 22px;\n}\n@media only screen and (max-width: 690px) {\n.paginator-wrapper[data-v-6d4d441a] {\n    display: block;\n    text-align: center;\n}\n.paginator-wrapper .paginator-info[data-v-6d4d441a] {\n    margin-top: 10px;\n    display: block;\n}\n}\n@media (prefers-color-scheme: dark) {\n.table .table-body tr[data-v-6d4d441a]:hover, .table .table-body th[data-v-6d4d441a]:hover {\n    background: #1e2024;\n}\n.paginator-wrapper .paginator-info[data-v-6d4d441a] {\n    color: #7d858c;\n}\n.pagination .page-link[data-v-6d4d441a] {\n    color: #7d858c;\n}\n.pagination .page-link svg polyline[data-v-6d4d441a] {\n    stroke: #bec6cf;\n}\n.pagination .page-link[data-v-6d4d441a]:hover:not(.disabled) {\n    color: #00BC7E;\n    background: rgba(0, 188, 126, 0.1);\n}\n.pagination .page-link.active[data-v-6d4d441a] {\n    color: #00BC7E;\n    background: rgba(0, 188, 126, 0.1);\n}\n.pagination .page-link.disabled[data-v-6d4d441a] {\n    background: transparent;\n    cursor: default;\n}\n.pagination .page-link.disabled svg polyline[data-v-6d4d441a] {\n    stroke: #7d858c;\n}\n}\n",""])},LedX:function(t,a,n){"use strict";var e=n("WEWl");n.n(e).a},MWZw:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".color-label[data-v-5c508dbf] {\n  text-transform: capitalize;\n  font-size: 0.75em;\n  display: inline-block;\n  border-radius: 6px;\n  font-weight: 700;\n  padding: 4px 6px;\n}\n.color-label.purple[data-v-5c508dbf] {\n  color: #9D66FE;\n  background: rgba(157, 102, 254, 0.1);\n}\n.color-label.yellow[data-v-5c508dbf] {\n  color: #FFBD2D;\n  background: rgba(255, 189, 45, 0.1);\n}\n.color-label.green[data-v-5c508dbf] {\n  color: #00BC7E;\n  background: rgba(0, 188, 126, 0.1);\n}\n.color-label.red[data-v-5c508dbf] {\n  color: #fd397a;\n  background: rgba(253, 57, 122, 0.1);\n}\n",""])},N10U:function(t,a,n){"use strict";var e=n("fPaL");n.n(e).a},SEx2:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".mobile-action-button[data-v-91a6ffa6] {\n  background: #f4f5f6;\n  margin-right: 15px;\n  border-radius: 8px;\n  padding: 7px 10px;\n  cursor: pointer;\n  border: none;\n  transition: 150ms all ease;\n}\n.mobile-action-button .flex[data-v-91a6ffa6] {\n  display: flex;\n  align-items: center;\n}\n.mobile-action-button .icon[data-v-91a6ffa6] {\n  margin-right: 10px;\n  font-size: 0.875em;\n}\n.mobile-action-button .icon path[data-v-91a6ffa6], .mobile-action-button .icon line[data-v-91a6ffa6], .mobile-action-button .icon polyline[data-v-91a6ffa6], .mobile-action-button .icon rect[data-v-91a6ffa6], .mobile-action-button .icon circle[data-v-91a6ffa6] {\n  transition: 150ms all ease;\n}\n.mobile-action-button .label[data-v-91a6ffa6] {\n  transition: 150ms all ease;\n  font-size: 0.875em;\n  font-weight: 700;\n  color: #1B2539;\n}\n.mobile-action-button[data-v-91a6ffa6]:active {\n  transform: scale(0.95);\n}\n@media (prefers-color-scheme: dark) {\n.mobile-action-button[data-v-91a6ffa6] {\n    background: #1e2024;\n}\n.mobile-action-button path[data-v-91a6ffa6], .mobile-action-button line[data-v-91a6ffa6], .mobile-action-button polyline[data-v-91a6ffa6], .mobile-action-button rect[data-v-91a6ffa6], .mobile-action-button circle[data-v-91a6ffa6] {\n    color: inherit;\n}\n.mobile-action-button .label[data-v-91a6ffa6] {\n    color: #bec6cf;\n}\n}\n",""])},THmQ:function(t,a,n){"use strict";var e={name:"SectionTitle"},i=(n("UHE7"),n("KHd+")),s=Object(i.a)(e,(function(){var t=this.$createElement;return(this._self._c||t)("b",{staticClass:"text-label"},[this._t("default")],2)}),[],!1,null,"6d799cf2",null);a.a=s.exports},UHE7:function(t,a,n){"use strict";var e=n("UmJ6");n.n(e).a},UmJ6:function(t,a,n){var e=n("vFyo");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},WEWl:function(t,a,n){var e=n("sGz8");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},WPpQ:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".empty-page-content[data-v-62696380] {\n  width: 100%;\n  height: 100%;\n  display: flex;\n  align-items: center;\n  text-align: center;\n}\n.empty-page-content .content[data-v-62696380] {\n  margin: 0 auto;\n  max-width: 360px;\n}\n.empty-page-content .content[data-v-62696380] .button-base {\n  margin: 0 auto;\n}\n.empty-page-content .icon path[data-v-62696380], .empty-page-content .icon polyline[data-v-62696380], .empty-page-content .icon line[data-v-62696380], .empty-page-content .icon circle[data-v-62696380] {\n  stroke: #00BC7E;\n}\n.empty-page-content .header[data-v-62696380] {\n  margin-top: 15px;\n  margin-bottom: 25px;\n}\n.empty-page-content .title[data-v-62696380] {\n  font-size: 1.4375em;\n  font-weight: 700;\n  padding-bottom: 5px;\n}\n.empty-page-content .description[data-v-62696380] {\n  font-size: 1em;\n  font-weight: 500;\n}\n",""])},Wx07:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".table-row[data-v-b0f3a8d0] {\n  border-radius: 8px;\n}\n.table-row[data-v-b0f3a8d0]:hover {\n  background: #f4f5f6;\n}\n.table-row .table-cell[data-v-b0f3a8d0] {\n  padding-top: 15px;\n  padding-bottom: 15px;\n}\n.table-row .table-cell[data-v-b0f3a8d0]:first-child {\n  padding-left: 15px;\n}\n.table-row .table-cell[data-v-b0f3a8d0]:last-child {\n  padding-right: 15px;\n  text-align: right;\n}\n.table-row .table-cell span[data-v-b0f3a8d0] {\n  font-size: 1em;\n  font-weight: bold;\n}\n",""])},YLwN:function(t,a,n){"use strict";var e=n("CK9m");n.n(e).a},d0UK:function(t,a,n){"use strict";var e=n("uBKk");n.n(e).a},fPaL:function(t,a,n){var e=n("sEJ9");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},kPoH:function(t,a,n){"use strict";var e={name:"ColorLabel",props:["color"]},i=(n("m6y/"),n("KHd+")),s=Object(i.a)(e,(function(){var t=this.$createElement;return(this._self._c||t)("b",{staticClass:"color-label",class:this.color},[this._t("default")],2)}),[],!1,null,"5c508dbf",null);a.a=s.exports},"m6y/":function(t,a,n){"use strict";var e=n("0rhn");n.n(e).a},"nr4+":function(t,a,n){var e=n("3eeM");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},omEc:function(t,a,n){"use strict";var e=n("+UVI");n.n(e).a},pAxR:function(t,a,n){"use strict";var e=n("IMud");n.n(e).a},sEJ9:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".preview-list-icon rect, .preview-list-icon line {\n  color: inherit;\n}\n",""])},sGz8:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".input-wrapper[data-v-421ca226] {\n  display: flex;\n  width: 100%;\n}\n.input-wrapper .input-label[data-v-421ca226] {\n  color: #1B2539;\n}\n.input-wrapper .switch-content[data-v-421ca226] {\n  width: 100%;\n}\n.input-wrapper .switch-content[data-v-421ca226]:last-child {\n  width: 80px;\n}\n.switch[data-v-421ca226] {\n  width: 50px;\n  height: 28px;\n  border-radius: 50px;\n  display: block;\n  background: #f1f1f5;\n  position: relative;\n  transition: 0.3s all ease;\n}\n.switch .switch-button[data-v-421ca226] {\n  transition: 0.3s all ease;\n  width: 22px;\n  height: 22px;\n  border-radius: 50px;\n  display: block;\n  background: white;\n  position: absolute;\n  top: 3px;\n  left: 3px;\n  box-shadow: 0 2px 4px rgba(37, 38, 94, 0.1);\n  cursor: pointer;\n}\n.switch.active .switch-button[data-v-421ca226] {\n  left: 25px;\n}\n@media (prefers-color-scheme: dark) {\n.switch[data-v-421ca226] {\n    background: #1e2024;\n}\n.popup-wrapper .switch[data-v-421ca226] {\n    background: #25272c;\n}\n}\n",""])},"t5U/":function(t,a,n){"use strict";var e=n("CjXH"),i={name:"MobileActionButton",props:["icon"],components:{SortingAndPreviewIcon:n("Fqzo").a,CheckSquareIcon:e.e,DollarSignIcon:e.p,CreditCardIcon:e.n,FolderPlusIcon:e.y,UserPlusIcon:e.fb,XSquareIcon:e.jb,CheckIcon:e.d,TrashIcon:e.bb,PlusIcon:e.Q,ListIcon:e.H,GridIcon:e.A}},s=(n("omEc"),n("KHd+")),o=Object(s.a)(i,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("button",{staticClass:"mobile-action-button"},[n("div",{staticClass:"flex"},["credit-card"===t.icon?n("credit-card-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):t._e(),t._v(" "),"folder-plus"===t.icon?n("folder-plus-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):t._e(),t._v(" "),"th-list"===t.icon?n("list-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):t._e(),t._v(" "),"trash"===t.icon?n("trash-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):t._e(),t._v(" "),"th"===t.icon?n("grid-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):t._e(),t._v(" "),"user-plus"===t.icon?n("user-plus-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):t._e(),t._v(" "),"plus"===t.icon?n("plus-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):t._e(),t._v(" "),"check-square"===t.icon?n("check-square-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):t._e(),t._v(" "),"x-square"===t.icon?n("x-square-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):t._e(),t._v(" "),"check"===t.icon?n("check-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):t._e(),t._v(" "),"dollar-sign"===t.icon?n("dollar-sign-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):t._e(),t._v(" "),"preview-sorting"===t.icon?n("sorting-and-preview-icon",{staticClass:"icon dark-text-theme preview-sorting",attrs:{size:"15"}}):t._e(),t._v(" "),n("span",{staticClass:"label"},[t._t("default")],2)],1)])}),[],!1,null,"91a6ffa6",null);a.a=o.exports},uBKk:function(t,a,n){var e=n("WPpQ");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},vFyo:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".text-label[data-v-6d799cf2] {\n  font-size: 0.75em;\n  color: #AFAFAF;\n  font-weight: 700;\n  display: block;\n  margin-bottom: 20px;\n}\n@media (prefers-color-scheme: dark) {\n.text-label[data-v-6d799cf2] {\n    color: #00BC7E;\n}\n}\n",""])},xxrA:function(t,a,n){"use strict";var e={name:"SwitchInput",props:["label","name","state","info"],data:function(){return{isSwitched:void 0}},methods:{changeState:function(){this.isSwitched=!this.isSwitched,this.$emit("input",this.isSwitched)}},mounted:function(){this.isSwitched=this.state}},i=(n("LedX"),n("KHd+")),s=Object(i.a)(e,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("div",{staticClass:"input-wrapper"},[n("div",{staticClass:"switch-content"},[t.label?n("label",{staticClass:"input-label"},[t._v(t._s(t.label)+":")]):t._e(),t._v(" "),t.info?n("small",{staticClass:"input-info"},[t._v(t._s(t.info))]):t._e()]),t._v(" "),n("div",{staticClass:"switch-content text-right"},[n("div",{staticClass:"switch",class:{active:t.isSwitched},on:{click:t.changeState}},[n("div",{staticClass:"switch-button"})])])])}),[],!1,null,"421ca226",null);a.a=s.exports}}]);