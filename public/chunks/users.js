(window.webpackJsonp=window.webpackJsonp||[]).push([[82],{"+UVI":function(a,t,e){var n=e("SEx2");"string"==typeof n&&(n=[[a.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(a.exports=n.locals)},"0rhn":function(a,t,e){var n=e("MWZw");"string"==typeof n&&(n=[[a.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(a.exports=n.locals)},"2Sb1":function(a,t,e){"use strict";var n={name:"PageHeader",props:["title","canBack"],components:{ChevronLeftIcon:e("CjXH").g}},i=(e("JOXf"),e("KHd+")),s=Object(i.a)(n,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("div",{staticClass:"page-header"},[a.canBack?e("div",{staticClass:"go-back",on:{click:function(t){return a.$router.back()}}},[e("chevron-left-icon",{attrs:{size:"17"}})],1):a._e(),a._v(" "),e("div",{staticClass:"content"},[e("h1",{staticClass:"title"},[a._v(a._s(a.title))])])])}),[],!1,null,"9fd0a424",null);t.a=s.exports},"3eeM":function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".page-header[data-v-9fd0a424] {\n  display: flex;\n  align-items: center;\n  background: white;\n  z-index: 9;\n  width: 100%;\n  position: -webkit-sticky;\n  position: sticky;\n  top: 0;\n  padding-top: 20px;\n  padding-bottom: 20px;\n}\n.page-header .title[data-v-9fd0a424] {\n  font-size: 1.125em;\n  font-weight: 700;\n  color: #1B2539;\n}\n.page-header .go-back[data-v-9fd0a424] {\n  margin-right: 10px;\n  cursor: pointer;\n}\n.page-header .go-back svg[data-v-9fd0a424] {\n  vertical-align: middle;\n  margin-top: -4px;\n}\n@media only screen and (max-width: 960px) {\n.page-header .title[data-v-9fd0a424] {\n    font-size: 1.125em;\n}\n}\n@media only screen and (max-width: 690px) {\n.page-header[data-v-9fd0a424] {\n    display: none;\n}\n}\n@media (prefers-color-scheme: dark) {\n.page-header[data-v-9fd0a424] {\n    background: #141414;\n}\n.page-header .title[data-v-9fd0a424] {\n    color: #bec6cf;\n}\n.page-header .icon path[data-v-9fd0a424] {\n    fill: #00BC7E;\n}\n}\n",""])},"6TPS":function(a,t,e){"use strict";var n=e("CjXH"),i={props:["data"],computed:{normalizedColumns:function(){return this.data.id&&delete this.data.id,Object.values(this.data)}}},s=(e("YLwN"),e("KHd+")),o=Object(s.a)(i,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("tr",{staticClass:"table-row"},a._l(a.normalizedColumns,(function(t,n){return e("td",{key:n,staticClass:"table-cell"},[e("span",[a._v(a._s(t))])])})),0)}),[],!1,null,"b0f3a8d0",null).exports,r=(e("LvDl"),e("vDqi")),l=e.n(r),d={name:"DatatableWrapper",props:["columns","scope","paginator","api","tableData"],components:{ChevronRightIcon:n.h,ChevronLeftIcon:n.g,DatatableCell:o,ChevronUpIcon:n.i},computed:{hasData:function(){return this.data&&this.data.data&&this.data.data.length>0},floatPages:function(){return[this.pageIndex-1,this.pageIndex,this.pageIndex+1]}},data:function(){return{data:void 0,isLoading:!0,pageIndex:1,filter:{sort:"DESC",field:void 0}}},methods:{goToPage:function(a){a>this.data.meta.last_page||0===a||(this.pageIndex=a,this.getPage(a))},sort:function(a,t){t&&(this.filter.field=a,"DESC"===this.filter.sort?this.filter.sort="ASC":"ASC"===this.filter.sort&&(this.filter.sort="DESC"),this.getPage(this.pageIndex))},getPage:function(a){var t=this;this.URI=this.api,this.paginator&&(this.URI=this.URI+"?page="+a),this.filter.field&&(this.URI=this.URI+(this.paginator?"&":"?")+"sort="+this.filter.field+"&direction="+this.filter.sort),this.isLoading=!0,l.a.get(this.URI).then((function(a){t.data=a.data,t.$emit("data",a.data)})).catch((function(){return t.$isSomethingWrong()})).finally((function(){t.$emit("init",!0),t.isLoading=!1}))}},created:function(){this.api&&this.getPage(this.pageIndex),this.tableData&&(this.data=this.tableData,this.isLoading=!1)}},c=(e("pAxR"),Object(s.a)(d,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("div",{staticClass:"datatable"},[a.hasData?e("table",{staticClass:"table"},[e("thead",{staticClass:"table-header"},[e("tr",a._l(a.columns,(function(t,n){return t.hidden?a._e():e("th",{key:n,class:{sortable:t.sortable},on:{click:function(e){return a.sort(t.field,t.sortable)}}},[e("span",{staticClass:"text-theme"},[a._v(a._s(t.label))]),a._v(" "),t.sortable?e("chevron-up-icon",{staticClass:"filter-arrow",class:{"arrow-down":"ASC"===a.filter.sort},attrs:{size:"14"}}):a._e()],1)})),0)]),a._v(" "),e("tbody",{staticClass:"table-body"},[a._l(a.data.data,(function(t){return a._t("default",[e("DatatableCell",{key:t.id,attrs:{data:t}})],{row:t})}))],2)]):a._e(),a._v(" "),a.isLoading||a.hasData?a._e():a._t("empty-page"),a._v(" "),a.paginator&&a.hasData?e("div",{staticClass:"paginator-wrapper"},[a.data.meta.total>20&&a.data.meta.last_page<=6?e("ul",{staticClass:"pagination"},[e("li",{staticClass:"page-item previous"},[e("a",{staticClass:"page-link",class:{disabled:0==a.pageIndex},on:{click:function(t){return a.goToPage(a.pageIndex-1)}}},[e("chevron-left-icon",{staticClass:"icon",attrs:{size:"14"}})],1)]),a._v(" "),a._l(6,(function(t,n){return e("li",{key:n,staticClass:"page-item",on:{click:function(e){return a.goToPage(t)}}},[e("a",{staticClass:"page-link",class:{active:a.pageIndex===t}},[a._v("\n                    "+a._s(t)+"\n                ")])])})),a._v(" "),e("li",{staticClass:"page-item next"},[e("a",{staticClass:"page-link",class:{disabled:a.pageIndex+1==a.data.meta.last_page},on:{click:function(t){return a.goToPage(a.pageIndex+1)}}},[e("chevron-right-icon",{staticClass:"icon",attrs:{size:"14"}})],1)])],2):a._e(),a._v(" "),a.data.meta.total>20&&a.data.meta.last_page>6?e("ul",{staticClass:"pagination"},[e("li",{staticClass:"page-item previous"},[e("a",{staticClass:"page-link",class:{disabled:0==a.pageIndex},on:{click:function(t){return a.goToPage(a.pageIndex-1)}}},[e("chevron-left-icon",{staticClass:"icon",attrs:{size:"14"}})],1)]),a._v(" "),a.pageIndex>=5?e("li",{staticClass:"page-item",on:{click:function(t){return a.goToPage(1)}}},[e("a",{staticClass:"page-link"},[a._v("\n                    1\n                ")])]):a._e(),a._v(" "),a._l(5,(function(t,n){return a.pageIndex<5?e("li",{key:n,staticClass:"page-item",on:{click:function(e){return a.goToPage(t)}}},[e("a",{staticClass:"page-link",class:{active:a.pageIndex===t}},[a._v("\n                    "+a._s(t)+"\n                ")])]):a._e()})),a._v(" "),a.pageIndex>=5?e("li",{staticClass:"page-item"},[e("a",{staticClass:"page-link"},[a._v("...")])]):a._e(),a._v(" "),a._l(a.floatPages,(function(t,n){return a.pageIndex>=5&&a.pageIndex<a.data.meta.last_page-3?e("li",{key:n,staticClass:"page-item",on:{click:function(e){return a.goToPage(t)}}},[e("a",{staticClass:"page-link",class:{active:a.pageIndex===t}},[a._v("\n                    "+a._s(t)+"\n                ")])]):a._e()})),a._v(" "),a.pageIndex<a.data.meta.last_page-3?e("li",{staticClass:"page-item"},[e("a",{staticClass:"page-link"},[a._v("...")])]):a._e(),a._v(" "),a._l(5,(function(t,n){return a.pageIndex>a.data.meta.last_page-4?e("li",{key:n,staticClass:"page-item",on:{click:function(t){a.goToPage(a.data.meta.last_page-(4-n))}}},[e("a",{staticClass:"page-link",class:{active:a.pageIndex===a.data.meta.last_page-(4-n)}},[a._v("\n                    "+a._s(a.data.meta.last_page-(4-n))+"\n                ")])]):a._e()})),a._v(" "),a.pageIndex<a.data.meta.last_page-3?e("li",{staticClass:"page-item",on:{click:function(t){return a.goToPage(a.data.meta.last_page)}}},[e("a",{staticClass:"page-link"},[a._v("\n                    "+a._s(a.data.meta.last_page)+"\n                ")])]):a._e(),a._v(" "),e("li",{staticClass:"page-item next"},[e("a",{staticClass:"page-link",class:{disabled:a.pageIndex+1==a.data.meta.last_page},on:{click:function(t){return a.goToPage(a.pageIndex+1)}}},[e("chevron-right-icon",{staticClass:"icon",attrs:{size:"14"}})],1)])],2):a._e(),a._v(" "),e("span",{staticClass:"paginator-info"},[a._v(a._s(a.$t("datatable.paginate_info",{visible:a.data.meta.per_page,total:a.data.meta.total})))])]):a._e()],2)}),[],!1,null,"6d4d441a",null));t.a=c.exports},"7h7L":function(a,t,e){"use strict";var n=e("vAU0");e.n(n).a},CK9m:function(a,t,e){var n=e("Wx07");"string"==typeof n&&(n=[[a.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(a.exports=n.locals)},Fqzo:function(a,t,e){"use strict";e("N10U");var n=e("KHd+"),i=Object(n.a)({},(function(){var a=this.$createElement,t=this._self._c||a;return t("svg",{staticClass:"preview-list-icon",attrs:{fill:"none",stroke:"currentColor","stroke-width":"1.5","fill-rule":"evenodd","stroke-linecap":"round","stroke-linejoin":"round",width:"15px",height:"15px",viewBox:"0 0 20 18",version:"1.1",xmlns:"http://www.w3.org/2000/svg","xmlns:xlink":"http://www.w3.org/1999/xlink"}},[t("rect",{attrs:{x:"9.77777778",y:"0",width:"6.22222222",height:"6.22222222"}}),this._v(" "),t("rect",{attrs:{x:"9.77777778",y:"9.77777778",width:"6.22222222",height:"6.22222222"}}),this._v(" "),t("line",{attrs:{x1:"0",y1:"2",x2:"6",y2:"2"}}),this._v(" "),t("line",{attrs:{x1:"0",y1:"8",x2:"6",y2:"8"}}),this._v(" "),t("line",{attrs:{x1:"0",y1:"14",x2:"6",y2:"14"}})])}),[],!1,null,null,null);t.a=i.exports},GELx:function(a,t,e){"use strict";var n={name:"DatatableCellImage",props:["image","title","description","image-size"]},i=(e("Tm5p"),e("KHd+")),s=Object(i.a)(n,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("div",{staticClass:"cell-image-thumbnail"},[a.image?e("div",{staticClass:"image",class:a.imageSize},[e("img",{attrs:{src:a.image,alt:a.title}}),a._v(" "),e("img",{staticClass:"blurred",attrs:{src:a.image,alt:a.title}})]):a._e(),a._v(" "),e("div",{staticClass:"info"},[a.title?e("b",{staticClass:"name"},[a._v(a._s(a.title))]):a._e(),a._v(" "),a.description?e("span",{staticClass:"description"},[a._v(a._s(a.description))]):a._e()])])}),[],!1,null,"2cd07f63",null);t.a=s.exports},IMud:function(a,t,e){var n=e("Kivv");"string"==typeof n&&(n=[[a.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(a.exports=n.locals)},JOXf:function(a,t,e){"use strict";var n=e("nr4+");e.n(n).a},Kivv:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".datatable[data-v-6d4d441a] {\n  height: 100%;\n}\n.table-row[data-v-6d4d441a] {\n  transition: 0.3s all ease;\n}\n.table-row-enter[data-v-6d4d441a],\n.table-row-leave-to[data-v-6d4d441a] {\n  opacity: 0;\n  transform: translateY(-100%);\n}\n.table-row-leave-active[data-v-6d4d441a] {\n  position: absolute;\n}\n.table[data-v-6d4d441a] {\n  width: 100%;\n  border-collapse: collapse;\n  overflow-x: auto;\n}\n.table tr[data-v-6d4d441a] {\n  width: 100%;\n}\n.table tr td[data-v-6d4d441a]:first-child, .table tr th[data-v-6d4d441a]:first-child {\n  padding-left: 15px;\n}\n.table tr td[data-v-6d4d441a]:last-child, .table tr th[data-v-6d4d441a]:last-child {\n  padding-right: 15px;\n  text-align: right;\n}\n.table .table-header[data-v-6d4d441a] {\n  margin-bottom: 10px;\n}\n.table .table-header tr td[data-v-6d4d441a], .table .table-header tr th[data-v-6d4d441a] {\n  padding: 12px;\n  text-align: left;\n}\n.table .table-header tr td span[data-v-6d4d441a], .table .table-header tr th span[data-v-6d4d441a] {\n  font-weight: 700;\n  font-size: 0.75em;\n  white-space: nowrap;\n}\n.table .table-header tr td.sortable[data-v-6d4d441a], .table .table-header tr th.sortable[data-v-6d4d441a] {\n  cursor: pointer;\n}\n.table .table-header tr td.sortable:hover .filter-arrow[data-v-6d4d441a], .table .table-header tr th.sortable:hover .filter-arrow[data-v-6d4d441a] {\n  opacity: 1;\n}\n.table .table-header tr td[data-v-6d4d441a]:last-child, .table .table-header tr th[data-v-6d4d441a]:last-child {\n  text-align: right;\n}\n.table .table-header .filter-arrow[data-v-6d4d441a] {\n  vertical-align: middle;\n  margin-left: 8px;\n  transition: 0.3s all ease;\n  opacity: 0;\n}\n.table .table-header .filter-arrow path[data-v-6d4d441a] {\n  fill: rgba(27, 37, 57, 0.7);\n}\n.table .table-header .filter-arrow.arrow-down[data-v-6d4d441a] {\n  transform: rotate(180deg);\n}\n.table .table-header span[data-v-6d4d441a] {\n  font-size: 13px;\n  font-weight: bold;\n}\n.table .table-body tr[data-v-6d4d441a] {\n  border-radius: 8px;\n}\n.table .table-body tr[data-v-6d4d441a]:hover {\n  background: #f4f5f6;\n}\n.table .table-body tr td[data-v-6d4d441a], .table .table-body tr th[data-v-6d4d441a] {\n  padding: 12px;\n}\n.table .table-body tr td:last-child button[data-v-6d4d441a], .table .table-body tr th:last-child button[data-v-6d4d441a] {\n  margin-right: 0;\n}\n.table .table-body span[data-v-6d4d441a], .table .table-body a.page-link[data-v-6d4d441a] {\n  font-size: 0.9375em;\n  font-weight: 700;\n  padding: 10px 35px 10px 0;\n  display: block;\n  white-space: nowrap;\n}\n.pagination .page-item[data-v-6d4d441a] {\n  padding: 3px;\n  display: inline-block;\n}\n.pagination .page-link[data-v-6d4d441a] {\n  width: 30px;\n  height: 30px;\n  display: block;\n  color: #1B2539;\n  border-radius: 6px;\n  text-align: center;\n  line-height: 2.4;\n  font-weight: bold;\n  font-size: 13px;\n  cursor: pointer;\n  transition: 0.15s all ease;\n}\n.pagination .page-link .icon[data-v-6d4d441a] {\n  vertical-align: middle;\n  margin-top: -2px;\n}\n.pagination .page-link[data-v-6d4d441a]:hover:not(.disabled) {\n  background: #f4f5f6;\n  color: #1B2539;\n}\n.pagination .page-link.active[data-v-6d4d441a] {\n  color: #1B2539;\n  background: #f4f5f6;\n}\n.pagination .page-link.disabled[data-v-6d4d441a] {\n  background: transparent;\n  cursor: default;\n}\n.pagination .page-link.disabled svg path[data-v-6d4d441a] {\n  fill: rgba(27, 37, 57, 0.7);\n}\n.paginator-wrapper[data-v-6d4d441a] {\n  margin-top: 30px;\n  margin-bottom: 40px;\n  display: flex;\n  justify-content: space-between;\n  align-items: center;\n}\n.paginator-wrapper .paginator-info[data-v-6d4d441a] {\n  font-size: 13px;\n  color: rgba(27, 37, 57, 0.7);\n}\n.user-preview[data-v-6d4d441a] {\n  display: flex;\n  align-items: center;\n  cursor: pointer;\n}\n.user-preview img[data-v-6d4d441a] {\n  width: 45px;\n  margin-right: 22px;\n}\n@media only screen and (max-width: 690px) {\n.paginator-wrapper[data-v-6d4d441a] {\n    display: block;\n    text-align: center;\n}\n.paginator-wrapper .paginator-info[data-v-6d4d441a] {\n    margin-top: 10px;\n    display: block;\n}\n}\n@media (prefers-color-scheme: dark) {\n.table .table-body tr[data-v-6d4d441a]:hover, .table .table-body th[data-v-6d4d441a]:hover {\n    background: #1e2024;\n}\n.paginator-wrapper .paginator-info[data-v-6d4d441a] {\n    color: #7d858c;\n}\n.pagination .page-link[data-v-6d4d441a] {\n    color: #7d858c;\n}\n.pagination .page-link svg polyline[data-v-6d4d441a] {\n    stroke: #bec6cf;\n}\n.pagination .page-link[data-v-6d4d441a]:hover:not(.disabled) {\n    color: #00BC7E;\n    background: rgba(0, 188, 126, 0.1);\n}\n.pagination .page-link.active[data-v-6d4d441a] {\n    color: #00BC7E;\n    background: rgba(0, 188, 126, 0.1);\n}\n.pagination .page-link.disabled[data-v-6d4d441a] {\n    background: transparent;\n    cursor: default;\n}\n.pagination .page-link.disabled svg polyline[data-v-6d4d441a] {\n    stroke: #7d858c;\n}\n}\n",""])},MWZw:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".color-label[data-v-5c508dbf] {\n  text-transform: capitalize;\n  font-size: 0.75em;\n  display: inline-block;\n  border-radius: 6px;\n  font-weight: 700;\n  padding: 4px 6px;\n}\n.color-label.purple[data-v-5c508dbf] {\n  color: #9D66FE;\n  background: rgba(157, 102, 254, 0.1);\n}\n.color-label.yellow[data-v-5c508dbf] {\n  color: #FFBD2D;\n  background: rgba(255, 189, 45, 0.1);\n}\n.color-label.green[data-v-5c508dbf] {\n  color: #00BC7E;\n  background: rgba(0, 188, 126, 0.1);\n}\n.color-label.red[data-v-5c508dbf] {\n  color: #fd397a;\n  background: rgba(253, 57, 122, 0.1);\n}\n",""])},N10U:function(a,t,e){"use strict";var n=e("fPaL");e.n(n).a},SEx2:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".mobile-action-button[data-v-91a6ffa6] {\n  background: #f4f5f6;\n  margin-right: 15px;\n  border-radius: 8px;\n  padding: 7px 10px;\n  cursor: pointer;\n  border: none;\n  transition: 150ms all ease;\n}\n.mobile-action-button .flex[data-v-91a6ffa6] {\n  display: flex;\n  align-items: center;\n}\n.mobile-action-button .icon[data-v-91a6ffa6] {\n  margin-right: 10px;\n  font-size: 0.875em;\n}\n.mobile-action-button .icon path[data-v-91a6ffa6], .mobile-action-button .icon line[data-v-91a6ffa6], .mobile-action-button .icon polyline[data-v-91a6ffa6], .mobile-action-button .icon rect[data-v-91a6ffa6], .mobile-action-button .icon circle[data-v-91a6ffa6] {\n  transition: 150ms all ease;\n}\n.mobile-action-button .label[data-v-91a6ffa6] {\n  transition: 150ms all ease;\n  font-size: 0.875em;\n  font-weight: 700;\n  color: #1B2539;\n}\n.mobile-action-button[data-v-91a6ffa6]:active {\n  transform: scale(0.95);\n}\n@media (prefers-color-scheme: dark) {\n.mobile-action-button[data-v-91a6ffa6] {\n    background: #1e2024;\n}\n.mobile-action-button path[data-v-91a6ffa6], .mobile-action-button line[data-v-91a6ffa6], .mobile-action-button polyline[data-v-91a6ffa6], .mobile-action-button rect[data-v-91a6ffa6], .mobile-action-button circle[data-v-91a6ffa6] {\n    color: inherit;\n}\n.mobile-action-button .label[data-v-91a6ffa6] {\n    color: #bec6cf;\n}\n}\n",""])},THmQ:function(a,t,e){"use strict";var n={name:"SectionTitle"},i=(e("UHE7"),e("KHd+")),s=Object(i.a)(n,(function(){var a=this.$createElement;return(this._self._c||a)("b",{staticClass:"text-label"},[this._t("default")],2)}),[],!1,null,"6d799cf2",null);t.a=s.exports},Tm5p:function(a,t,e){"use strict";var n=e("XGzT");e.n(n).a},UHE7:function(a,t,e){"use strict";var n=e("UmJ6");e.n(n).a},UmJ6:function(a,t,e){var n=e("vFyo");"string"==typeof n&&(n=[[a.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(a.exports=n.locals)},Wx07:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".table-row[data-v-b0f3a8d0] {\n  border-radius: 8px;\n}\n.table-row[data-v-b0f3a8d0]:hover {\n  background: #f4f5f6;\n}\n.table-row .table-cell[data-v-b0f3a8d0] {\n  padding-top: 15px;\n  padding-bottom: 15px;\n}\n.table-row .table-cell[data-v-b0f3a8d0]:first-child {\n  padding-left: 15px;\n}\n.table-row .table-cell[data-v-b0f3a8d0]:last-child {\n  padding-right: 15px;\n  text-align: right;\n}\n.table-row .table-cell span[data-v-b0f3a8d0] {\n  font-size: 1em;\n  font-weight: bold;\n}\n",""])},XGzT:function(a,t,e){var n=e("eE9K");"string"==typeof n&&(n=[[a.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(a.exports=n.locals)},YLwN:function(a,t,e){"use strict";var n=e("CK9m");e.n(n).a},ZIRg:function(a,t,e){"use strict";e.r(t);var n=e("GELx"),i=e("6TPS"),s=e("t5U/"),o=e("D62o"),r=e("THmQ"),l=e("Nv84"),d=e("CjXH"),c=e("2Sb1"),p=e("kPoH"),g=e("zTYo"),b=e("L2JU");e("vDqi");function u(a,t){var e=Object.keys(a);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(a);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(a,t).enumerable}))),e.push.apply(e,n)}return e}function f(a,t,e){return t in a?Object.defineProperty(a,t,{value:e,enumerable:!0,configurable:!0,writable:!0}):a[t]=e,a}var v={name:"Profile",components:{DatatableCellImage:n.a,MobileActionButton:s.a,DatatableWrapper:i.a,SectionTitle:r.a,MobileHeader:o.a,Trash2Icon:d.ab,PageHeader:c.a,ButtonBase:l.a,ColorLabel:p.a,Edit2Icon:d.r,Spinner:g.a},computed:function(a){for(var t=1;t<arguments.length;t++){var e=null!=arguments[t]?arguments[t]:{};t%2?u(Object(e),!0).forEach((function(t){f(a,t,e[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(a,Object.getOwnPropertyDescriptors(e)):u(Object(e)).forEach((function(t){Object.defineProperty(a,t,Object.getOwnPropertyDescriptor(e,t))}))}return a}({},Object(b.b)(["config"])),data:function(){return{isLoading:!0,columns:void 0}},methods:{getRoleColor:function(a){switch(a){case"admin":return"purple";case"user":return"yellow"}}},created:function(){this.columns=[{label:this.$t("admin_page_user.table.name"),field:"email",sortable:!0},{label:this.$t("admin_page_user.table.role"),field:"role",sortable:!0},{label:this.$t("admin_page_user.table.plan"),field:"subscription",sortable:!1,hidden:!this.config.isSaaS},{label:this.$t("admin_page_user.table.storage_used"),field:"used",sortable:!0},{label:this.$t("admin_page_user.table.storage_capacity"),field:"settings.storage_capacity",sortable:!0,hidden:!this.config.storageLimit},{label:this.$t("admin_page_user.table.created_at"),field:"created_at",sortable:!0},{label:this.$t("admin_page_user.table.action"),field:"data.action",sortable:!1}]}},h=(e("7h7L"),e("KHd+")),m=Object(h.a)(v,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("div",{attrs:{id:"single-page"}},[e("div",{attrs:{id:"page-content"}},[e("MobileHeader",{attrs:{title:a.$t(a.$router.currentRoute.meta.title)}}),a._v(" "),e("PageHeader",{attrs:{title:a.$t(a.$router.currentRoute.meta.title)}}),a._v(" "),e("div",{staticClass:"content-page"},[e("div",{staticClass:"table-tools"},[e("div",{staticClass:"buttons"},[e("router-link",{attrs:{to:{name:"UserCreate"}}},[e("MobileActionButton",{attrs:{icon:"user-plus"}},[a._v("\n                            "+a._s(a.$t("admin_page_user.create_user.submit"))+"\n                        ")])],1)],1)]),a._v(" "),e("DatatableWrapper",{staticClass:"table table-users",attrs:{api:"/api/admin/users",paginator:!0,columns:a.columns},on:{init:function(t){a.isLoading=!1}},scopedSlots:a._u([{key:"default",fn:function(t){var n=t.row;return[e("tr",[e("td",{staticStyle:{"min-width":"320px"}},[e("router-link",{attrs:{to:{name:"UserDetail",params:{id:n.data.id}}}},[e("DatatableCellImage",{attrs:{image:n.data.relationships.settings.data.attributes.avatar,title:n.data.relationships.settings.data.attributes.name,description:n.data.attributes.email}})],1)],1),a._v(" "),e("td",[e("ColorLabel",{attrs:{color:a.getRoleColor(n.data.attributes.role)}},[a._v("\n                                "+a._s(n.data.attributes.role)+"\n                            ")])],1),a._v(" "),a.config.isSaaS?e("td",[n.data.attributes.subscription?e("span",{staticClass:"cell-item"},[a._v("\n                                "+a._s(a.$t("global.premium"))+"\n                            ")]):e("span",{staticClass:"cell-item"},[a._v("\n                                "+a._s(a.$t("global.free"))+"\n                            ")])]):a._e(),a._v(" "),e("td",[0!==n.data.attributes.storage.capacity?e("span",{staticClass:"cell-item"},[a._v("\n                                "+a._s(n.data.attributes.storage.used_formatted)+"\n                            ")]):a._e(),a._v(" "),0==n.data.attributes.storage.capacity?e("span",{staticClass:"cell-item"},[a._v("\n                                -\n                            ")]):a._e()]),a._v(" "),a.config.storageLimit?e("td",[0!==n.data.attributes.storage.capacity?e("span",{staticClass:"cell-item"},[a._v("\n                                "+a._s(n.data.attributes.storage.capacity_formatted)+"\n                            ")]):a._e(),a._v(" "),0==n.data.attributes.storage.capacity?e("span",{staticClass:"cell-item"},[a._v("\n                                -\n                            ")]):a._e()]):a._e(),a._v(" "),e("td",[e("span",{staticClass:"cell-item"},[a._v("\n                                "+a._s(n.data.attributes.created_at_formatted)+"\n                            ")])]),a._v(" "),e("td",[e("div",{staticClass:"action-icons"},[e("router-link",{attrs:{to:{name:"UserDetail",params:{id:n.data.id}}}},[e("Edit2Icon",{staticClass:"icon icon-edit",attrs:{size:"15"}})],1),a._v(" "),e("router-link",{attrs:{to:{name:"UserDelete",params:{id:n.data.id}}}},[e("Trash2Icon",{staticClass:"icon icon-trash",attrs:{size:"15"}})],1)],1)])])]}}])})],1)],1),a._v(" "),a.isLoading?e("div",{attrs:{id:"loader"}},[e("Spinner")],1):a._e()])}),[],!1,null,"12c5b85a",null);t.default=m.exports},c9pc:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".table-tools[data-v-12c5b85a] {\n  background: white;\n  display: flex;\n  justify-content: space-between;\n  padding: 15px 0 10px;\n  position: -webkit-sticky;\n  position: sticky;\n  top: 40px;\n  z-index: 9;\n}\n.table .cell-item[data-v-12c5b85a] {\n  font-size: 0.9375em;\n  white-space: nowrap;\n}\n@media only screen and (max-width: 690px) {\n.table-tools[data-v-12c5b85a] {\n    padding: 0 0 5px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.table-tools[data-v-12c5b85a] {\n    background: #141414;\n}\n}\n",""])},eE9K:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".cell-image-thumbnail[data-v-2cd07f63] {\n  display: flex;\n  align-items: center;\n  cursor: pointer;\n}\n.cell-image-thumbnail .image[data-v-2cd07f63] {\n  margin-right: 20px;\n  line-height: 0;\n  position: relative;\n}\n.cell-image-thumbnail .image img[data-v-2cd07f63] {\n  line-height: 0;\n  width: 48px;\n  height: 48px;\n  border-radius: 8px;\n  z-index: 1;\n  position: relative;\n}\n.cell-image-thumbnail .image img.blurred[data-v-2cd07f63] {\n  position: absolute;\n  left: 0;\n  top: 2px;\n  z-index: 0;\n  -webkit-filter: blur(8px);\n          filter: blur(8px);\n  opacity: 0.5;\n}\n.cell-image-thumbnail .image.small img[data-v-2cd07f63] {\n  width: 32px;\n  height: 32px;\n}\n.cell-image-thumbnail .info .name[data-v-2cd07f63], .cell-image-thumbnail .info .description[data-v-2cd07f63] {\n  max-width: 150px;\n  white-space: nowrap;\n  overflow: hidden;\n  text-overflow: ellipsis;\n  display: block;\n}\n.cell-image-thumbnail .info .name[data-v-2cd07f63] {\n  font-size: 0.9375em;\n  line-height: 1;\n  color: #1B2539;\n}\n.cell-image-thumbnail .info .description[data-v-2cd07f63] {\n  color: rgba(27, 37, 57, 0.7);\n  font-size: 0.75em;\n}\n@media (prefers-color-scheme: dark) {\n.cell-image-thumbnail .image img.blurred[data-v-2cd07f63] {\n    display: none;\n}\n.cell-image-thumbnail .info .name[data-v-2cd07f63] {\n    color: #bec6cf;\n}\n.cell-image-thumbnail .info .description[data-v-2cd07f63] {\n    color: #7d858c;\n}\n}\n",""])},fPaL:function(a,t,e){var n=e("sEJ9");"string"==typeof n&&(n=[[a.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(a.exports=n.locals)},kPoH:function(a,t,e){"use strict";var n={name:"ColorLabel",props:["color"]},i=(e("m6y/"),e("KHd+")),s=Object(i.a)(n,(function(){var a=this.$createElement;return(this._self._c||a)("b",{staticClass:"color-label",class:this.color},[this._t("default")],2)}),[],!1,null,"5c508dbf",null);t.a=s.exports},"m6y/":function(a,t,e){"use strict";var n=e("0rhn");e.n(n).a},"nr4+":function(a,t,e){var n=e("3eeM");"string"==typeof n&&(n=[[a.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(a.exports=n.locals)},omEc:function(a,t,e){"use strict";var n=e("+UVI");e.n(n).a},pAxR:function(a,t,e){"use strict";var n=e("IMud");e.n(n).a},sEJ9:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".preview-list-icon rect, .preview-list-icon line {\n  color: inherit;\n}\n",""])},"t5U/":function(a,t,e){"use strict";var n=e("CjXH"),i={name:"MobileActionButton",props:["icon"],components:{SortingAndPreviewIcon:e("Fqzo").a,CheckSquareIcon:n.e,DollarSignIcon:n.p,CreditCardIcon:n.n,FolderPlusIcon:n.y,UserPlusIcon:n.fb,XSquareIcon:n.jb,CheckIcon:n.d,TrashIcon:n.bb,PlusIcon:n.Q,ListIcon:n.H,GridIcon:n.A}},s=(e("omEc"),e("KHd+")),o=Object(s.a)(i,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("button",{staticClass:"mobile-action-button"},[e("div",{staticClass:"flex"},["credit-card"===a.icon?e("credit-card-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):a._e(),a._v(" "),"folder-plus"===a.icon?e("folder-plus-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):a._e(),a._v(" "),"th-list"===a.icon?e("list-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):a._e(),a._v(" "),"trash"===a.icon?e("trash-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):a._e(),a._v(" "),"th"===a.icon?e("grid-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):a._e(),a._v(" "),"user-plus"===a.icon?e("user-plus-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):a._e(),a._v(" "),"plus"===a.icon?e("plus-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):a._e(),a._v(" "),"check-square"===a.icon?e("check-square-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):a._e(),a._v(" "),"x-square"===a.icon?e("x-square-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):a._e(),a._v(" "),"check"===a.icon?e("check-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):a._e(),a._v(" "),"dollar-sign"===a.icon?e("dollar-sign-icon",{staticClass:"icon dark-text-theme",attrs:{size:"15"}}):a._e(),a._v(" "),"preview-sorting"===a.icon?e("sorting-and-preview-icon",{staticClass:"icon dark-text-theme preview-sorting",attrs:{size:"15"}}):a._e(),a._v(" "),e("span",{staticClass:"label"},[a._t("default")],2)],1)])}),[],!1,null,"91a6ffa6",null);t.a=o.exports},vAU0:function(a,t,e){var n=e("c9pc");"string"==typeof n&&(n=[[a.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,i);n.locals&&(a.exports=n.locals)},vFyo:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".text-label[data-v-6d799cf2] {\n  font-size: 0.75em;\n  color: #AFAFAF;\n  font-weight: 700;\n  display: block;\n  margin-bottom: 20px;\n}\n@media (prefers-color-scheme: dark) {\n.text-label[data-v-6d799cf2] {\n    color: #00BC7E;\n}\n}\n",""])}}]);