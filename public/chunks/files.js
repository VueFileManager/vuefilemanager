(window.webpackJsonp=window.webpackJsonp||[]).push([[19],{"1DBy":function(e,t,i){(e.exports=i("I1BE")(!1)).push([e.i,".empty-note.navigator[data-v-30dfb6be]{padding:5px 25px 10px}.empty-note.favourites[data-v-30dfb6be]{padding:5px 23px 10px}.navigator[data-v-30dfb6be]{width:100%;overflow-x:auto}@media only screen and (max-width:1024px){.empty-note.navigator[data-v-30dfb6be]{padding:5px 20px 10px}.empty-note.favourites[data-v-30dfb6be]{padding:5px 18px 10px}}.folder-item-move[data-v-30dfb6be]{transition:transform 300s ease}.folder-item-enter-active[data-v-30dfb6be]{transition:all .3s ease}.folder-item-leave-active[data-v-30dfb6be]{transition:all .3s}.folder-item-enter[data-v-30dfb6be],.folder-item-leave-to[data-v-30dfb6be]{opacity:0;transform:translateX(30px)}.folder-item-leave-active[data-v-30dfb6be]{position:absolute}",""])},"2wZp":function(e,t,i){"use strict";var r=i("Mlra");i.n(r).a},"6Rdq":function(e,t,i){"use strict";var r=i("s5FU"),a=i("yMep"),n=i("c4kp"),o=i("2QtR"),s=i("L2JU"),d=i("xCqy");function l(e,t){var i=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),i.push.apply(i,r)}return i}function c(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}var f={name:"FilesView",components:{SortingAndPreview:r.a,DesktopToolbar:a.a,FileBrowser:n.a,ContextMenu:o.a},computed:function(e){for(var t=1;t<arguments.length;t++){var i=null!=arguments[t]?arguments[t]:{};t%2?l(Object(i),!0).forEach((function(t){c(e,t,i[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(i)):l(Object(i)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(i,t))}))}return e}({},Object(s.b)(["config"])),methods:{fileViewClick:function(){d.a.$emit("contextMenu:hide")},contextMenu:function(e,t){d.a.$emit("contextMenu:show",e,t)}}},u=(i("2wZp"),i("KHd+")),p=Object(u.a)(f,(function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{attrs:{id:"files-view"},on:{click:e.fileViewClick,"!contextmenu":function(t){return t.preventDefault(),e.contextMenu(t,void 0)}}},[i("ContextMenu"),e._v(" "),i("SortingAndPreview"),e._v(" "),i("DesktopToolbar"),e._v(" "),i("FileBrowser")],1)}),[],!1,null,null,null);t.a=p.exports},"8KKK":function(e,t,i){"use strict";i.r(t);var r=i("Nv84"),a=i("CjXH"),n=i("L2JU");function o(e,t){var i=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),i.push.apply(i,r)}return i}function s(e){for(var t=1;t<arguments.length;t++){var i=null!=arguments[t]?arguments[t]:{};t%2?o(Object(i),!0).forEach((function(t){d(e,t,i[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(i)):o(Object(i)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(i,t))}))}return e}function d(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}var l={name:"UpgradeSidebarBanner",components:{HardDriveIcon:a.y,ButtonBase:r.a},computed:s(s({},Object(n.b)(["config"])),{},{storage:function(){return this.$store.getters.user.relationships.storage.data.attributes}})},c=(i("Mr/W"),i("KHd+")),f=Object(c.a)(l,(function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"upgrade-banner"},[i("div",{staticClass:"header-title"},[i("hard-drive-icon",{staticClass:"icon",attrs:{size:"19"}}),e._v(" "),i("span",{staticClass:"title"},[e._v(e._s(e.storage.used)+"% From "+e._s(e.storage.capacity_formatted))])],1),e._v(" "),i("div",{staticClass:"content"},[e.storage.used>95?i("p",{staticClass:"reach-capacity"},[e._v(e._s(e.$t("upgrade_banner.title")))]):i("p",{staticClass:"reach-capacity"},[e._v(e._s(e.$t("upgrade_banner.description")))])]),e._v(" "),e.config.app_payments_active?i("div",{staticClass:"footer"},[i("router-link",{staticClass:"button",attrs:{to:{name:"UpgradePlan"}}},[e._v("\n            "+e._s(e.$t("upgrade_banner.button"))+"\n        ")])],1):e._e()])}),[],!1,null,"086836f8",null).exports,u=i("FdzE"),p=i("N7DK"),v=i("6Rdq"),b=i("LtV2"),g=i("hXay"),h=i("xCqy");function m(e,t){var i=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),i.push.apply(i,r)}return i}function y(e){for(var t=1;t<arguments.length;t++){var i=null!=arguments[t]?arguments[t]:{};t%2?m(Object(i),!0).forEach((function(t){w(e,t,i[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(i)):m(Object(i)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(i,t))}))}return e}function w(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}var _={name:"FilesView",components:{UpgradeSidebarBanner:f,TreeMenuNavigator:u.a,ContentFileView:v.a,MultiSelected:p.a,ContentSidebar:b.a,UploadCloudIcon:a.W,ContentGroup:g.a,FolderIcon:a.v,Trash2Icon:a.T,HomeIcon:a.z,XIcon:a.bb},computed:y(y({},Object(n.b)(["user","homeDirectory","currentFolder","config","fileInfoDetail"])),{},{favourites:function(){return this.user.relationships.favourites.data.attributes.folders},tree:function(){return this.user.relationships.tree.data.attributes.folders},storage:function(){return this.$store.getters.user.relationships.storage.data.attributes}}),data:function(){return{area:!1,draggedItem:void 0}},methods:{getTrash:function(){this.$store.dispatch("getTrash")},getLatest:function(){this.$store.dispatch("getLatest")},goHome:function(){this.$store.dispatch("getFolder",[{folder:this.homeDirectory,back:!1,init:!0}])},openFolder:function(e){this.$store.dispatch("getFolder",[{folder:e,back:!1,init:!1}])},dragEnter:function(){this.draggedItem&&"folder"!==this.draggedItem.type||this.fileInfoDetail.length>0&&this.fileInfoDetail.find((function(e){return"folder"!==e.type}))||(this.area=!0)},dragLeave:function(){this.area=!1},dragFinish:function(){var e=this;this.area=!1,h.a.$emit("drop"),this.draggedItem&&"folder"!==this.draggedItem.type||this.favourites.find((function(t){return t.unique_id==e.draggedItem.unique_id}))||this.fileInfoDetail.length>0&&this.fileInfoDetail.find((function(e){return"folder"!==e.type}))||(this.fileInfoDetail.includes(this.draggedItem)||this.$store.dispatch("addToFavourites",this.draggedItem),this.fileInfoDetail.includes(this.draggedItem)&&this.$store.dispatch("addToFavourites",null))},removeFavourite:function(e){this.$store.dispatch("removeFromFavourites",e)}},created:function(){var e=this;this.goHome(),h.a.$on("dragstart",(function(t){e.draggedItem=t,e.dragInProgress=!0})),h.a.$on("drop",(function(){e.dragInProgress=!1}))},beforeRouteLeave:function(e,t,i){"SignIn"===e.name?window.confirm(this.$t("alerts.leave_to_sign_in"))?i():i(!1):i()}},O=(i("YQvs"),Object(c.a)(_,(function(){var e=this,t=e.$createElement,i=e._self._c||t;return e.user?i("section",{attrs:{id:"viewport"}},[i("ContentSidebar",[e.config.storageLimit&&e.storage.used>95?i("ContentGroup",[i("UpgradeSidebarBanner")],1):e._e(),e._v(" "),i("ContentGroup",{attrs:{title:e.$t("sidebar.locations_title")}},[i("div",{staticClass:"menu-list-wrapper vertical"},[i("a",{staticClass:"menu-list-item link",class:{"is-active":e.$isThisLocation(["base"])},on:{click:e.goHome}},[i("div",{staticClass:"icon"},[i("home-icon",{attrs:{size:"17"}})],1),e._v(" "),i("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("sidebar.home"))+"\n                    ")])]),e._v(" "),i("a",{staticClass:"menu-list-item link",class:{"is-active":e.$isThisLocation(["latest"])},on:{click:e.getLatest}},[i("div",{staticClass:"icon"},[i("upload-cloud-icon",{attrs:{size:"17"}})],1),e._v(" "),i("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("sidebar.latest"))+"\n                    ")])]),e._v(" "),i("a",{staticClass:"menu-list-item link trash",class:{"is-active-trash":e.$isThisLocation(["trash","trash-root"])},on:{click:e.getTrash}},[i("div",{staticClass:"icon"},[i("trash2-icon",{attrs:{size:"17"}})],1),e._v(" "),i("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("locations.trash"))+"\n                    ")])])])]),e._v(" "),i("ContentGroup",{staticClass:"navigator",attrs:{title:e.$t("sidebar.navigator_title"),slug:"navigator","can-collapse":!0}},[0==e.tree.length?i("span",{staticClass:"empty-note navigator"},[e._v("\n                "+e._s(e.$t("sidebar.folders_empty"))+"\n            ")]):e._e(),e._v(" "),e._l(e.tree,(function(e){return i("TreeMenuNavigator",{key:e.unique_id,staticClass:"folder-tree",attrs:{depth:0,nodes:e}})}))],2),e._v(" "),i("ContentGroup",{attrs:{title:e.$t("sidebar.favourites"),slug:"favourites","can-collapse":!0}},[i("div",{staticClass:"menu-list-wrapper vertical favourites",class:{"is-dragenter":e.area},on:{dragover:function(t){return t.preventDefault(),e.dragEnter(t)},dragleave:e.dragLeave,drop:function(t){return e.dragFinish(t)}}},[i("transition-group",{staticClass:"menu-list",attrs:{tag:"div",name:"folder-item"}},[0==e.favourites.length?i("span",{key:0,staticClass:"empty-note favourites"},[e._v("\n                        "+e._s(e.$t("sidebar.favourites_empty"))+"\n                    ")]):e._e(),e._v(" "),e._l(e.favourites,(function(t){return i("a",{key:t.unique_id,staticClass:"menu-list-item",class:{"is-current":t&&e.currentFolder&&e.currentFolder.unique_id===t.unique_id},on:{click:function(i){return i.stopPropagation(),e.openFolder(t)}}},[i("div",[i("folder-icon",{staticClass:"folder-icon",attrs:{size:"17"}}),e._v(" "),i("span",{staticClass:"label"},[e._v(e._s(t.name))])],1),e._v(" "),i("x-icon",{staticClass:"delete-icon",attrs:{size:"17"},on:{click:function(i){return i.stopPropagation(),e.removeFavourite(t)}}})],1)}))],2)],1)])],1),e._v(" "),i("ContentFileView")],1):e._e()}),[],!1,null,"30dfb6be",null));t.default=O.exports},FdzE:function(e,t,i){"use strict";var r=i("CjXH"),a=i("L2JU"),n=i("xCqy");function o(e,t){var i=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),i.push.apply(i,r)}return i}function s(e){for(var t=1;t<arguments.length;t++){var i=null!=arguments[t]?arguments[t]:{};t%2?o(Object(i),!0).forEach((function(t){d(e,t,i[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(i)):o(Object(i)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(i,t))}))}return e}function d(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}var l={name:"TreeMenuNavigator",props:["nodes","depth","disabled"],components:{TreeMenuNavigator:u,ChevronRightIcon:r.h,FolderIcon:r.v},computed:s(s({},Object(a.b)(["fileInfoDetail"])),{},{disabledFolder:function(){var e=this,t=!1;return this.draggedItem.length>0?this.draggedItem.forEach((function(i){"folder"===i.type&&e.nodes.unique_id===i.parent_id&&(t=!0),e.nodes.unique_id===i.unique_id&&"folder"===i.type&&(t=!0,e.disableChildren=!0),e.disabled&&(e.disableChildren=!0)})):(t=!1,this.disableChildren=!1),t},indent:function(){var e=window.innerWidth<=1024?17:22;return{paddingLeft:(0==this.depth?e:e+20*this.depth)+"px"}}}),data:function(){return{isVisible:!1,isSelected:!1,area:!1,draggedItem:[],disableChildren:!1}},methods:{dragFinish:function(){this.fileInfoDetail.includes(this.draggedItem[0])||this.$store.dispatch("moveItem",{to_item:this.nodes,noSelectedItem:this.draggedItem[0]}),this.fileInfoDetail.includes(this.draggedItem[0])&&this.$store.dispatch("moveItem",{to_item:this.nodes,noSelectedItem:null}),this.draggedItem=[],this.area=!1,n.a.$emit("drop")},dragEnter:function(){this.area=!0},dragLeave:function(){this.area=!1},getFolder:function(){n.a.$emit("show-folder",this.nodes),this.$isThisLocation("public")?this.$store.dispatch("browseShared",[{folder:this.nodes,back:!1,init:!1}]):this.$store.dispatch("getFolder",[{folder:this.nodes,back:!1,init:!1}])},showTree:function(){this.isVisible=!this.isVisible}},created:function(){var e=this;n.a.$on("drop",(function(){e.draggedItem=[]})),n.a.$on("dragstart",(function(t){e.fileInfoDetail.includes(t)||(e.draggedItem=[t]),e.fileInfoDetail.includes(t)&&(e.draggedItem=e.fileInfoDetail)})),n.a.$on("show-folder",(function(t){e.isSelected=!1,e.nodes.unique_id==t.unique_id&&(e.isSelected=!0)}))}},c=(i("XrVr"),i("KHd+")),f=Object(c.a)(l,(function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("transition",{attrs:{name:"folder"}},[i("div",{staticClass:"folder-item-wrapper"},[i("div",{staticClass:"folder-item",class:{"is-selected":e.isSelected,"is-dragenter":e.area,"is-inactive":e.disabledFolder||e.disabled&&e.draggedItem.length>0},style:e.indent,on:{click:e.getFolder,dragover:function(t){return t.preventDefault(),e.dragEnter(t)},dragleave:e.dragLeave,drop:function(t){return e.dragFinish()}}},[i("chevron-right-icon",{staticClass:"icon-arrow",class:{"is-opened":e.isVisible,"is-visible":0!==e.nodes.folders.length},attrs:{size:"17"},on:{click:function(t){return t.stopPropagation(),e.showTree(t)}}}),e._v(" "),i("folder-icon",{staticClass:"icon",attrs:{size:"17"}}),e._v(" "),i("span",{staticClass:"label"},[e._v(e._s(e.nodes.name))])],1),e._v(" "),e._l(e.nodes.folders,(function(t){return e.isVisible?i("TreeMenuNavigator",{key:t.unique_id,attrs:{disabled:e.disableChildren,depth:e.depth+1,nodes:t}}):e._e()}))],2)])}),[],!1,null,"2e948b10",null),u=t.a=f.exports},"MS+B":function(e,t,i){(e.exports=i("I1BE")(!1)).push([e.i,".is-inactive[data-v-2e948b10]{opacity:.5;pointer-events:none}.is-dragenter[data-v-2e948b10]{border:2px dashed #00bc7e!important;border-radius:8px}.folder-item[data-v-2e948b10]{display:block;padding:8px 0;transition:all .15s ease;cursor:pointer;position:relative;white-space:nowrap;width:100%;border:2px dashed transparent}.folder-item .icon[data-v-2e948b10]{line-height:0;width:15px;margin-right:9px;vertical-align:middle;margin-top:-1px}.folder-item .icon circle[data-v-2e948b10],.folder-item .icon line[data-v-2e948b10],.folder-item .icon path[data-v-2e948b10],.folder-item .icon polyline[data-v-2e948b10],.folder-item .icon rect[data-v-2e948b10]{transition:all .15s ease}.folder-item .icon-arrow[data-v-2e948b10]{transition:all .3s ease;margin-right:4px;vertical-align:middle;opacity:0}.folder-item .icon-arrow.is-visible[data-v-2e948b10]{opacity:1}.folder-item .icon-arrow.is-opened[data-v-2e948b10]{transform:rotate(90deg)}.folder-item .label[data-v-2e948b10]{transition:all .15s ease;font-size:.8125em;font-weight:700;vertical-align:middle;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:inline-block;color:#1b2539;max-width:130px}.folder-item.is-selected .icon circle[data-v-2e948b10],.folder-item.is-selected .icon line[data-v-2e948b10],.folder-item.is-selected .icon path[data-v-2e948b10],.folder-item.is-selected .icon polyline[data-v-2e948b10],.folder-item.is-selected .icon rect[data-v-2e948b10],.folder-item:hover .icon circle[data-v-2e948b10],.folder-item:hover .icon line[data-v-2e948b10],.folder-item:hover .icon path[data-v-2e948b10],.folder-item:hover .icon polyline[data-v-2e948b10],.folder-item:hover .icon rect[data-v-2e948b10]{stroke:#00bc7e}.folder-item.is-selected .label[data-v-2e948b10],.folder-item:hover .label[data-v-2e948b10]{color:#00bc7e}@media only screen and (max-width:1024px){.folder-item[data-v-2e948b10]{padding:8px 0}}@media (prefers-color-scheme:dark){.folder-item .label[data-v-2e948b10]{color:#bec6cf}.folder-item.is-selected[data-v-2e948b10],.folder-item[data-v-2e948b10]:hover,.is-selected[data-v-2e948b10]{background:rgba(0,188,126,.1)}}@media (prefers-color-scheme:dark) and (max-width:690px){.folder-item.is-selected[data-v-2e948b10],.folder-item[data-v-2e948b10]:hover{background:rgba(0,188,126,.1)}}",""])},Mlra:function(e,t,i){var r=i("Q8SN");"string"==typeof r&&(r=[[e.i,r,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};i("aET+")(r,a);r.locals&&(e.exports=r.locals)},"Mr/W":function(e,t,i){"use strict";var r=i("ceKr");i.n(r).a},Q8SN:function(e,t,i){(e.exports=i("I1BE")(!1)).push([e.i,"#files-view{font-family:Nunito,sans-serif;font-size:16px;width:100%;height:100%;position:relative;min-width:320px;overflow-x:hidden;padding-left:15px;padding-right:15px;overflow-y:hidden}@media only screen and (max-width:690px){#files-view{padding-left:0;padding-right:0}}",""])},UtFG:function(e,t,i){var r=i("MS+B");"string"==typeof r&&(r=[[e.i,r,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};i("aET+")(r,a);r.locals&&(e.exports=r.locals)},XrVr:function(e,t,i){"use strict";var r=i("UtFG");i.n(r).a},YQvs:function(e,t,i){"use strict";var r=i("yKIu");i.n(r).a},ceKr:function(e,t,i){var r=i("jcuL");"string"==typeof r&&(r=[[e.i,r,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};i("aET+")(r,a);r.locals&&(e.exports=r.locals)},jcuL:function(e,t,i){(e.exports=i("I1BE")(!1)).push([e.i,".upgrade-banner[data-v-086836f8]{background:rgba(253,57,122,.1);padding:10px;border-radius:6px;margin:0 16px}.header-title[data-v-086836f8]{margin-bottom:12px;display:flex;align-items:center}.header-title .icon[data-v-086836f8]{margin-right:10px}.header-title .icon line[data-v-086836f8],.header-title .icon path[data-v-086836f8]{stroke:#fd397a}.header-title .title[data-v-086836f8]{font-size:.8125em;font-weight:800;color:#fd397a}.content[data-v-086836f8]{margin-bottom:12px}.content p[data-v-086836f8]{color:#fd397a}.button[data-v-086836f8],.content p[data-v-086836f8]{font-size:.75em;font-weight:700}.button[data-v-086836f8]{background:#fd397a;border-radius:50px;padding:6px 0;width:100%;color:#fff;text-align:center;display:block;box-shadow:0 4px 10px rgba(253,57,122,.35)}",""])},yKIu:function(e,t,i){var r=i("1DBy");"string"==typeof r&&(r=[[e.i,r,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};i("aET+")(r,a);r.locals&&(e.exports=r.locals)}}]);