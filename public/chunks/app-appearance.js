(window.webpackJsonp=window.webpackJsonp||[]).push([[13],{"+Pqb":function(a,e,t){"use strict";var n={name:"ProgressBar",props:["progress"]},o=(t("8L2t"),t("KHd+")),i=Object(o.a)(n,(function(){var a=this.$createElement,e=this._self._c||a;return e("div",{staticClass:"progress-bar"},[e("span",{staticClass:"bg-theme",style:{width:this.progress+"%"}})])}),[],!1,null,"6ec2be7a",null);e.a=i.exports},"+v8A":function(a,e,t){var n=t("hw5j");"string"==typeof n&&(n=[[a.i,n,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(n,o);n.locals&&(a.exports=n.locals)},"1XC+":function(a,e,t){"use strict";t.r(e);var n=t("A5+z"),o=t("xnZf"),i=t("gahf"),d=t("4TWA"),r=t("D+dh"),s=t("eZ9V"),p=t("Nv84"),l=t("13Td"),c=t("qefO"),v=t("KnjL"),m=t("TJPC"),u=t("vDqi"),b=t.n(u),f={name:"AppAppearance",components:{ValidationObserver:n.ValidationObserver,ValidationProvider:n.ValidationProvider,StorageItemDetail:o.a,PageTabGroup:i.a,SelectInput:d.a,ImageInput:r.a,ButtonBase:p.a,FormLabel:s.a,SetupBox:l.a,required:m.a,PageTab:c.a,InfoBox:v.a},data:function(){return{isLoading:!0,app:void 0}},mounted:function(){var a=this;b.a.get("/api/admin/settings",{params:{column:"app_title|app_description|app_logo|app_favicon|app_logo_horizontal|app_color"}}).then((function(e){a.app={logo_horizontal:e.data.app_logo_horizontal,description:e.data.app_description,favicon:e.data.app_favicon,title:e.data.app_title,color:e.data.app_color,logo:e.data.app_logo}})).finally((function(){a.isLoading=!1}))}},g=(t("l39B"),t("KHd+")),h=Object(g.a)(f,(function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("PageTab",{staticClass:"form-fixed-width",attrs:{"is-loading":a.isLoading}},[a.app?t("PageTabGroup",[t("div",{staticClass:"form block-form"},[t("FormLabel",[a._v(a._s(a.$t("admin_settings.appearance.section_general")))]),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_settings.appearance.title"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Title",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(e){var n=e.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.app.title,expression:"app.title"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:a.$t("admin_settings.appearance.title_plac"),type:"text"},domProps:{value:a.app.title},on:{input:[function(e){e.target.composing||a.$set(a.app,"title",e.target.value)},function(e){return a.$updateText("/admin/settings","app_title",a.app.title)}]}}),a._v(" "),n[0]?t("span",{staticClass:"error-message"},[a._v(a._s(n[0]))]):a._e()]}}],null,!1,1189024011)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_settings.appearance.description"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Description",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(e){var n=e.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.app.description,expression:"app.description"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:a.$t("admin_settings.appearance.description_plac"),type:"text"},domProps:{value:a.app.description},on:{input:[function(e){e.target.composing||a.$set(a.app,"description",e.target.value)},function(e){return a.$updateText("/admin/settings","app_description",a.app.description)}]}}),a._v(" "),n[0]?t("span",{staticClass:"error-message"},[a._v(a._s(n[0]))]):a._e()]}}],null,!1,735436589)})],1),a._v(" "),t("FormLabel",{staticClass:"mt-70"},[a._v(a._s(a.$t("admin_settings.appearance.section_appearance")))]),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Title",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(e){var n=e.errors;return[t("div",{staticClass:"inline-wrapper"},[t("div",{staticClass:"switch-label"},[t("label",{staticClass:"input-label"},[a._v("Color Theme:")]),a._v(" "),t("small",{staticClass:"input-help"},[a._v("Your color change will be visible after app refresh.")]),a._v(" "),n[0]?t("span",{staticClass:"error-message"},[a._v(a._s(n[0]))]):a._e()]),a._v(" "),t("input",{directives:[{name:"model",rawName:"v-model",value:a.app.color,expression:"app.color"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:a.$t("admin_settings.appearance.title_plac"),type:"color"},domProps:{value:a.app.color},on:{input:[function(e){e.target.composing||a.$set(a.app,"color",e.target.value)},function(e){return a.$updateText("/admin/settings","app_color",a.app.color)}]}})])]}}],null,!1,3986394998)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_settings.appearance.logo"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Logo"},scopedSlots:a._u([{key:"default",fn:function(e){var n=e.errors;return[t("ImageInput",{attrs:{image:a.$getImage(a.app.logo),error:n[0]},on:{input:function(e){return a.$updateImage("/admin/settings","app_logo",a.app.logo)}},model:{value:a.app.logo,callback:function(e){a.$set(a.app,"logo",e)},expression:"app.logo"}})]}}],null,!1,1322801077)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_settings.appearance.logo_horizontal"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Logo Horizontal"},scopedSlots:a._u([{key:"default",fn:function(e){var n=e.errors;return[t("ImageInput",{attrs:{image:a.$getImage(a.app.logo_horizontal),error:n[0]},on:{input:function(e){return a.$updateImage("/admin/settings","app_logo_horizontal",a.app.logo_horizontal)}},model:{value:a.app.logo_horizontal,callback:function(e){a.$set(a.app,"logo_horizontal",e)},expression:"app.logo_horizontal"}})]}}],null,!1,1123020821)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_settings.appearance.favicon"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Favicon"},scopedSlots:a._u([{key:"default",fn:function(e){var n=e.errors;return[t("ImageInput",{attrs:{image:a.$getImage(a.app.favicon),error:n[0]},on:{input:function(e){return a.$updateImage("/admin/settings","app_favicon",a.app.favicon)}},model:{value:a.app.favicon,callback:function(e){a.$set(a.app,"favicon",e)},expression:"app.favicon"}})]}}],null,!1,906519509)})],1)],1)]):a._e()],1)}),[],!1,null,"b1d9fdac",null);e.default=h.exports},"2jUW":function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".dropzone[data-v-5d141a68] {\n  border: 1px dashed #a1abc2;\n  border-radius: 8px;\n  position: relative;\n  text-align: center;\n  display: flex;\n  align-items: center;\n  min-height: 175px;\n}\n.dropzone.is-error[data-v-5d141a68] {\n  border: 2px dashed rgba(253, 57, 122, 0.3);\n}\n.dropzone.is-error .dropzone-title[data-v-5d141a68] {\n  color: #fd397a;\n}\n.dropzone.is-error .icon-upload rect[data-v-5d141a68], .dropzone.is-error .icon-upload circle[data-v-5d141a68], .dropzone.is-error .icon-upload polyline[data-v-5d141a68] {\n  stroke: #fd397a;\n}\n.dropzone input[type='file'][data-v-5d141a68] {\n  opacity: 0;\n  position: absolute;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  z-index: 1;\n  width: 100%;\n  cursor: pointer;\n}\n.dropzone .image-preview[data-v-5d141a68] {\n  position: absolute;\n  width: 100%;\n  height: 100%;\n  -o-object-fit: contain;\n     object-fit: contain;\n  left: 0;\n  padding: 25px;\n  display: block;\n}\n.dropzone .image-preview.fit-image[data-v-5d141a68] {\n  -o-object-fit: cover;\n     object-fit: cover;\n  border-radius: 12px;\n  overflow: hidden;\n}\n.dropzone .dropzone-message[data-v-5d141a68] {\n  padding: 50px 0;\n  width: 100%;\n}\n.dropzone .dropzone-message .icon-upload rect[data-v-5d141a68], .dropzone .dropzone-message .icon-upload circle[data-v-5d141a68], .dropzone .dropzone-message .icon-upload polyline[data-v-5d141a68] {\n  color: inherit;\n}\n.dropzone .dropzone-message .dropzone-title[data-v-5d141a68] {\n  font-size: 1em;\n  font-weight: 700;\n  display: block;\n}\n.dropzone .dropzone-message .dropzone-description[data-v-5d141a68] {\n  color: rgba(27, 37, 57, 0.7);\n  font-size: 0.75em;\n}\n.dropzone .reset-image[data-v-5d141a68] {\n  z-index: 2;\n  background: white;\n  border-radius: 50px;\n  display: block;\n  position: absolute;\n  right: 0;\n  top: 0;\n  cursor: pointer;\n  transform: translateY(-50%) translateX(50%);\n  padding: 0px 4px;\n  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);\n}\n.dropzone .reset-image .close-icon[data-v-5d141a68] {\n  vertical-align: middle;\n}\n.dropzone .reset-image .close-icon line path[data-v-5d141a68] {\n  fill: #1B2539;\n}\n@media (prefers-color-scheme: dark) {\n.dropzone[data-v-5d141a68] {\n    border-color: rgba(255, 255, 255, 0.2);\n}\n.dropzone .dropzone-message .icon-upload path[data-v-5d141a68], .dropzone .dropzone-message .icon-upload polyline[data-v-5d141a68], .dropzone .dropzone-message .icon-upload line[data-v-5d141a68] {\n    color: inherit;\n}\n.dropzone .dropzone-message .dropzone-description[data-v-5d141a68] {\n    color: #7d858c;\n}\n}\n",""])},"3GDE":function(a,e,t){var n=t("UqlB");"string"==typeof n&&(n=[[a.i,n,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(n,o);n.locals&&(a.exports=n.locals)},"4TWA":function(a,e,t){"use strict";var n=t("CjXH"),o={name:"SelectInput",props:["options","isError","default","placeholder"],components:{Edit2Icon:n.r,UserIcon:n.db,ChevronDownIcon:n.f},data:function(){return{selected:void 0,isOpen:!1}},methods:{selectOption:function(a){this.$emit("input",a.value),this.selected=a,this.isOpen=!1},openMenu:function(){this.isOpen=!this.isOpen}},created:function(){var a=this;this.default&&(this.selected=this.options.find((function(e){return e.value===a.default})))}},i=(t("F+Qg"),t("KHd+")),d=Object(i.a)(o,(function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("div",{staticClass:"select"},[t("div",{staticClass:"input-area",class:{"is-active":a.isOpen,"is-error":a.isError},on:{click:a.openMenu}},[a.selected?t("div",{staticClass:"selected"},[a.selected.icon?t("div",{staticClass:"option-icon"},["user"===a.selected.icon?t("user-icon",{attrs:{size:"14"}}):a._e(),a._v(" "),"user-edit"===a.selected.icon?t("edit2-icon",{attrs:{size:"14"}}):a._e()],1):a._e(),a._v(" "),t("span",{staticClass:"option-value"},[a._v(a._s(a.selected.label))])]):a._e(),a._v(" "),a.selected?a._e():t("div",{staticClass:"not-selected"},[t("span",{staticClass:"option-value placehoder"},[a._v(a._s(a.placeholder))])]),a._v(" "),t("chevron-down-icon",{staticClass:"chevron",attrs:{size:"19"}})],1),a._v(" "),t("transition",{attrs:{name:"slide-in"}},[a.isOpen?t("ul",{staticClass:"input-options"},a._l(a.options,(function(e,n){return t("li",{key:n,staticClass:"option-item",on:{click:function(t){return a.selectOption(e)}}},[e.icon?t("div",{staticClass:"option-icon"},["user"===e.icon?t("user-icon",{attrs:{size:"14"}}):a._e(),a._v(" "),"user-edit"===e.icon?t("edit2-icon",{attrs:{size:"14"}}):a._e()],1):a._e(),a._v(" "),t("span",{staticClass:"option-value"},[a._v(a._s(e.label))])])})),0):a._e()])],1)}),[],!1,null,"14b1d6a3",null);e.a=d.exports},"8L2t":function(a,e,t){"use strict";var n=t("YQqd");t.n(n).a},BelR:function(a,e,t){var n=t("2jUW");"string"==typeof n&&(n=[[a.i,n,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(n,o);n.locals&&(a.exports=n.locals)},"D+dh":function(a,e,t){"use strict";var n=t("CjXH"),o={name:"ImageInput",props:["image","error"],components:{ImageIcon:n.C,XIcon:n.hb},data:function(){return{imagePreview:void 0}},computed:{isData:function(){return void 0!==this.imagePreview&&""!==this.imagePreview}},methods:{resetImage:function(){this.imagePreview=void 0,this.$emit("input",void 0)},showImagePreview:function(a){var e=this,t=a.target.files[0].name,n=t.substring(t.lastIndexOf(".")+1).toLowerCase();if(["png","jpg","jpeg","svg"].includes(n)){var o=a.target.files[0],i=new FileReader;i.onload=function(){return e.imagePreview=i.result},i.readAsDataURL(o),this.$emit("input",a.target.files[0])}else alert(this.$t("validation_errors.wrong_image"))}},created:function(){this.image&&(this.imagePreview=this.image)}},i=(t("Obrk"),t("KHd+")),d=Object(i.a)(o,(function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("div",{staticClass:"dropzone",class:{"is-error":a.error}},[a.imagePreview?t("div",{staticClass:"reset-image",on:{click:a.resetImage}},[t("x-icon",{staticClass:"close-icon text-theme",attrs:{size:"14"}})],1):a._e(),a._v(" "),t("input",{ref:"file",staticClass:"dummy",attrs:{type:"file"},on:{change:function(e){return a.showImagePreview(e)}}}),a._v(" "),a.imagePreview?t("img",{ref:"image",staticClass:"image-preview",attrs:{src:a.imagePreview}}):a._e(),a._v(" "),t("div",{directives:[{name:"show",rawName:"v-show",value:!a.isData,expression:"! isData"}],staticClass:"dropzone-message"},[t("image-icon",{staticClass:"icon-upload text-theme",attrs:{size:"28"}}),a._v(" "),t("span",{staticClass:"dropzone-title"},[a._v("\n            "+a._s(a.$t("input_image.title"))+"\n        ")]),a._v(" "),t("span",{staticClass:"dropzone-description"},[a._v("\n            "+a._s(a.$t("input_image.supported"))+"\n        ")])],1)])}),[],!1,null,"5d141a68",null);e.a=d.exports},"F+Qg":function(a,e,t){"use strict";var n=t("3GDE");t.n(n).a},Obrk:function(a,e,t){"use strict";var n=t("BelR");t.n(n).a},UqlB:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".select[data-v-14b1d6a3] {\n  position: relative;\n  -webkit-user-select: none;\n     -moz-user-select: none;\n      -ms-user-select: none;\n          user-select: none;\n  width: 100%;\n}\n.input-options[data-v-14b1d6a3] {\n  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);\n  background: white;\n  border-radius: 8px;\n  position: absolute;\n  overflow: hidden;\n  top: 65px;\n  left: 0;\n  right: 0;\n  z-index: 9;\n  max-height: 295px;\n  overflow-y: auto;\n}\n.input-options .option-item[data-v-14b1d6a3] {\n  padding: 13px 20px;\n  display: block;\n  cursor: pointer;\n}\n.input-options .option-item[data-v-14b1d6a3]:hover {\n  color: #00BC7E;\n  background: #f4f5f6;\n}\n.input-options .option-item[data-v-14b1d6a3]:last-child {\n  border-bottom: none;\n}\n.input-area[data-v-14b1d6a3] {\n  border-width: 1px;\n  border-style: solid;\n  border-color: transparent;\n  justify-content: space-between;\n  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);\n  transition: 150ms all ease;\n  align-items: center;\n  border-radius: 8px;\n  padding: 13px 20px;\n  display: flex;\n  outline: 0;\n  width: 100%;\n  cursor: pointer;\n}\n.input-area .chevron[data-v-14b1d6a3] {\n  transition: 150ms all ease;\n}\n.input-area.is-active .chevron[data-v-14b1d6a3] {\n  transform: rotate(180deg);\n}\n.input-area.is-error[data-v-14b1d6a3] {\n  border-color: #fd397a;\n  box-shadow: 0 0 7px rgba(253, 57, 122, 0.3);\n}\n.option-icon[data-v-14b1d6a3] {\n  width: 20px;\n  display: inline-block;\n  font-size: 0.625em;\n}\n.option-icon svg[data-v-14b1d6a3] {\n  margin-top: -4px;\n  vertical-align: middle;\n}\n.option-value[data-v-14b1d6a3] {\n  font-size: 0.875em;\n  font-weight: 700;\n  width: 100%;\n  vertical-align: middle;\n}\n.option-value.placehoder[data-v-14b1d6a3] {\n  color: rgba(27, 37, 57, 0.5);\n}\n.slide-in-enter-active[data-v-14b1d6a3] {\n  transition: all 150ms ease;\n}\n.slide-in-enter[data-v-14b1d6a3] {\n  opacity: 0;\n  transform: translateY(-50px);\n}\n@media (prefers-color-scheme: dark) {\n.input-area[data-v-14b1d6a3] {\n    background: #1e2024;\n    border-color: #1e2024;\n}\n.popup-wrapper .input-area[data-v-14b1d6a3] {\n    background: #25272c;\n}\n.input-options[data-v-14b1d6a3] {\n    background: #1e2024;\n}\n.input-options .option-item[data-v-14b1d6a3] {\n    border-bottom: none;\n}\n.input-options .option-item[data-v-14b1d6a3]:hover {\n    background: #2a2c32;\n}\n.input-options .option-item:hover .option-icon path[data-v-14b1d6a3], .input-options .option-item:hover .option-icon circle[data-v-14b1d6a3] {\n    color: inherit;\n}\n.input-options .option-item[data-v-14b1d6a3]:last-child {\n    border-bottom: none;\n}\n.option-value.placehoder[data-v-14b1d6a3] {\n    color: #7d858c;\n}\n}\n",""])},YQqd:function(a,e,t){var n=t("gvpH");"string"==typeof n&&(n=[[a.i,n,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(n,o);n.locals&&(a.exports=n.locals)},YRup:function(a,e,t){var n=t("yilg");"string"==typeof n&&(n=[[a.i,n,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(n,o);n.locals&&(a.exports=n.locals)},gvpH:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".progress-bar[data-v-6ec2be7a] {\n  width: 100%;\n  height: 5px;\n  background: #f4f5f6;\n  margin-top: 6px;\n  border-radius: 10px;\n}\n.progress-bar span[data-v-6ec2be7a] {\n  display: block;\n  height: 100%;\n  border-radius: 10px;\n  max-width: 100%;\n}\n@media (prefers-color-scheme: dark) {\n.progress-bar[data-v-6ec2be7a] {\n    background: #1e2024;\n}\n}\n@media only screen and (min-width: 680px) and (prefers-color-scheme: dark) {\n.progress-bar[data-v-6ec2be7a] {\n    background: #1e2024;\n}\n}\n",""])},hw5j:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".detail-storage-item[data-v-d9e0536e] {\n  margin-bottom: 35px;\n}\n.detail-storage-item.disk .icon path[data-v-d9e0536e], .detail-storage-item.disk .icon line[data-v-d9e0536e], .detail-storage-item.disk .icon polyline[data-v-d9e0536e], .detail-storage-item.disk .icon rect[data-v-d9e0536e], .detail-storage-item.disk .icon circle[data-v-d9e0536e], .detail-storage-item.disk .icon polygon[data-v-d9e0536e] {\n  stroke: #1B2539;\n}\n.detail-storage-item.disk .storage-progress[data-v-d9e0536e] span {\n  background: #1B2539;\n}\n.detail-storage-item.images .icon path[data-v-d9e0536e], .detail-storage-item.images .icon line[data-v-d9e0536e], .detail-storage-item.images .icon polyline[data-v-d9e0536e], .detail-storage-item.images .icon rect[data-v-d9e0536e], .detail-storage-item.images .icon circle[data-v-d9e0536e], .detail-storage-item.images .icon polygon[data-v-d9e0536e] {\n  stroke: #9D66FE;\n}\n.detail-storage-item.images .storage-progress[data-v-d9e0536e] span {\n  background: #9D66FE;\n}\n.detail-storage-item.videos .icon path[data-v-d9e0536e], .detail-storage-item.videos .icon line[data-v-d9e0536e], .detail-storage-item.videos .icon polyline[data-v-d9e0536e], .detail-storage-item.videos .icon rect[data-v-d9e0536e], .detail-storage-item.videos .icon circle[data-v-d9e0536e], .detail-storage-item.videos .icon polygon[data-v-d9e0536e] {\n  stroke: #FFBD2D;\n}\n.detail-storage-item.videos .storage-progress[data-v-d9e0536e] span {\n  background: #FFBD2D;\n}\n.detail-storage-item.audios .icon path[data-v-d9e0536e], .detail-storage-item.audios .icon line[data-v-d9e0536e], .detail-storage-item.audios .icon polyline[data-v-d9e0536e], .detail-storage-item.audios .icon rect[data-v-d9e0536e], .detail-storage-item.audios .icon circle[data-v-d9e0536e], .detail-storage-item.audios .icon polygon[data-v-d9e0536e] {\n  stroke: #FE66A1;\n}\n.detail-storage-item.audios .storage-progress[data-v-d9e0536e] span {\n  background: #FE66A1;\n}\n.detail-storage-item.documents .icon path[data-v-d9e0536e], .detail-storage-item.documents .icon line[data-v-d9e0536e], .detail-storage-item.documents .icon polyline[data-v-d9e0536e], .detail-storage-item.documents .icon rect[data-v-d9e0536e], .detail-storage-item.documents .icon circle[data-v-d9e0536e], .detail-storage-item.documents .icon polygon[data-v-d9e0536e] {\n  stroke: #FE6057;\n}\n.detail-storage-item.documents .storage-progress[data-v-d9e0536e] span {\n  background: #FE6057;\n}\n.detail-storage-item.others .icon path[data-v-d9e0536e], .detail-storage-item.others .icon line[data-v-d9e0536e], .detail-storage-item.others .icon polyline[data-v-d9e0536e], .detail-storage-item.others .icon rect[data-v-d9e0536e], .detail-storage-item.others .icon circle[data-v-d9e0536e], .detail-storage-item.others .icon polygon[data-v-d9e0536e] {\n  stroke: #1B2539;\n}\n.detail-storage-item.others .storage-progress[data-v-d9e0536e] span {\n  background: #1B2539;\n}\n.header-storage-item[data-v-d9e0536e] {\n  display: flex;\n  align-items: flex-start;\n  margin-bottom: 10px;\n}\n.header-storage-item .icon[data-v-d9e0536e] {\n  width: 35px;\n}\n.header-storage-item .type[data-v-d9e0536e] {\n  font-size: 0.9375em;\n  color: #1B2539;\n}\n.header-storage-item .total-size[data-v-d9e0536e] {\n  font-size: 0.625em;\n  display: block;\n  color: rgba(27, 37, 57, 0.7);\n}\n@media (prefers-color-scheme: dark) {\n.header-storage-item .type[data-v-d9e0536e] {\n    color: #bec6cf;\n}\n.header-storage-item .total-size[data-v-d9e0536e] {\n    color: #7d858c;\n}\n.detail-storage-item.others .icon path[data-v-d9e0536e], .detail-storage-item.others .icon line[data-v-d9e0536e], .detail-storage-item.others .icon polyline[data-v-d9e0536e], .detail-storage-item.others .icon rect[data-v-d9e0536e], .detail-storage-item.others .icon circle[data-v-d9e0536e], .detail-storage-item.others .icon polygon[data-v-d9e0536e], .detail-storage-item.disk .icon path[data-v-d9e0536e], .detail-storage-item.disk .icon line[data-v-d9e0536e], .detail-storage-item.disk .icon polyline[data-v-d9e0536e], .detail-storage-item.disk .icon rect[data-v-d9e0536e], .detail-storage-item.disk .icon circle[data-v-d9e0536e], .detail-storage-item.disk .icon polygon[data-v-d9e0536e] {\n    stroke: #41454e;\n}\n.detail-storage-item.others .storage-progress[data-v-d9e0536e] span, .detail-storage-item.disk .storage-progress[data-v-d9e0536e] span {\n    background: #41454e;\n}\n}\n",""])},jCWY:function(a,e,t){"use strict";var n=t("+v8A");t.n(n).a},l39B:function(a,e,t){"use strict";var n=t("YRup");t.n(n).a},xnZf:function(a,e,t){"use strict";var n=t("+Pqb"),o=t("CjXH"),i={name:"StorageItemDetail",props:["percentage","title","type","used"],components:{HardDriveIcon:o.A,FileTextIcon:o.w,ProgressBar:n.a,MusicIcon:o.N,VideoIcon:o.gb,ImageIcon:o.C,FileIcon:o.v}},d=(t("jCWY"),t("KHd+")),r=Object(d.a)(i,(function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("article",{staticClass:"detail-storage-item",class:a.type},[t("div",{staticClass:"header-storage-item"},[t("div",{staticClass:"icon"},["images"==a.type?t("image-icon",{attrs:{size:"23"}}):a._e(),a._v(" "),"videos"==a.type?t("video-icon",{attrs:{size:"23"}}):a._e(),a._v(" "),"audios"==a.type?t("music-icon",{attrs:{size:"23"}}):a._e(),a._v(" "),"documents"==a.type?t("file-text-icon",{attrs:{size:"23"}}):a._e(),a._v(" "),"others"==a.type?t("file-icon",{attrs:{size:"23"}}):a._e(),a._v(" "),"disk"==a.type?t("hard-drive-icon",{attrs:{size:"23"}}):a._e()],1),a._v(" "),t("div",{staticClass:"title"},[t("b",{staticClass:"type"},[a._v(a._s(a.title))]),a._v(" "),t("span",{staticClass:"total-size"},[a._v(a._s(a.used))])])]),a._v(" "),t("ProgressBar",{staticClass:"storage-progress",attrs:{progress:a.percentage}})],1)}),[],!1,null,"d9e0536e",null);e.a=r.exports},yilg:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,'.form[data-v-b1d9fdac] {\n  max-width: 700px;\n}\n.form.inline-form[data-v-b1d9fdac] {\n  display: flex;\n  position: relative;\n  justify-content: center;\n  margin: 0 auto;\n}\n.form.inline-form .input-wrapper[data-v-b1d9fdac] {\n  position: relative;\n}\n.form.inline-form .input-wrapper .error-message[data-v-b1d9fdac] {\n  position: absolute;\n  left: 0;\n  bottom: -25px;\n}\n.form.block-form .wrapper-inline[data-v-b1d9fdac] {\n  display: flex;\n  margin: 0 -15px;\n}\n.form.block-form .wrapper-inline .block-wrapper[data-v-b1d9fdac] {\n  width: 100%;\n  padding: 0 15px;\n}\n.form.block-form .block-wrapper[data-v-b1d9fdac] {\n  margin-bottom: 32px;\n}\n.form.block-form .block-wrapper label[data-v-b1d9fdac] {\n  font-size: 0.875em;\n  color: rgba(27, 37, 57, 0.8);\n  font-weight: 700;\n  display: block;\n  margin-bottom: 7px;\n  text-align: left;\n}\n.form.block-form .block-wrapper[data-v-b1d9fdac]:last-child {\n  margin-bottom: 0;\n}\n.form.block-form .button[data-v-b1d9fdac] {\n  margin-top: 50px;\n}\n.form .inline-wrapper[data-v-b1d9fdac] {\n  display: flex;\n  align-items: center;\n  justify-content: space-between;\n}\n.form .inline-wrapper .switch-label .input-help[data-v-b1d9fdac] {\n  padding-top: 0;\n}\n.form .inline-wrapper .switch-label .input-label[data-v-b1d9fdac] {\n  font-weight: 700;\n  color: #1B2539;\n  font-size: 1em;\n  margin-bottom: 5px;\n}\n.form .input-help[data-v-b1d9fdac] {\n  font-size: 0.75em;\n  color: rgba(27, 37, 57, 0.7);\n  line-height: 1.35;\n  padding-top: 10px;\n  display: block;\n}\n.single-line-form[data-v-b1d9fdac] {\n  display: flex;\n}\n.single-line-form .submit-button[data-v-b1d9fdac] {\n  margin-left: 20px;\n}\n.error-message[data-v-b1d9fdac] {\n  font-size: 0.875em;\n  color: #fd397a;\n  padding-top: 5px;\n  display: block;\n  text-align: left;\n}\ntextarea[data-v-b1d9fdac] {\n  width: 100%;\n}\ninput[type="color"][data-v-b1d9fdac] {\n  width: 38px;\n  height: 40px;\n  border: none;\n  outline: none;\n  background: none;\n}\ntextarea[data-v-b1d9fdac],\ninput[type="password"][data-v-b1d9fdac],\ninput[type="text"][data-v-b1d9fdac],\ninput[type="number"][data-v-b1d9fdac],\ninput[type="email"][data-v-b1d9fdac] {\n  border: 1px solid transparent;\n  transition: 150ms all ease;\n  font-size: 1em;\n  border-radius: 8px;\n  padding: 13px 20px;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  font-weight: 700;\n  outline: 0;\n  width: 100%;\n  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);\n}\ntextarea.is-error[data-v-b1d9fdac],\ninput[type="password"].is-error[data-v-b1d9fdac],\ninput[type="text"].is-error[data-v-b1d9fdac],\ninput[type="number"].is-error[data-v-b1d9fdac],\ninput[type="email"].is-error[data-v-b1d9fdac] {\n  border-color: #fd397a;\n}\ntextarea[data-v-b1d9fdac]::-webkit-input-placeholder, input[type="password"][data-v-b1d9fdac]::-webkit-input-placeholder, input[type="text"][data-v-b1d9fdac]::-webkit-input-placeholder, input[type="number"][data-v-b1d9fdac]::-webkit-input-placeholder, input[type="email"][data-v-b1d9fdac]::-webkit-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-b1d9fdac]::-moz-placeholder, input[type="password"][data-v-b1d9fdac]::-moz-placeholder, input[type="text"][data-v-b1d9fdac]::-moz-placeholder, input[type="number"][data-v-b1d9fdac]::-moz-placeholder, input[type="email"][data-v-b1d9fdac]::-moz-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-b1d9fdac]:-ms-input-placeholder, input[type="password"][data-v-b1d9fdac]:-ms-input-placeholder, input[type="text"][data-v-b1d9fdac]:-ms-input-placeholder, input[type="number"][data-v-b1d9fdac]:-ms-input-placeholder, input[type="email"][data-v-b1d9fdac]:-ms-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-b1d9fdac]::-ms-input-placeholder, input[type="password"][data-v-b1d9fdac]::-ms-input-placeholder, input[type="text"][data-v-b1d9fdac]::-ms-input-placeholder, input[type="number"][data-v-b1d9fdac]::-ms-input-placeholder, input[type="email"][data-v-b1d9fdac]::-ms-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-b1d9fdac]::placeholder,\ninput[type="password"][data-v-b1d9fdac]::placeholder,\ninput[type="text"][data-v-b1d9fdac]::placeholder,\ninput[type="number"][data-v-b1d9fdac]::placeholder,\ninput[type="email"][data-v-b1d9fdac]::placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[disabled][data-v-b1d9fdac],\ninput[type="password"][disabled][data-v-b1d9fdac],\ninput[type="text"][disabled][data-v-b1d9fdac],\ninput[type="number"][disabled][data-v-b1d9fdac],\ninput[type="email"][disabled][data-v-b1d9fdac] {\n  background: white;\n  color: rgba(27, 37, 57, 0.8);\n  -webkit-text-fill-color: rgba(27, 37, 57, 0.8);\n  opacity: 1;\n  cursor: not-allowed;\n}\n.additional-link[data-v-b1d9fdac] {\n  font-size: 1em;\n  margin-top: 50px;\n  display: block;\n}\n.additional-link b[data-v-b1d9fdac], .additional-link a[data-v-b1d9fdac] {\n  cursor: pointer;\n}\n.additional-link b[data-v-b1d9fdac]:hover, .additional-link a[data-v-b1d9fdac]:hover {\n  text-decoration: underline;\n}\n@media only screen and (max-width: 1024px) {\n.form[data-v-b1d9fdac] {\n    max-width: 100%;\n}\n}\n@media only screen and (max-width: 960px) {\n.form .button[data-v-b1d9fdac] {\n    margin-top: 20px;\n    width: 100%;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form input[data-v-b1d9fdac], .form textarea[data-v-b1d9fdac] {\n    width: 100%;\n    min-width: 100%;\n}\n.form.block-form .block-wrapper[data-v-b1d9fdac] {\n    display: block;\n}\n.form.block-form .block-wrapper label[data-v-b1d9fdac] {\n    width: 100%;\n    padding-right: 0;\n    display: block;\n    margin-bottom: 7px;\n    text-align: left !important;\n    font-size: 0.875em;\n    padding-top: 0;\n}\n.form.block-form .button[data-v-b1d9fdac] {\n    margin-top: 25px;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form.inline-form[data-v-b1d9fdac] {\n    display: block;\n}\n.form.inline-form .input-wrapper .error-message[data-v-b1d9fdac] {\n    position: relative;\n    bottom: 0;\n}\n.form .button[data-v-b1d9fdac] {\n    padding: 14px 32px;\n}\n.single-line-form[data-v-b1d9fdac] {\n    display: block;\n}\n.single-line-form .submit-button[data-v-b1d9fdac] {\n    margin-left: 0;\n    margin-top: 20px;\n    width: 100%;\n}\ntextarea[data-v-b1d9fdac],\n  input[type="password"][data-v-b1d9fdac],\n  input[type="number"][data-v-b1d9fdac],\n  input[type="text"][data-v-b1d9fdac],\n  input[type="email"][data-v-b1d9fdac] {\n    padding: 14px 20px;\n}\n}\n@media only screen and (max-width: 690px) {\n.form.block-form .wrapper-inline[data-v-b1d9fdac] {\n    display: block;\n}\n}\n@media (prefers-color-scheme: dark) {\n.form .input-help[data-v-b1d9fdac] {\n    color: #7d858c;\n}\n.form.block-form .block-wrapper label[data-v-b1d9fdac] {\n    color: #bec6cf;\n}\n.form .inline-wrapper .switch-label .input-label[data-v-b1d9fdac] {\n    color: #bec6cf;\n}\ntextarea[data-v-b1d9fdac],\n  input[type="password"][data-v-b1d9fdac],\n  input[type="text"][data-v-b1d9fdac],\n  input[type="number"][data-v-b1d9fdac],\n  input[type="email"][data-v-b1d9fdac] {\n    border-color: #1e2024;\n    background: #1e2024;\n    color: #bec6cf;\n}\ntextarea[data-v-b1d9fdac]::-webkit-input-placeholder, input[type="password"][data-v-b1d9fdac]::-webkit-input-placeholder, input[type="text"][data-v-b1d9fdac]::-webkit-input-placeholder, input[type="number"][data-v-b1d9fdac]::-webkit-input-placeholder, input[type="email"][data-v-b1d9fdac]::-webkit-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-b1d9fdac]::-moz-placeholder, input[type="password"][data-v-b1d9fdac]::-moz-placeholder, input[type="text"][data-v-b1d9fdac]::-moz-placeholder, input[type="number"][data-v-b1d9fdac]::-moz-placeholder, input[type="email"][data-v-b1d9fdac]::-moz-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-b1d9fdac]:-ms-input-placeholder, input[type="password"][data-v-b1d9fdac]:-ms-input-placeholder, input[type="text"][data-v-b1d9fdac]:-ms-input-placeholder, input[type="number"][data-v-b1d9fdac]:-ms-input-placeholder, input[type="email"][data-v-b1d9fdac]:-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-b1d9fdac]::-ms-input-placeholder, input[type="password"][data-v-b1d9fdac]::-ms-input-placeholder, input[type="text"][data-v-b1d9fdac]::-ms-input-placeholder, input[type="number"][data-v-b1d9fdac]::-ms-input-placeholder, input[type="email"][data-v-b1d9fdac]::-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-b1d9fdac]::placeholder,\n  input[type="password"][data-v-b1d9fdac]::placeholder,\n  input[type="text"][data-v-b1d9fdac]::placeholder,\n  input[type="number"][data-v-b1d9fdac]::placeholder,\n  input[type="email"][data-v-b1d9fdac]::placeholder {\n    color: #7d858c;\n}\ntextarea[disabled][data-v-b1d9fdac],\n  input[type="password"][disabled][data-v-b1d9fdac],\n  input[type="text"][disabled][data-v-b1d9fdac],\n  input[type="number"][disabled][data-v-b1d9fdac],\n  input[type="email"][disabled][data-v-b1d9fdac] {\n    background: #1e2024;\n    color: rgba(125, 133, 140, 0.8);\n    -webkit-text-fill-color: rgba(125, 133, 140, 0.8);\n}\n.popup-wrapper textarea[data-v-b1d9fdac],\n  .popup-wrapper input[type="password"][data-v-b1d9fdac],\n  .popup-wrapper input[type="text"][data-v-b1d9fdac],\n  .popup-wrapper input[type="number"][data-v-b1d9fdac],\n  .popup-wrapper input[type="email"][data-v-b1d9fdac] {\n    background: #25272c;\n}\n}\n.block-form[data-v-b1d9fdac] {\n  max-width: 100%;\n}\n',""])}}]);