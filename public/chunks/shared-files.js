(window.webpackJsonp=window.webpackJsonp||[]).push([[55],{"2wZp":function(t,e,n){"use strict";var i=n("Mlra");n.n(i).a},"6Rdq":function(t,e,n){"use strict";var i=n("9Q3x"),r=n("yMep"),o=n("c4kp"),s=n("2QtR"),a=n("L2JU"),c=n("xCqy");function l(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(t);e&&(i=i.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,i)}return n}function p(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var u={name:"FilesView",components:{DesktopSortingAndPreview:i.a,DesktopToolbar:r.a,FileBrowser:o.a,ContextMenu:s.a},computed:function(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?l(Object(n),!0).forEach((function(e){p(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):l(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}({},Object(a.b)(["config"])),methods:{contextMenu:function(t,e){c.a.$emit("contextMenu:show",t,e)}}},d=(n("2wZp"),n("KHd+")),f=Object(d.a)(u,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{attrs:{id:"files-view"},on:{"!contextmenu":function(e){return e.preventDefault(),t.contextMenu(e,void 0)}}},[n("ContextMenu"),t._v(" "),n("DesktopSortingAndPreview"),t._v(" "),n("DesktopToolbar"),t._v(" "),n("FileBrowser")],1)}),[],!1,null,null,null);e.a=f.exports},CoTS:function(t,e,n){"use strict";n.r(e);var i=n("6Rdq"),r=n("LtV2"),o=n("hXay"),s=n("CjXH"),a={name:"FilesView",components:{ContentFileView:i.a,ContentSidebar:r.a,ContentGroup:o.a,LinkIcon:s.F,UsersIcon:s.fb},methods:{getShared:function(){this.$store.dispatch("getShared",[{back:!1,init:!1}])},getParticipantUploads:function(){this.$store.dispatch("getParticipantUploads")}},mounted:function(){this.getShared()}},c=n("KHd+"),l=Object(c.a)(a,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("section",{attrs:{id:"viewport"}},[n("ContentSidebar",[n("ContentGroup",{attrs:{title:t.$t("sidebar.locations_title")}},[n("div",{staticClass:"menu-list-wrapper vertical"},[n("li",{staticClass:"menu-list-item link",class:{"is-active":t.$isThisLocation(["shared"])},on:{click:function(e){return t.getShared()}}},[n("div",{staticClass:"icon text-theme"},[n("link-icon",{attrs:{size:"17"}})],1),t._v(" "),n("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("sidebar.my_shared"))+"\n                    ")])]),t._v(" "),n("li",{staticClass:"menu-list-item link",class:{"is-active":t.$isThisLocation(["participant_uploads"])},on:{click:function(e){return t.getParticipantUploads()}}},[n("div",{staticClass:"icon text-theme"},[n("users-icon",{attrs:{size:"17"}})],1),t._v(" "),n("div",{staticClass:"label text-theme"},[t._v("\n                        "+t._s(t.$t("sidebar.participant_uploads"))+"\n                    ")])])])])],1),t._v(" "),n("ContentFileView")],1)}),[],!1,null,"55043004",null);e.default=l.exports},Mlra:function(t,e,n){var i=n("Q8SN");"string"==typeof i&&(i=[[t.i,i,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(i,r);i.locals&&(t.exports=i.locals)},Q8SN:function(t,e,n){(t.exports=n("I1BE")(!1)).push([t.i,"#files-view {\n  font-family: 'Nunito', sans-serif;\n  font-size: 16px;\n  width: 100%;\n  height: 100%;\n  position: relative;\n  min-width: 320px;\n  overflow-x: hidden;\n  padding-left: 15px;\n  padding-right: 15px;\n  overflow-y: hidden;\n}\n@media only screen and (max-width: 690px) {\n#files-view {\n    padding-left: 0;\n    padding-right: 0;\n}\n}\n",""])}}]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvY29tcG9uZW50cy9PdGhlcnMvQ29udGVudEZpbGVWaWV3LnZ1ZT80ZWMwIiwid2VicGFjazovLy8uL3Jlc291cmNlcy9qcy9jb21wb25lbnRzL090aGVycy9Db250ZW50RmlsZVZpZXcudnVlP2ZkMjIiLCJ3ZWJwYWNrOi8vL3Jlc291cmNlcy9qcy9jb21wb25lbnRzL090aGVycy9Db250ZW50RmlsZVZpZXcudnVlIiwid2VicGFjazovLy8uL3Jlc291cmNlcy9qcy9jb21wb25lbnRzL090aGVycy9Db250ZW50RmlsZVZpZXcudnVlP2VjZWYiLCJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2pzL2NvbXBvbmVudHMvT3RoZXJzL0NvbnRlbnRGaWxlVmlldy52dWUiLCJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2pzL3ZpZXdzL0ZpbGVQYWdlcy9TaGFyZWRGaWxlcy52dWU/MjIyNCIsIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvdmlld3MvRmlsZVBhZ2VzL1NoYXJlZEZpbGVzLnZ1ZT8wYTdjIiwid2VicGFjazovLy9yZXNvdXJjZXMvanMvdmlld3MvRmlsZVBhZ2VzL1NoYXJlZEZpbGVzLnZ1ZSIsIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvdmlld3MvRmlsZVBhZ2VzL1NoYXJlZEZpbGVzLnZ1ZSIsIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvY29tcG9uZW50cy9PdGhlcnMvQ29udGVudEZpbGVWaWV3LnZ1ZT8xNzZlIiwid2VicGFjazovLy8uL3Jlc291cmNlcy9qcy9jb21wb25lbnRzL090aGVycy9Db250ZW50RmlsZVZpZXcudnVlP2RjOWEiXSwibmFtZXMiOlsiY29tcG9uZW50IiwiX3ZtIiwidGhpcyIsIl9oIiwiJGNyZWF0ZUVsZW1lbnQiLCJfYyIsIl9zZWxmIiwiYXR0cnMiLCJvbiIsIiRldmVudCIsInByZXZlbnREZWZhdWx0IiwiY29udGV4dE1lbnUiLCJ1bmRlZmluZWQiLCJfdiIsIiR0Iiwic3RhdGljQ2xhc3MiLCJjbGFzcyIsIiRpc1RoaXNMb2NhdGlvbiIsImdldFNoYXJlZCIsIl9zIiwiZ2V0UGFydGljaXBhbnRVcGxvYWRzIiwiY29udGVudCIsIm1vZHVsZSIsImkiLCJvcHRpb25zIiwidHJhbnNmb3JtIiwibG9jYWxzIiwiZXhwb3J0cyIsInB1c2giXSwibWFwcGluZ3MiOiI4RkFBQSx1QkFBNmIsRyxvQ0NBN2IsSSw4WkNrQkEsSUNsQnFOLEVEa0JyTixDQUNFLEtBQUYsWUFDRSxXQUFGLENBQ0kseUJBQUosSUFDSSxlQUFKLElBQ0ksWUFBSixJQUNJLFlBQUosS0FFRSxTLCtWQUFGLElBQ0EseUJBRUUsUUFBRixDQUNJLFlBREosU0FDQSxLQUNNLEVBQU4sbUMsd0JFdkJJQSxFQUFZLFlBQ2QsR0hUVyxXQUFhLElBQUlDLEVBQUlDLEtBQVNDLEVBQUdGLEVBQUlHLGVBQW1CQyxFQUFHSixFQUFJSyxNQUFNRCxJQUFJRixFQUFHLE9BQU9FLEVBQUcsTUFBTSxDQUFDRSxNQUFNLENBQUMsR0FBSyxjQUFjQyxHQUFHLENBQUMsZUFBZSxTQUFTQyxHQUFnQyxPQUF4QkEsRUFBT0MsaUJBQXdCVCxFQUFJVSxZQUFZRixPQUFRRyxNQUFjLENBQUNQLEVBQUcsZUFBZUosRUFBSVksR0FBRyxLQUFLUixFQUFHLDRCQUE0QkosRUFBSVksR0FBRyxLQUFLUixFQUFHLGtCQUFrQkosRUFBSVksR0FBRyxLQUFLUixFQUFHLGdCQUFnQixLQUNyVixJR1dwQixFQUNBLEtBQ0EsS0FDQSxNQUlhLElBQUFMLEUsa0RDbkJmLEksZ0RDQWlOLEVDMENqTixDQUNFLEtBQUYsWUFDRSxXQUFGLENBQ0ksZ0JBQUosSUFDSSxlQUFKLElBQ0ksYUFBSixJQUNJLFNBQUosSUFDSSxVQUFKLE1BRUUsUUFBRixDQUNJLFVBREosV0FFTSxLQUFOLDhCQUFRLE1BQVIsRUFBUSxNQUFSLE1BRUksc0JBSkosV0FLTSxLQUFOLDJDQUdFLFFBakJGLFdBa0JJLEtBQUosYyxZQ3JESUEsRUFBWSxZQUNkLEdIUlcsV0FBYSxJQUFJQyxFQUFJQyxLQUFTQyxFQUFHRixFQUFJRyxlQUFtQkMsRUFBR0osRUFBSUssTUFBTUQsSUFBSUYsRUFBRyxPQUFPRSxFQUFHLFVBQVUsQ0FBQ0UsTUFBTSxDQUFDLEdBQUssYUFBYSxDQUFDRixFQUFHLGlCQUFpQixDQUFDQSxFQUFHLGVBQWUsQ0FBQ0UsTUFBTSxDQUFDLE1BQVFOLEVBQUlhLEdBQUcsNkJBQTZCLENBQUNULEVBQUcsTUFBTSxDQUFDVSxZQUFZLDhCQUE4QixDQUFDVixFQUFHLEtBQUssQ0FBQ1UsWUFBWSxzQkFBc0JDLE1BQU0sQ0FBQyxZQUFhZixFQUFJZ0IsZ0JBQWdCLENBQUMsWUFBWVQsR0FBRyxDQUFDLE1BQVEsU0FBU0MsR0FBUSxPQUFPUixFQUFJaUIsZUFBZSxDQUFDYixFQUFHLE1BQU0sQ0FBQ1UsWUFBWSxtQkFBbUIsQ0FBQ1YsRUFBRyxZQUFZLENBQUNFLE1BQU0sQ0FBQyxLQUFPLFNBQVMsR0FBR04sRUFBSVksR0FBRyxLQUFLUixFQUFHLE1BQU0sQ0FBQ1UsWUFBWSxvQkFBb0IsQ0FBQ2QsRUFBSVksR0FBRyw2QkFBNkJaLEVBQUlrQixHQUFHbEIsRUFBSWEsR0FBRyxzQkFBc0IsOEJBQThCYixFQUFJWSxHQUFHLEtBQUtSLEVBQUcsS0FBSyxDQUFDVSxZQUFZLHNCQUFzQkMsTUFBTSxDQUFDLFlBQWFmLEVBQUlnQixnQkFBZ0IsQ0FBQyx5QkFBeUJULEdBQUcsQ0FBQyxNQUFRLFNBQVNDLEdBQVEsT0FBT1IsRUFBSW1CLDJCQUEyQixDQUFDZixFQUFHLE1BQU0sQ0FBQ1UsWUFBWSxtQkFBbUIsQ0FBQ1YsRUFBRyxhQUFhLENBQUNFLE1BQU0sQ0FBQyxLQUFPLFNBQVMsR0FBR04sRUFBSVksR0FBRyxLQUFLUixFQUFHLE1BQU0sQ0FBQ1UsWUFBWSxvQkFBb0IsQ0FBQ2QsRUFBSVksR0FBRyw2QkFBNkJaLEVBQUlrQixHQUFHbEIsRUFBSWEsR0FBRyxnQ0FBZ0MsbUNBQW1DLEdBQUdiLEVBQUlZLEdBQUcsS0FBS1IsRUFBRyxvQkFBb0IsS0FDaG5DLElHVXBCLEVBQ0EsS0FDQSxXQUNBLE1BSWEsVUFBQUwsRSw4QkNqQmYsSUFBSXFCLEVBQVUsRUFBUSxRQUVBLGlCQUFaQSxJQUFzQkEsRUFBVSxDQUFDLENBQUNDLEVBQU9DLEVBQUlGLEVBQVMsTUFPaEUsSUFBSUcsRUFBVSxDQUFDLEtBQU0sRUFFckIsZUFQSUMsRUFRSixnQkFBcUJiLEdBRVIsRUFBUSxPQUFSLENBQW1FUyxFQUFTRyxHQUV0RkgsRUFBUUssU0FBUUosRUFBT0ssUUFBVU4sRUFBUUssUyxzQkNqQmxDSixFQUFPSyxRQUFVLEVBQVEsT0FBUixFQUErRCxJQUtsRkMsS0FBSyxDQUFDTixFQUFPQyxFQUFJLHdXQUF5VyIsImZpbGUiOiJjaHVua3Mvc2hhcmVkLWZpbGVzLmpzP2lkPTc4YmUzMDI2Zjk5OTk3NTVmYTQ4Iiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IG1vZCBmcm9tIFwiLSEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvc3R5bGUtbG9hZGVyL2luZGV4LmpzIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9jc3MtbG9hZGVyL2luZGV4LmpzIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy92dWUtbG9hZGVyL2xpYi9sb2FkZXJzL3N0eWxlUG9zdExvYWRlci5qcyEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvcG9zdGNzcy1sb2FkZXIvc3JjL2luZGV4LmpzPz9yZWYtLTctMiEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvc2Fzcy1sb2FkZXIvZGlzdC9janMuanM/P3JlZi0tNy0zIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy92dWUtbG9hZGVyL2xpYi9pbmRleC5qcz8/dnVlLWxvYWRlci1vcHRpb25zIS4vQ29udGVudEZpbGVWaWV3LnZ1ZT92dWUmdHlwZT1zdHlsZSZpbmRleD0wJmxhbmc9c2NzcyZcIjsgZXhwb3J0IGRlZmF1bHQgbW9kOyBleHBvcnQgKiBmcm9tIFwiLSEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvc3R5bGUtbG9hZGVyL2luZGV4LmpzIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9jc3MtbG9hZGVyL2luZGV4LmpzIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy92dWUtbG9hZGVyL2xpYi9sb2FkZXJzL3N0eWxlUG9zdExvYWRlci5qcyEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvcG9zdGNzcy1sb2FkZXIvc3JjL2luZGV4LmpzPz9yZWYtLTctMiEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvc2Fzcy1sb2FkZXIvZGlzdC9janMuanM/P3JlZi0tNy0zIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy92dWUtbG9hZGVyL2xpYi9pbmRleC5qcz8/dnVlLWxvYWRlci1vcHRpb25zIS4vQ29udGVudEZpbGVWaWV3LnZ1ZT92dWUmdHlwZT1zdHlsZSZpbmRleD0wJmxhbmc9c2NzcyZcIiIsInZhciByZW5kZXIgPSBmdW5jdGlvbiAoKSB7dmFyIF92bT10aGlzO3ZhciBfaD1fdm0uJGNyZWF0ZUVsZW1lbnQ7dmFyIF9jPV92bS5fc2VsZi5fY3x8X2g7cmV0dXJuIF9jKCdkaXYnLHthdHRyczp7XCJpZFwiOlwiZmlsZXMtdmlld1wifSxvbjp7XCIhY29udGV4dG1lbnVcIjpmdW5jdGlvbigkZXZlbnQpeyRldmVudC5wcmV2ZW50RGVmYXVsdCgpO3JldHVybiBfdm0uY29udGV4dE1lbnUoJGV2ZW50LCB1bmRlZmluZWQpfX19LFtfYygnQ29udGV4dE1lbnUnKSxfdm0uX3YoXCIgXCIpLF9jKCdEZXNrdG9wU29ydGluZ0FuZFByZXZpZXcnKSxfdm0uX3YoXCIgXCIpLF9jKCdEZXNrdG9wVG9vbGJhcicpLF92bS5fdihcIiBcIiksX2MoJ0ZpbGVCcm93c2VyJyldLDEpfVxudmFyIHN0YXRpY1JlbmRlckZucyA9IFtdXG5cbmV4cG9ydCB7IHJlbmRlciwgc3RhdGljUmVuZGVyRm5zIH0iLCI8dGVtcGxhdGU+XG4gICAgPGRpdiBAY29udGV4dG1lbnUucHJldmVudC5jYXB0dXJlPVwiY29udGV4dE1lbnUoJGV2ZW50LCB1bmRlZmluZWQpXCJcbiAgICAgICAgIGlkPVwiZmlsZXMtdmlld1wiPlxuICAgICAgICA8Q29udGV4dE1lbnUvPlxuICAgICAgICA8RGVza3RvcFNvcnRpbmdBbmRQcmV2aWV3Lz5cbiAgICAgICAgPERlc2t0b3BUb29sYmFyLz5cbiAgICAgICAgPEZpbGVCcm93c2VyLz5cbiAgICA8L2Rpdj5cbjwvdGVtcGxhdGU+XG5cbjxzY3JpcHQ+XG4gICAgaW1wb3J0IERlc2t0b3BTb3J0aW5nQW5kUHJldmlldyBmcm9tICdAL2NvbXBvbmVudHMvRmlsZXNWaWV3L0Rlc2t0b3BTb3J0aW5nQW5kUHJldmlldydcbiAgICBpbXBvcnQgRGVza3RvcFRvb2xiYXIgZnJvbSAnQC9jb21wb25lbnRzL0ZpbGVzVmlldy9EZXNrdG9wVG9vbGJhcidcbiAgICBpbXBvcnQgRmlsZUJyb3dzZXIgZnJvbSAnQC9jb21wb25lbnRzL0ZpbGVzVmlldy9GaWxlQnJvd3NlcidcbiAgICBpbXBvcnQgQ29udGV4dE1lbnUgZnJvbSAnQC9jb21wb25lbnRzL0ZpbGVzVmlldy9Db250ZXh0TWVudSdcbiAgICBpbXBvcnQge21hcEdldHRlcnN9IGZyb20gJ3Z1ZXgnXG4gICAgaW1wb3J0IHtldmVudHN9IGZyb20gJ0AvYnVzJ1xuXG4gICAgZXhwb3J0IGRlZmF1bHQge1xuICAgICAgICBuYW1lOiAnRmlsZXNWaWV3JyxcbiAgICAgICAgY29tcG9uZW50czoge1xuICAgICAgICAgICAgRGVza3RvcFNvcnRpbmdBbmRQcmV2aWV3LFxuICAgICAgICAgICAgRGVza3RvcFRvb2xiYXIsXG4gICAgICAgICAgICBGaWxlQnJvd3NlcixcbiAgICAgICAgICAgIENvbnRleHRNZW51LFxuICAgICAgICB9LFxuICAgICAgICBjb21wdXRlZDoge1xuICAgICAgICAgICAgLi4ubWFwR2V0dGVycyhbJ2NvbmZpZyddKSxcbiAgICAgICAgfSxcbiAgICAgICAgbWV0aG9kczoge1xuICAgICAgICAgICAgY29udGV4dE1lbnUoZXZlbnQsIGl0ZW0pIHtcbiAgICAgICAgICAgICAgICBldmVudHMuJGVtaXQoJ2NvbnRleHRNZW51OnNob3cnLCBldmVudCwgaXRlbSlcbiAgICAgICAgICAgIH0sXG4gICAgICAgIH0sXG4gICAgfVxuPC9zY3JpcHQ+XG5cbjxzdHlsZSBsYW5nPVwic2Nzc1wiPlxuICAgIEBpbXBvcnQgJ0Bhc3NldHMvdnVlZmlsZW1hbmFnZXIvX3ZhcmlhYmxlcyc7XG4gICAgQGltcG9ydCAnQGFzc2V0cy92dWVmaWxlbWFuYWdlci9fbWl4aW5zJztcblxuICAgICNmaWxlcy12aWV3IHtcbiAgICAgICAgZm9udC1mYW1pbHk6ICdOdW5pdG8nLCBzYW5zLXNlcmlmO1xuICAgICAgICBmb250LXNpemU6IDE2cHg7XG4gICAgICAgIHdpZHRoOiAxMDAlO1xuICAgICAgICBoZWlnaHQ6IDEwMCU7XG4gICAgICAgIHBvc2l0aW9uOiByZWxhdGl2ZTtcbiAgICAgICAgbWluLXdpZHRoOiAzMjBweDtcbiAgICAgICAgb3ZlcmZsb3cteDogaGlkZGVuO1xuICAgICAgICBwYWRkaW5nLWxlZnQ6IDE1cHg7XG4gICAgICAgIHBhZGRpbmctcmlnaHQ6IDE1cHg7XG4gICAgICAgIG92ZXJmbG93LXk6IGhpZGRlbjtcbiAgICB9XG5cbiAgICBAbWVkaWEgb25seSBzY3JlZW4gYW5kIChtYXgtd2lkdGg6IDY5MHB4KSB7XG4gICAgICAgICNmaWxlcy12aWV3IHtcbiAgICAgICAgICAgIHBhZGRpbmctbGVmdDogMDtcbiAgICAgICAgICAgIHBhZGRpbmctcmlnaHQ6IDA7XG4gICAgICAgIH1cbiAgICB9XG5cbjwvc3R5bGU+XG4iLCJpbXBvcnQgbW9kIGZyb20gXCItIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9iYWJlbC1sb2FkZXIvbGliL2luZGV4LmpzPz9yZWYtLTQtMCEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvdnVlLWxvYWRlci9saWIvaW5kZXguanM/P3Z1ZS1sb2FkZXItb3B0aW9ucyEuL0NvbnRlbnRGaWxlVmlldy52dWU/dnVlJnR5cGU9c2NyaXB0Jmxhbmc9anMmXCI7IGV4cG9ydCBkZWZhdWx0IG1vZDsgZXhwb3J0ICogZnJvbSBcIi0hLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL2JhYmVsLWxvYWRlci9saWIvaW5kZXguanM/P3JlZi0tNC0wIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy92dWUtbG9hZGVyL2xpYi9pbmRleC5qcz8/dnVlLWxvYWRlci1vcHRpb25zIS4vQ29udGVudEZpbGVWaWV3LnZ1ZT92dWUmdHlwZT1zY3JpcHQmbGFuZz1qcyZcIiIsImltcG9ydCB7IHJlbmRlciwgc3RhdGljUmVuZGVyRm5zIH0gZnJvbSBcIi4vQ29udGVudEZpbGVWaWV3LnZ1ZT92dWUmdHlwZT10ZW1wbGF0ZSZpZD04YTFjYjQxYyZcIlxuaW1wb3J0IHNjcmlwdCBmcm9tIFwiLi9Db250ZW50RmlsZVZpZXcudnVlP3Z1ZSZ0eXBlPXNjcmlwdCZsYW5nPWpzJlwiXG5leHBvcnQgKiBmcm9tIFwiLi9Db250ZW50RmlsZVZpZXcudnVlP3Z1ZSZ0eXBlPXNjcmlwdCZsYW5nPWpzJlwiXG5pbXBvcnQgc3R5bGUwIGZyb20gXCIuL0NvbnRlbnRGaWxlVmlldy52dWU/dnVlJnR5cGU9c3R5bGUmaW5kZXg9MCZsYW5nPXNjc3MmXCJcblxuXG4vKiBub3JtYWxpemUgY29tcG9uZW50ICovXG5pbXBvcnQgbm9ybWFsaXplciBmcm9tIFwiIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy92dWUtbG9hZGVyL2xpYi9ydW50aW1lL2NvbXBvbmVudE5vcm1hbGl6ZXIuanNcIlxudmFyIGNvbXBvbmVudCA9IG5vcm1hbGl6ZXIoXG4gIHNjcmlwdCxcbiAgcmVuZGVyLFxuICBzdGF0aWNSZW5kZXJGbnMsXG4gIGZhbHNlLFxuICBudWxsLFxuICBudWxsLFxuICBudWxsXG4gIFxuKVxuXG5leHBvcnQgZGVmYXVsdCBjb21wb25lbnQuZXhwb3J0cyIsInZhciByZW5kZXIgPSBmdW5jdGlvbiAoKSB7dmFyIF92bT10aGlzO3ZhciBfaD1fdm0uJGNyZWF0ZUVsZW1lbnQ7dmFyIF9jPV92bS5fc2VsZi5fY3x8X2g7cmV0dXJuIF9jKCdzZWN0aW9uJyx7YXR0cnM6e1wiaWRcIjpcInZpZXdwb3J0XCJ9fSxbX2MoJ0NvbnRlbnRTaWRlYmFyJyxbX2MoJ0NvbnRlbnRHcm91cCcse2F0dHJzOntcInRpdGxlXCI6X3ZtLiR0KCdzaWRlYmFyLmxvY2F0aW9uc190aXRsZScpfX0sW19jKCdkaXYnLHtzdGF0aWNDbGFzczpcIm1lbnUtbGlzdC13cmFwcGVyIHZlcnRpY2FsXCJ9LFtfYygnbGknLHtzdGF0aWNDbGFzczpcIm1lbnUtbGlzdC1pdGVtIGxpbmtcIixjbGFzczp7J2lzLWFjdGl2ZSc6IF92bS4kaXNUaGlzTG9jYXRpb24oWydzaGFyZWQnXSl9LG9uOntcImNsaWNrXCI6ZnVuY3Rpb24oJGV2ZW50KXtyZXR1cm4gX3ZtLmdldFNoYXJlZCgpfX19LFtfYygnZGl2Jyx7c3RhdGljQ2xhc3M6XCJpY29uIHRleHQtdGhlbWVcIn0sW19jKCdsaW5rLWljb24nLHthdHRyczp7XCJzaXplXCI6XCIxN1wifX0pXSwxKSxfdm0uX3YoXCIgXCIpLF9jKCdkaXYnLHtzdGF0aWNDbGFzczpcImxhYmVsIHRleHQtdGhlbWVcIn0sW192bS5fdihcIlxcbiAgICAgICAgICAgICAgICAgICAgICAgIFwiK192bS5fcyhfdm0uJHQoJ3NpZGViYXIubXlfc2hhcmVkJykpK1wiXFxuICAgICAgICAgICAgICAgICAgICBcIildKV0pLF92bS5fdihcIiBcIiksX2MoJ2xpJyx7c3RhdGljQ2xhc3M6XCJtZW51LWxpc3QtaXRlbSBsaW5rXCIsY2xhc3M6eydpcy1hY3RpdmUnOiBfdm0uJGlzVGhpc0xvY2F0aW9uKFsncGFydGljaXBhbnRfdXBsb2FkcyddKX0sb246e1wiY2xpY2tcIjpmdW5jdGlvbigkZXZlbnQpe3JldHVybiBfdm0uZ2V0UGFydGljaXBhbnRVcGxvYWRzKCl9fX0sW19jKCdkaXYnLHtzdGF0aWNDbGFzczpcImljb24gdGV4dC10aGVtZVwifSxbX2MoJ3VzZXJzLWljb24nLHthdHRyczp7XCJzaXplXCI6XCIxN1wifX0pXSwxKSxfdm0uX3YoXCIgXCIpLF9jKCdkaXYnLHtzdGF0aWNDbGFzczpcImxhYmVsIHRleHQtdGhlbWVcIn0sW192bS5fdihcIlxcbiAgICAgICAgICAgICAgICAgICAgICAgIFwiK192bS5fcyhfdm0uJHQoJ3NpZGViYXIucGFydGljaXBhbnRfdXBsb2FkcycpKStcIlxcbiAgICAgICAgICAgICAgICAgICAgXCIpXSldKV0pXSldLDEpLF92bS5fdihcIiBcIiksX2MoJ0NvbnRlbnRGaWxlVmlldycpXSwxKX1cbnZhciBzdGF0aWNSZW5kZXJGbnMgPSBbXVxuXG5leHBvcnQgeyByZW5kZXIsIHN0YXRpY1JlbmRlckZucyB9IiwiaW1wb3J0IG1vZCBmcm9tIFwiLSEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvYmFiZWwtbG9hZGVyL2xpYi9pbmRleC5qcz8/cmVmLS00LTAhLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL3Z1ZS1sb2FkZXIvbGliL2luZGV4LmpzPz92dWUtbG9hZGVyLW9wdGlvbnMhLi9TaGFyZWRGaWxlcy52dWU/dnVlJnR5cGU9c2NyaXB0Jmxhbmc9anMmXCI7IGV4cG9ydCBkZWZhdWx0IG1vZDsgZXhwb3J0ICogZnJvbSBcIi0hLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL2JhYmVsLWxvYWRlci9saWIvaW5kZXguanM/P3JlZi0tNC0wIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy92dWUtbG9hZGVyL2xpYi9pbmRleC5qcz8/dnVlLWxvYWRlci1vcHRpb25zIS4vU2hhcmVkRmlsZXMudnVlP3Z1ZSZ0eXBlPXNjcmlwdCZsYW5nPWpzJlwiIiwiPHRlbXBsYXRlPlxuICAgIDxzZWN0aW9uIGlkPVwidmlld3BvcnRcIj5cblxuICAgICAgICA8Q29udGVudFNpZGViYXI+XG5cbiAgICAgICAgICAgIDwhLS1OYXZpZ2F0b3ItLT5cbiAgICAgICAgICAgIDxDb250ZW50R3JvdXAgOnRpdGxlPVwiJHQoJ3NpZGViYXIubG9jYXRpb25zX3RpdGxlJylcIj5cbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwibWVudS1saXN0LXdyYXBwZXIgdmVydGljYWxcIj5cbiAgICAgICAgICAgICAgICAgICAgPGxpIGNsYXNzPVwibWVudS1saXN0LWl0ZW0gbGlua1wiIDpjbGFzcz1cInsnaXMtYWN0aXZlJzogJGlzVGhpc0xvY2F0aW9uKFsnc2hhcmVkJ10pfVwiIEBjbGljaz1cImdldFNoYXJlZCgpXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiaWNvbiB0ZXh0LXRoZW1lXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpbmstaWNvbiBzaXplPVwiMTdcIj48L2xpbmstaWNvbj5cbiAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImxhYmVsIHRleHQtdGhlbWVcIj5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB7eyAkdCgnc2lkZWJhci5teV9zaGFyZWQnKSB9fVxuICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICAgICAgICAgIDwvbGk+XG4gICAgICAgICAgICAgICAgICAgIDxsaSBjbGFzcz1cIm1lbnUtbGlzdC1pdGVtIGxpbmtcIiA6Y2xhc3M9XCJ7J2lzLWFjdGl2ZSc6ICRpc1RoaXNMb2NhdGlvbihbJ3BhcnRpY2lwYW50X3VwbG9hZHMnXSl9XCIgQGNsaWNrPVwiZ2V0UGFydGljaXBhbnRVcGxvYWRzKClcIj5cbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJpY29uIHRleHQtdGhlbWVcIj5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8dXNlcnMtaWNvbiBzaXplPVwiMTdcIj48L3VzZXJzLWljb24+XG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJsYWJlbCB0ZXh0LXRoZW1lXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAge3sgJHQoJ3NpZGViYXIucGFydGljaXBhbnRfdXBsb2FkcycpIH19XG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgICAgICAgPC9saT5cbiAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDwvQ29udGVudEdyb3VwPlxuXG4gICAgICAgIDwvQ29udGVudFNpZGViYXI+XG5cbiAgICAgICAgPENvbnRlbnRGaWxlVmlldyAvPlxuICAgIDwvc2VjdGlvbj5cbjwvdGVtcGxhdGU+XG5cbjxzY3JpcHQ+XG4gICAgaW1wb3J0IENvbnRlbnRGaWxlVmlldyBmcm9tICdAL2NvbXBvbmVudHMvT3RoZXJzL0NvbnRlbnRGaWxlVmlldydcbiAgICBpbXBvcnQgQ29udGVudFNpZGViYXIgZnJvbSAnQC9jb21wb25lbnRzL1NpZGViYXIvQ29udGVudFNpZGViYXInXG4gICAgaW1wb3J0IENvbnRlbnRHcm91cCBmcm9tICdAL2NvbXBvbmVudHMvU2lkZWJhci9Db250ZW50R3JvdXAnXG4gICAgaW1wb3J0IHtcbiAgICAgICAgTGlua0ljb24sXG4gICAgICAgIFVzZXJzSWNvbixcbiAgICB9IGZyb20gJ3Z1ZS1mZWF0aGVyLWljb25zJ1xuXG4gICAgZXhwb3J0IGRlZmF1bHQge1xuICAgICAgICBuYW1lOiAnRmlsZXNWaWV3JyxcbiAgICAgICAgY29tcG9uZW50czoge1xuICAgICAgICAgICAgQ29udGVudEZpbGVWaWV3LFxuICAgICAgICAgICAgQ29udGVudFNpZGViYXIsXG4gICAgICAgICAgICBDb250ZW50R3JvdXAsXG4gICAgICAgICAgICBMaW5rSWNvbixcbiAgICAgICAgICAgIFVzZXJzSWNvbixcbiAgICAgICAgfSxcbiAgICAgICAgbWV0aG9kczoge1xuICAgICAgICAgICAgZ2V0U2hhcmVkKCkge1xuICAgICAgICAgICAgICAgIHRoaXMuJHN0b3JlLmRpc3BhdGNoKCdnZXRTaGFyZWQnLCBbe2JhY2s6IGZhbHNlLCBpbml0OiBmYWxzZX1dKVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGdldFBhcnRpY2lwYW50VXBsb2FkcygpIHtcbiAgICAgICAgICAgICAgICB0aGlzLiRzdG9yZS5kaXNwYXRjaCgnZ2V0UGFydGljaXBhbnRVcGxvYWRzJylcbiAgICAgICAgICAgIH0sXG4gICAgICAgIH0sXG4gICAgICAgIG1vdW50ZWQoKSB7XG4gICAgICAgICAgICB0aGlzLmdldFNoYXJlZCgpXG4gICAgICAgIH1cbiAgICB9XG48L3NjcmlwdD5cblxuPHN0eWxlIGxhbmc9XCJzY3NzXCIgc2NvcGVkPlxuPC9zdHlsZT5cbiIsImltcG9ydCB7IHJlbmRlciwgc3RhdGljUmVuZGVyRm5zIH0gZnJvbSBcIi4vU2hhcmVkRmlsZXMudnVlP3Z1ZSZ0eXBlPXRlbXBsYXRlJmlkPTU1MDQzMDA0JnNjb3BlZD10cnVlJlwiXG5pbXBvcnQgc2NyaXB0IGZyb20gXCIuL1NoYXJlZEZpbGVzLnZ1ZT92dWUmdHlwZT1zY3JpcHQmbGFuZz1qcyZcIlxuZXhwb3J0ICogZnJvbSBcIi4vU2hhcmVkRmlsZXMudnVlP3Z1ZSZ0eXBlPXNjcmlwdCZsYW5nPWpzJlwiXG5cblxuLyogbm9ybWFsaXplIGNvbXBvbmVudCAqL1xuaW1wb3J0IG5vcm1hbGl6ZXIgZnJvbSBcIiEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvdnVlLWxvYWRlci9saWIvcnVudGltZS9jb21wb25lbnROb3JtYWxpemVyLmpzXCJcbnZhciBjb21wb25lbnQgPSBub3JtYWxpemVyKFxuICBzY3JpcHQsXG4gIHJlbmRlcixcbiAgc3RhdGljUmVuZGVyRm5zLFxuICBmYWxzZSxcbiAgbnVsbCxcbiAgXCI1NTA0MzAwNFwiLFxuICBudWxsXG4gIFxuKVxuXG5leHBvcnQgZGVmYXVsdCBjb21wb25lbnQuZXhwb3J0cyIsIlxudmFyIGNvbnRlbnQgPSByZXF1aXJlKFwiISEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvY3NzLWxvYWRlci9pbmRleC5qcyEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvdnVlLWxvYWRlci9saWIvbG9hZGVycy9zdHlsZVBvc3RMb2FkZXIuanMhLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL3Bvc3Rjc3MtbG9hZGVyL3NyYy9pbmRleC5qcz8/cmVmLS03LTIhLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL3Nhc3MtbG9hZGVyL2Rpc3QvY2pzLmpzPz9yZWYtLTctMyEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvdnVlLWxvYWRlci9saWIvaW5kZXguanM/P3Z1ZS1sb2FkZXItb3B0aW9ucyEuL0NvbnRlbnRGaWxlVmlldy52dWU/dnVlJnR5cGU9c3R5bGUmaW5kZXg9MCZsYW5nPXNjc3MmXCIpO1xuXG5pZih0eXBlb2YgY29udGVudCA9PT0gJ3N0cmluZycpIGNvbnRlbnQgPSBbW21vZHVsZS5pZCwgY29udGVudCwgJyddXTtcblxudmFyIHRyYW5zZm9ybTtcbnZhciBpbnNlcnRJbnRvO1xuXG5cblxudmFyIG9wdGlvbnMgPSB7XCJobXJcIjp0cnVlfVxuXG5vcHRpb25zLnRyYW5zZm9ybSA9IHRyYW5zZm9ybVxub3B0aW9ucy5pbnNlcnRJbnRvID0gdW5kZWZpbmVkO1xuXG52YXIgdXBkYXRlID0gcmVxdWlyZShcIiEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvc3R5bGUtbG9hZGVyL2xpYi9hZGRTdHlsZXMuanNcIikoY29udGVudCwgb3B0aW9ucyk7XG5cbmlmKGNvbnRlbnQubG9jYWxzKSBtb2R1bGUuZXhwb3J0cyA9IGNvbnRlbnQubG9jYWxzO1xuXG5pZihtb2R1bGUuaG90KSB7XG5cdG1vZHVsZS5ob3QuYWNjZXB0KFwiISEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvY3NzLWxvYWRlci9pbmRleC5qcyEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvdnVlLWxvYWRlci9saWIvbG9hZGVycy9zdHlsZVBvc3RMb2FkZXIuanMhLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL3Bvc3Rjc3MtbG9hZGVyL3NyYy9pbmRleC5qcz8/cmVmLS03LTIhLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL3Nhc3MtbG9hZGVyL2Rpc3QvY2pzLmpzPz9yZWYtLTctMyEuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvdnVlLWxvYWRlci9saWIvaW5kZXguanM/P3Z1ZS1sb2FkZXItb3B0aW9ucyEuL0NvbnRlbnRGaWxlVmlldy52dWU/dnVlJnR5cGU9c3R5bGUmaW5kZXg9MCZsYW5nPXNjc3MmXCIsIGZ1bmN0aW9uKCkge1xuXHRcdHZhciBuZXdDb250ZW50ID0gcmVxdWlyZShcIiEhLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL2Nzcy1sb2FkZXIvaW5kZXguanMhLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL3Z1ZS1sb2FkZXIvbGliL2xvYWRlcnMvc3R5bGVQb3N0TG9hZGVyLmpzIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9wb3N0Y3NzLWxvYWRlci9zcmMvaW5kZXguanM/P3JlZi0tNy0yIS4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9zYXNzLWxvYWRlci9kaXN0L2Nqcy5qcz8/cmVmLS03LTMhLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL3Z1ZS1sb2FkZXIvbGliL2luZGV4LmpzPz92dWUtbG9hZGVyLW9wdGlvbnMhLi9Db250ZW50RmlsZVZpZXcudnVlP3Z1ZSZ0eXBlPXN0eWxlJmluZGV4PTAmbGFuZz1zY3NzJlwiKTtcblxuXHRcdGlmKHR5cGVvZiBuZXdDb250ZW50ID09PSAnc3RyaW5nJykgbmV3Q29udGVudCA9IFtbbW9kdWxlLmlkLCBuZXdDb250ZW50LCAnJ11dO1xuXG5cdFx0dmFyIGxvY2FscyA9IChmdW5jdGlvbihhLCBiKSB7XG5cdFx0XHR2YXIga2V5LCBpZHggPSAwO1xuXG5cdFx0XHRmb3Ioa2V5IGluIGEpIHtcblx0XHRcdFx0aWYoIWIgfHwgYVtrZXldICE9PSBiW2tleV0pIHJldHVybiBmYWxzZTtcblx0XHRcdFx0aWR4Kys7XG5cdFx0XHR9XG5cblx0XHRcdGZvcihrZXkgaW4gYikgaWR4LS07XG5cblx0XHRcdHJldHVybiBpZHggPT09IDA7XG5cdFx0fShjb250ZW50LmxvY2FscywgbmV3Q29udGVudC5sb2NhbHMpKTtcblxuXHRcdGlmKCFsb2NhbHMpIHRocm93IG5ldyBFcnJvcignQWJvcnRpbmcgQ1NTIEhNUiBkdWUgdG8gY2hhbmdlZCBjc3MtbW9kdWxlcyBsb2NhbHMuJyk7XG5cblx0XHR1cGRhdGUobmV3Q29udGVudCk7XG5cdH0pO1xuXG5cdG1vZHVsZS5ob3QuZGlzcG9zZShmdW5jdGlvbigpIHsgdXBkYXRlKCk7IH0pO1xufSIsImV4cG9ydHMgPSBtb2R1bGUuZXhwb3J0cyA9IHJlcXVpcmUoXCIuLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvY3NzLWxvYWRlci9saWIvY3NzLWJhc2UuanNcIikoZmFsc2UpO1xuLy8gaW1wb3J0c1xuXG5cbi8vIG1vZHVsZVxuZXhwb3J0cy5wdXNoKFttb2R1bGUuaWQsIFwiI2ZpbGVzLXZpZXcge1xcbiAgZm9udC1mYW1pbHk6ICdOdW5pdG8nLCBzYW5zLXNlcmlmO1xcbiAgZm9udC1zaXplOiAxNnB4O1xcbiAgd2lkdGg6IDEwMCU7XFxuICBoZWlnaHQ6IDEwMCU7XFxuICBwb3NpdGlvbjogcmVsYXRpdmU7XFxuICBtaW4td2lkdGg6IDMyMHB4O1xcbiAgb3ZlcmZsb3cteDogaGlkZGVuO1xcbiAgcGFkZGluZy1sZWZ0OiAxNXB4O1xcbiAgcGFkZGluZy1yaWdodDogMTVweDtcXG4gIG92ZXJmbG93LXk6IGhpZGRlbjtcXG59XFxuQG1lZGlhIG9ubHkgc2NyZWVuIGFuZCAobWF4LXdpZHRoOiA2OTBweCkge1xcbiNmaWxlcy12aWV3IHtcXG4gICAgcGFkZGluZy1sZWZ0OiAwO1xcbiAgICBwYWRkaW5nLXJpZ2h0OiAwO1xcbn1cXG59XFxuXCIsIFwiXCJdKTtcblxuLy8gZXhwb3J0c1xuIl0sInNvdXJjZVJvb3QiOiIifQ==