(window.webpackJsonp=window.webpackJsonp||[]).push([[19],{"2wZp":function(e,t,a){"use strict";var i=a("Mlra");a.n(i).a},"6Rdq":function(e,t,a){"use strict";var i=a("yMep"),r=a("c4kp"),n=a("2QtR"),o=a("L2JU"),s=a("xCqy");function d(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);t&&(i=i.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,i)}return a}function l(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}var c={name:"FilesView",components:{DesktopToolbar:i.a,FileBrowser:r.a,ContextMenu:n.a},computed:function(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?d(Object(a),!0).forEach((function(t){l(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):d(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}({},Object(o.b)(["config"])),methods:{fileViewClick:function(){s.a.$emit("contextMenu:hide")},contextMenu:function(e,t){s.a.$emit("contextMenu:show",e,t)}}},f=(a("2wZp"),a("KHd+")),u=Object(f.a)(c,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{attrs:{id:"files-view"},on:{click:e.fileViewClick,"!contextmenu":function(t){return t.preventDefault(),e.contextMenu(t,void 0)}}},[a("ContextMenu"),e._v(" "),a("DesktopToolbar"),e._v(" "),a("FileBrowser")],1)}),[],!1,null,null,null);t.a=u.exports},"8KKK":function(e,t,a){"use strict";a.r(t);var i=a("Nv84"),r=a("CjXH"),n=a("L2JU");function o(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);t&&(i=i.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,i)}return a}function s(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?o(Object(a),!0).forEach((function(t){d(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):o(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}function d(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}var l={name:"UpgradeSidebarBanner",components:{HardDriveIcon:r.w,ButtonBase:i.a},computed:s(s({},Object(n.b)(["config"])),{},{storage:function(){return this.$store.getters.user.relationships.storage.data.attributes}})},c=(a("Mr/W"),a("KHd+")),f=Object(c.a)(l,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"upgrade-banner"},[a("div",{staticClass:"header-title"},[a("hard-drive-icon",{staticClass:"icon",attrs:{size:"19"}}),e._v(" "),a("span",{staticClass:"title"},[e._v(e._s(e.storage.used)+"% From "+e._s(e.storage.capacity_formatted))])],1),e._v(" "),a("div",{staticClass:"content"},[e.storage.used>95?a("p",{staticClass:"reach-capacity"},[e._v(e._s(e.$t("upgrade_banner.title")))]):a("p",{staticClass:"reach-capacity"},[e._v(e._s(e.$t("upgrade_banner.description")))])]),e._v(" "),e.config.app_payments_active?a("div",{staticClass:"footer"},[a("router-link",{staticClass:"button",attrs:{to:{name:"UpgradePlan"}}},[e._v("\n            "+e._s(e.$t("upgrade_banner.button"))+"\n        ")])],1):e._e()])}),[],!1,null,"086836f8",null).exports,u=a("FdzE"),p=a("N7DK"),v=a("6Rdq"),g=a("LtV2"),h=a("hXay"),m=a("xCqy");function b(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);t&&(i=i.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,i)}return a}function y(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?b(Object(a),!0).forEach((function(t){w(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):b(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}function w(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}var _={name:"FilesView",components:{UpgradeSidebarBanner:f,TreeMenuNavigator:u.a,ContentFileView:v.a,MultiSelected:p.a,ContentSidebar:g.a,UploadCloudIcon:r.U,ContentGroup:h.a,FolderIcon:r.t,Trash2Icon:r.R,HomeIcon:r.x,XIcon:r.Z},computed:y(y({},Object(n.b)(["user","homeDirectory","currentFolder","config","fileInfoDetail"])),{},{favourites:function(){return this.user.relationships.favourites.data.attributes.folders},tree:function(){return this.user.relationships.tree.data.attributes.folders},storage:function(){return this.$store.getters.user.relationships.storage.data.attributes}}),data:function(){return{area:!1,draggedItem:void 0}},methods:{getTrash:function(){this.$store.dispatch("getTrash")},getLatest:function(){this.$store.dispatch("getLatest")},goHome:function(){this.$store.dispatch("getFolder",[{folder:this.homeDirectory,back:!1,init:!0}])},openFolder:function(e){this.$store.dispatch("getFolder",[{folder:e,back:!1,init:!1}])},dragEnter:function(){this.draggedItem&&"folder"!==this.draggedItem.type||this.fileInfoDetail.length>0&&this.fileInfoDetail.find((function(e){return"folder"!==e.type}))||(this.area=!0)},dragLeave:function(){this.area=!1},dragFinish:function(){var e=this;this.area=!1,m.a.$emit("drop"),this.draggedItem&&"folder"!==this.draggedItem.type||this.favourites.find((function(t){return t.unique_id==e.draggedItem.unique_id}))||this.fileInfoDetail.length>0&&this.fileInfoDetail.find((function(e){return"folder"!==e.type}))||(this.fileInfoDetail.includes(this.draggedItem)||this.$store.dispatch("addToFavourites",this.draggedItem),this.fileInfoDetail.includes(this.draggedItem)&&this.$store.dispatch("addToFavourites",null))},removeFavourite:function(e){this.$store.dispatch("removeFromFavourites",e)}},created:function(){var e=this;this.goHome(),m.a.$on("dragstart",(function(t){e.draggedItem=t,e.dragInProgress=!0})),m.a.$on("drop",(function(){e.dragInProgress=!1}))},beforeRouteLeave:function(e,t,a){"SignIn"===e.name?window.confirm("Do you really want to leave?")?a():a(!1):a()}},O=(a("LmFl"),Object(c.a)(_,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return e.user?a("section",{attrs:{id:"viewport"}},[a("ContentSidebar",[e.config.storageLimit&&e.storage.used>95?a("ContentGroup",[a("UpgradeSidebarBanner")],1):e._e(),e._v(" "),a("ContentGroup",{attrs:{title:e.$t("sidebar.locations_title")}},[a("div",{staticClass:"menu-list-wrapper vertical"},[a("a",{staticClass:"menu-list-item link",class:{"is-active":e.$isThisLocation(["base"])},on:{click:e.goHome}},[a("div",{staticClass:"icon"},[a("home-icon",{attrs:{size:"17"}})],1),e._v(" "),a("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("sidebar.home"))+"\n                    ")])]),e._v(" "),a("a",{staticClass:"menu-list-item link",class:{"is-active":e.$isThisLocation(["latest"])},on:{click:e.getLatest}},[a("div",{staticClass:"icon"},[a("upload-cloud-icon",{attrs:{size:"17"}})],1),e._v(" "),a("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("sidebar.latest"))+"\n                    ")])]),e._v(" "),a("a",{staticClass:"menu-list-item link trash",class:{"is-active-trash":e.$isThisLocation(["trash","trash-root"])},on:{click:e.getTrash}},[a("div",{staticClass:"icon"},[a("trash2-icon",{attrs:{size:"17"}})],1),e._v(" "),a("div",{staticClass:"label"},[e._v("\n                        "+e._s(e.$t("locations.trash"))+"\n                    ")])])])]),e._v(" "),a("ContentGroup",{staticClass:"navigator",attrs:{title:e.$t("sidebar.navigator_title"),slug:"navigator","can-collapse":!0}},[0==e.tree.length?a("span",{staticClass:"empty-note navigator"},[e._v("\n                "+e._s(e.$t("sidebar.folders_empty"))+"\n            ")]):e._e(),e._v(" "),e._l(e.tree,(function(e){return a("TreeMenuNavigator",{key:e.unique_id,staticClass:"folder-tree",attrs:{depth:0,nodes:e}})}))],2),e._v(" "),a("ContentGroup",{attrs:{title:e.$t("sidebar.favourites"),slug:"favourites","can-collapse":!0}},[a("div",{staticClass:"menu-list-wrapper vertical favourites",class:{"is-dragenter":e.area},on:{dragover:function(t){return t.preventDefault(),e.dragEnter(t)},dragleave:e.dragLeave,drop:function(t){return e.dragFinish(t)}}},[a("transition-group",{staticClass:"menu-list",attrs:{tag:"div",name:"folder-item"}},[0==e.favourites.length?a("span",{key:0,staticClass:"empty-note favourites"},[e._v("\n                        "+e._s(e.$t("sidebar.favourites_empty"))+"\n                    ")]):e._e(),e._v(" "),e._l(e.favourites,(function(t){return a("a",{key:t.unique_id,staticClass:"menu-list-item",class:{"is-current":t&&e.currentFolder&&e.currentFolder.unique_id===t.unique_id},on:{click:function(a){return a.stopPropagation(),e.openFolder(t)}}},[a("div",[a("folder-icon",{staticClass:"folder-icon",attrs:{size:"17"}}),e._v(" "),a("span",{staticClass:"label"},[e._v(e._s(t.name))])],1),e._v(" "),a("x-icon",{staticClass:"delete-icon",attrs:{size:"17"},on:{click:function(a){return a.stopPropagation(),e.removeFavourite(t)}}})],1)}))],2)],1)])],1),e._v(" "),a("ContentFileView")],1):e._e()}),[],!1,null,"6bc2a868",null));t.default=O.exports},BpEw:function(e,t,a){var i=a("kKlU");"string"==typeof i&&(i=[[e.i,i,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,r);i.locals&&(e.exports=i.locals)},FdzE:function(e,t,a){"use strict";var i=a("CjXH"),r=a("L2JU"),n=a("xCqy");function o(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);t&&(i=i.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,i)}return a}function s(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?o(Object(a),!0).forEach((function(t){d(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):o(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}function d(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}var l={name:"TreeMenuNavigator",props:["nodes","depth","disabled"],components:{TreeMenuNavigator:u,ChevronRightIcon:i.f,FolderIcon:i.t},computed:s(s({},Object(r.b)(["fileInfoDetail"])),{},{disabledFolder:function(){var e=this,t=!1;return this.draggedItem.length>0?this.draggedItem.forEach((function(a){"folder"===a.type&&e.nodes.unique_id===a.parent_id&&(t=!0),e.nodes.unique_id===a.unique_id&&"folder"===a.type&&(t=!0,e.disableChildren=!0),e.disabled&&(e.disableChildren=!0)})):(t=!1,this.disableChildren=!1),t},indent:function(){var e=window.innerWidth<=1024?17:22;return{paddingLeft:(0==this.depth?e:e+20*this.depth)+"px"}}}),data:function(){return{isVisible:!1,isSelected:!1,area:!1,draggedItem:[],disableChildren:!1}},methods:{dragFinish:function(){this.fileInfoDetail.includes(this.draggedItem[0])||(this.$store.dispatch("moveItem",{to_item:this.nodes,noSelectedItem:this.draggedItem[0]}),this.draggedItem=[]),this.fileInfoDetail.includes(this.draggedItem[0])&&(this.$store.dispatch("moveItem",{to_item:this.nodes,noSelectedItem:null}),this.draggedItem=[]),this.area=!1,n.a.$emit("drop")},dragEnter:function(){this.area=!0},dragLeave:function(){this.area=!1},getFolder:function(){n.a.$emit("show-folder",this.nodes),this.$store.dispatch("getFolder",[{folder:this.nodes,back:!1,init:!1}])},showTree:function(){this.isVisible=!this.isVisible}},created:function(){var e=this;n.a.$on("drop",(function(){e.draggedItem=[]})),n.a.$on("dragstart",(function(t){e.fileInfoDetail.includes(t)||(e.draggedItem=[t]),e.fileInfoDetail.includes(t)&&(e.draggedItem=e.fileInfoDetail)})),n.a.$on("show-folder",(function(t){e.isSelected=!1,e.nodes.unique_id==t.unique_id&&(e.isSelected=!0)}))}},c=(a("SaJT"),a("KHd+")),f=Object(c.a)(l,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("transition",{attrs:{name:"folder"}},[a("div",{staticClass:"folder-item-wrapper"},[a("div",{staticClass:"folder-item",class:{"is-selected":e.isSelected,"is-dragenter":e.area,"is-inactive":e.disabledFolder||e.disabled&&e.draggedItem.length>0},style:e.indent,on:{click:e.getFolder,dragover:function(t){return t.preventDefault(),e.dragEnter(t)},dragleave:e.dragLeave,drop:function(t){return e.dragFinish()}}},[a("chevron-right-icon",{staticClass:"icon-arrow",class:{"is-opened":e.isVisible,"is-visible":0!==e.nodes.folders.length},attrs:{size:"17"},on:{click:function(t){return t.stopPropagation(),e.showTree(t)}}}),e._v(" "),a("folder-icon",{staticClass:"icon",attrs:{size:"17"}}),e._v(" "),a("span",{staticClass:"label"},[e._v(e._s(e.nodes.name))])],1),e._v(" "),e._l(e.nodes.folders,(function(t){return e.isVisible?a("TreeMenuNavigator",{key:t.unique_id,attrs:{disabled:e.disableChildren,depth:e.depth+1,nodes:t}}):e._e()}))],2)])}),[],!1,null,"739fae5a",null),u=t.a=f.exports},LmFl:function(e,t,a){"use strict";var i=a("BpEw");a.n(i).a},Mlra:function(e,t,a){var i=a("Q8SN");"string"==typeof i&&(i=[[e.i,i,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,r);i.locals&&(e.exports=i.locals)},"Mr/W":function(e,t,a){"use strict";var i=a("ceKr");a.n(i).a},PBAd:function(e,t,a){(e.exports=a("I1BE")(!1)).push([e.i,".is-inactive[data-v-739fae5a]{opacity:.5;pointer-events:none}.is-dragenter[data-v-739fae5a]{border:2px dashed #00bc7e;border-radius:8px}.folder-item[data-v-739fae5a]{display:block;padding:8px 0;transition:all .15s ease;cursor:pointer;position:relative;white-space:nowrap;width:100%}.folder-item .icon[data-v-739fae5a]{line-height:0;width:15px;margin-right:9px;vertical-align:middle;margin-top:-1px}.folder-item .icon circle[data-v-739fae5a],.folder-item .icon line[data-v-739fae5a],.folder-item .icon path[data-v-739fae5a],.folder-item .icon polyline[data-v-739fae5a],.folder-item .icon rect[data-v-739fae5a]{transition:all .15s ease}.folder-item .icon-arrow[data-v-739fae5a]{transition:all .3s ease;margin-right:4px;vertical-align:middle;opacity:0}.folder-item .icon-arrow.is-visible[data-v-739fae5a]{opacity:1}.folder-item .icon-arrow.is-opened[data-v-739fae5a]{transform:rotate(90deg)}.folder-item .label[data-v-739fae5a]{transition:all .15s ease;font-size:.8125em;font-weight:700;vertical-align:middle;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:inline-block;color:#1b2539;max-width:130px}.folder-item.is-selected .icon circle[data-v-739fae5a],.folder-item.is-selected .icon line[data-v-739fae5a],.folder-item.is-selected .icon path[data-v-739fae5a],.folder-item.is-selected .icon polyline[data-v-739fae5a],.folder-item.is-selected .icon rect[data-v-739fae5a],.folder-item:hover .icon circle[data-v-739fae5a],.folder-item:hover .icon line[data-v-739fae5a],.folder-item:hover .icon path[data-v-739fae5a],.folder-item:hover .icon polyline[data-v-739fae5a],.folder-item:hover .icon rect[data-v-739fae5a]{stroke:#00bc7e}.folder-item.is-selected .label[data-v-739fae5a],.folder-item:hover .label[data-v-739fae5a]{color:#00bc7e}@media only screen and (max-width:1024px){.folder-item[data-v-739fae5a]{padding:8px 0}}@media (prefers-color-scheme:dark){.folder-item .label[data-v-739fae5a]{color:#bec6cf}.folder-item.is-selected[data-v-739fae5a],.folder-item[data-v-739fae5a]:hover,.is-selected[data-v-739fae5a]{background:rgba(0,188,126,.1)}}@media (prefers-color-scheme:dark) and (max-width:690px){.folder-item.is-selected[data-v-739fae5a],.folder-item[data-v-739fae5a]:hover{background:rgba(0,188,126,.1)}}",""])},Q8SN:function(e,t,a){(e.exports=a("I1BE")(!1)).push([e.i,"#files-view{font-family:Nunito,sans-serif;font-size:16px;width:100%;height:100%;position:relative;min-width:320px;overflow-x:hidden;padding-left:15px;padding-right:15px;overflow-y:hidden}@media only screen and (max-width:690px){#files-view{padding-left:0;padding-right:0}}",""])},SaJT:function(e,t,a){"use strict";var i=a("wQQz");a.n(i).a},ceKr:function(e,t,a){var i=a("jcuL");"string"==typeof i&&(i=[[e.i,i,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,r);i.locals&&(e.exports=i.locals)},jcuL:function(e,t,a){(e.exports=a("I1BE")(!1)).push([e.i,".upgrade-banner[data-v-086836f8]{background:rgba(253,57,122,.1);padding:10px;border-radius:6px;margin:0 16px}.header-title[data-v-086836f8]{margin-bottom:12px;display:flex;align-items:center}.header-title .icon[data-v-086836f8]{margin-right:10px}.header-title .icon line[data-v-086836f8],.header-title .icon path[data-v-086836f8]{stroke:#fd397a}.header-title .title[data-v-086836f8]{font-size:.8125em;font-weight:800;color:#fd397a}.content[data-v-086836f8]{margin-bottom:12px}.content p[data-v-086836f8]{color:#fd397a}.button[data-v-086836f8],.content p[data-v-086836f8]{font-size:.75em;font-weight:700}.button[data-v-086836f8]{background:#fd397a;border-radius:50px;padding:6px 0;width:100%;color:#fff;text-align:center;display:block;box-shadow:0 4px 10px rgba(253,57,122,.35)}",""])},kKlU:function(e,t,a){(e.exports=a("I1BE")(!1)).push([e.i,".empty-note.navigator[data-v-6bc2a868]{padding:5px 25px 10px}.empty-note.favourites[data-v-6bc2a868]{padding:5px 23px 10px}.navigator[data-v-6bc2a868]{width:100%;overflow-x:auto}@media only screen and (max-width:1024px){.empty-note.navigator[data-v-6bc2a868]{padding:5px 20px 10px}.empty-note.favourites[data-v-6bc2a868]{padding:5px 18px 10px}}.folder-item-move[data-v-6bc2a868]{transition:transform 300s ease}.folder-item-enter-active[data-v-6bc2a868]{transition:all .3s ease}.folder-item-leave-active[data-v-6bc2a868]{transition:all .3s}.folder-item-enter[data-v-6bc2a868],.folder-item-leave-to[data-v-6bc2a868]{opacity:0;transform:translateX(30px)}.folder-item-leave-active[data-v-6bc2a868]{position:absolute}",""])},wQQz:function(e,t,a){var i=a("PBAd");"string"==typeof i&&(i=[[e.i,i,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,r);i.locals&&(e.exports=i.locals)}}]);