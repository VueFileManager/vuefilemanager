(window.webpackJsonp=window.webpackJsonp||[]).push([[8],{"+Pqb":function(a,e,t){"use strict";var i={name:"ProgressBar",props:["progress"]},o=(t("woIv"),t("KHd+")),r=Object(o.a)(i,(function(){var a=this.$createElement,e=this._self._c||a;return e("div",{staticClass:"progress-bar"},[e("span",{style:{width:this.progress+"%"}})])}),[],!1,null,"f372b280",null);e.a=r.exports},"+t0u":function(a,e,t){var i=t("QO4y");"string"==typeof i&&(i=[[a.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(a.exports=i.locals)},"13Td":function(a,e,t){"use strict";var i={name:"SetupBox",props:["title","description","theme"]},o=(t("jS71"),t("KHd+")),r=Object(o.a)(i,(function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("div",{staticClass:"setup-box",class:a.theme},[t("b",{staticClass:"title"},[a._v(a._s(a.title))]),a._v(" "),t("p",{staticClass:"description"},[a._v(a._s(a.description))]),a._v(" "),a._t("default")],2)}),[],!1,null,"664a78dc",null);e.a=r.exports},"5pbA":function(a,e,t){"use strict";var i=t("+t0u");t.n(i).a},"6gqG":function(a,e,t){var i=t("ydEr");"string"==typeof i&&(i=[[a.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(a.exports=i.locals)},"7OGm":function(a,e,t){"use strict";var i=t("6gqG");t.n(i).a},A7XW:function(a,e,t){var i=t("xo5e");"string"==typeof i&&(i=[[a.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(a.exports=i.locals)},Ad51:function(a,e,t){"use strict";var i=t("A7XW");t.n(i).a},"D+dh":function(a,e,t){"use strict";var i=t("CjXH"),o={name:"ImageInput",props:["image","error"],components:{ImageIcon:i.B,XIcon:i.db},data:function(){return{imagePreview:void 0}},computed:{isData:function(){return void 0!==this.imagePreview&&""!==this.imagePreview}},methods:{resetImage:function(){this.imagePreview=void 0,this.$emit("input",void 0)},showImagePreview:function(a){var e=this,t=a.target.files[0].name,i=t.substring(t.lastIndexOf(".")+1).toLowerCase();if(["png","jpg","jpeg","svg"].includes(i)){var o=a.target.files[0],r=new FileReader;r.onload=function(){return e.imagePreview=r.result},r.readAsDataURL(o),this.$emit("input",a.target.files[0])}else alert(this.$t("validation_errors.wrong_image"))}},created:function(){this.image&&(this.imagePreview=this.image)}},r=(t("w9z4"),t("KHd+")),s=Object(r.a)(o,(function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("div",{staticClass:"dropzone",class:{"is-error":a.error}},[a.imagePreview?t("div",{staticClass:"reset-image",on:{click:a.resetImage}},[t("x-icon",{staticClass:"close-icon",attrs:{size:"14"}})],1):a._e(),a._v(" "),t("input",{ref:"file",staticClass:"dummy",attrs:{type:"file"},on:{change:function(e){return a.showImagePreview(e)}}}),a._v(" "),a.imagePreview?t("img",{ref:"image",staticClass:"image-preview",attrs:{src:a.imagePreview}}):a._e(),a._v(" "),t("div",{directives:[{name:"show",rawName:"v-show",value:!a.isData,expression:"! isData"}],staticClass:"dropzone-message"},[t("image-icon",{staticClass:"icon-upload",attrs:{size:"28"}}),a._v(" "),t("span",{staticClass:"dropzone-title"},[a._v("\n            "+a._s(a.$t("input_image.title"))+"\n        ")]),a._v(" "),t("span",{staticClass:"dropzone-description"},[a._v("\n            "+a._s(a.$t("input_image.supported"))+"\n        ")])],1)])}),[],!1,null,"eb0cae00",null);e.a=s.exports},"F12+":function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".progress-bar[data-v-f372b280]{width:100%;height:5px;background:#f4f5f6;margin-top:6px;border-radius:10px}.progress-bar span[data-v-f372b280]{background:#00bc7e;display:block;height:100%;border-radius:10px;max-width:100%}@media (prefers-color-scheme:dark){.progress-bar[data-v-f372b280]{background:#1e2024}}@media only screen and (min-width:680px) and (prefers-color-scheme:dark){.progress-bar[data-v-f372b280]{background:#1e2024}}",""])},FEcZ:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".setup-box[data-v-664a78dc]{padding:20px;border-radius:8px;margin-bottom:30px}.setup-box .title[data-v-664a78dc]{font-size:1.3125em;margin-bottom:5px;display:block;font-weight:700}.setup-box .description[data-v-664a78dc]{font-size:.9375em;line-height:1.5;margin-bottom:20px}.setup-box.base[data-v-664a78dc],.setup-box.danger[data-v-664a78dc]{background:#f4f5f6}.setup-box.danger .title[data-v-664a78dc]{color:#fd397a}.setup-box[data-v-664a78dc] .input-area,.setup-box[data-v-664a78dc] input .input-area,.setup-box[data-v-664a78dc] input[type=number],.setup-box[data-v-664a78dc] input[type=text]{background:#fff}.setup-box[data-v-664a78dc] .form{margin-top:20px}.setup-box[data-v-664a78dc] .form.block-form{max-width:450px}.setup-box[data-v-664a78dc] .form.block-form .single-line-form{display:flex}.setup-box[data-v-664a78dc] .form.block-form .single-line-form .submit-button{margin-left:20px}@media only screen and (max-width:960px){.setup-box[data-v-664a78dc] .form.block-form{max-width:100%}.setup-box[data-v-664a78dc] .form input{min-width:0}}@media only screen and (max-width:690px){.setup-box[data-v-664a78dc]{padding:15px}.setup-box .title[data-v-664a78dc]{font-size:1.0625em;margin-bottom:10px}.setup-box .description[data-v-664a78dc]{font-size:.875em}.setup-box[data-v-664a78dc] .form.block-form .single-line-form{display:block}.setup-box[data-v-664a78dc] .form.block-form .single-line-form .submit-button{margin-left:0;margin-top:10px}}@media (prefers-color-scheme:dark){.setup-box.base[data-v-664a78dc],.setup-box.danger[data-v-664a78dc]{background:#1e2024}.setup-box[data-v-664a78dc] .input-area,.setup-box[data-v-664a78dc] input .input-area,.setup-box[data-v-664a78dc] input[type=number],.setup-box[data-v-664a78dc] input[type=text]{background:#131414}}",""])},IS7u:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".dropzone[data-v-eb0cae00]{border:1px dashed #a1abc2;border-radius:8px;position:relative;text-align:center;display:flex;align-items:center;min-height:175px}.dropzone.is-error[data-v-eb0cae00]{border:2px dashed rgba(253,57,122,.3)}.dropzone.is-error .dropzone-title[data-v-eb0cae00]{color:#fd397a}.dropzone.is-error .icon-upload circle[data-v-eb0cae00],.dropzone.is-error .icon-upload polyline[data-v-eb0cae00],.dropzone.is-error .icon-upload rect[data-v-eb0cae00]{stroke:#fd397a}.dropzone input[type=file][data-v-eb0cae00]{opacity:0;position:absolute;top:0;left:0;right:0;bottom:0;z-index:1;width:100%;cursor:pointer}.dropzone .image-preview[data-v-eb0cae00]{position:absolute;width:100%;height:100%;-o-object-fit:contain;object-fit:contain;left:0;padding:25px;display:block}.dropzone .image-preview.fit-image[data-v-eb0cae00]{-o-object-fit:cover;object-fit:cover;border-radius:12px;overflow:hidden}.dropzone .dropzone-message[data-v-eb0cae00]{padding:50px 0;width:100%}.dropzone .dropzone-message .icon-upload circle[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload polyline[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload rect[data-v-eb0cae00]{stroke:#00bc7e}.dropzone .dropzone-message .dropzone-title[data-v-eb0cae00]{font-size:1em;font-weight:700;display:block}.dropzone .dropzone-message .dropzone-description[data-v-eb0cae00]{color:rgba(27,37,57,.7);font-size:.75em}.dropzone .reset-image[data-v-eb0cae00]{z-index:2;background:#fff;border-radius:50px;display:block;position:absolute;right:0;top:0;cursor:pointer;transform:translateY(-50%) translateX(50%);padding:0 4px;box-shadow:0 1px 5px rgba(0,0,0,.12)}.dropzone .reset-image .close-icon[data-v-eb0cae00]{vertical-align:middle}.dropzone .reset-image .close-icon line path[data-v-eb0cae00]{fill:#1b2539}@media (prefers-color-scheme:dark){.dropzone[data-v-eb0cae00]{border-color:hsla(0,0%,100%,.2)}.dropzone .dropzone-message .icon-upload line[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload path[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload polyline[data-v-eb0cae00]{stroke:#00bc7e}.dropzone .dropzone-message .dropzone-description[data-v-eb0cae00]{color:#7d858c}}",""])},Idvm:function(a,e,t){var i=t("lig4");"string"==typeof i&&(i=[[a.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(a.exports=i.locals)},KnjL:function(a,e,t){"use strict";var i={name:"InfoBox",props:["type"]},o=(t("7OGm"),t("KHd+")),r=Object(o.a)(i,(function(){var a=this.$createElement;return(this._self._c||a)("div",{staticClass:"info-box",class:this.type},[this._t("default")],2)}),[],!1,null,"bf43be5e",null);e.a=r.exports},LE5O:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".detail-storage-item[data-v-56af1b6e]{margin-bottom:35px}.detail-storage-item.disk .icon circle[data-v-56af1b6e],.detail-storage-item.disk .icon line[data-v-56af1b6e],.detail-storage-item.disk .icon path[data-v-56af1b6e],.detail-storage-item.disk .icon polygon[data-v-56af1b6e],.detail-storage-item.disk .icon polyline[data-v-56af1b6e],.detail-storage-item.disk .icon rect[data-v-56af1b6e]{stroke:#1b2539}.detail-storage-item.disk .storage-progress[data-v-56af1b6e] span{background:#1b2539}.detail-storage-item.images .icon circle[data-v-56af1b6e],.detail-storage-item.images .icon line[data-v-56af1b6e],.detail-storage-item.images .icon path[data-v-56af1b6e],.detail-storage-item.images .icon polygon[data-v-56af1b6e],.detail-storage-item.images .icon polyline[data-v-56af1b6e],.detail-storage-item.images .icon rect[data-v-56af1b6e]{stroke:#9d66fe}.detail-storage-item.images .storage-progress[data-v-56af1b6e] span{background:#9d66fe}.detail-storage-item.videos .icon circle[data-v-56af1b6e],.detail-storage-item.videos .icon line[data-v-56af1b6e],.detail-storage-item.videos .icon path[data-v-56af1b6e],.detail-storage-item.videos .icon polygon[data-v-56af1b6e],.detail-storage-item.videos .icon polyline[data-v-56af1b6e],.detail-storage-item.videos .icon rect[data-v-56af1b6e]{stroke:#ffbd2d}.detail-storage-item.videos .storage-progress[data-v-56af1b6e] span{background:#ffbd2d}.detail-storage-item.audios .icon circle[data-v-56af1b6e],.detail-storage-item.audios .icon line[data-v-56af1b6e],.detail-storage-item.audios .icon path[data-v-56af1b6e],.detail-storage-item.audios .icon polygon[data-v-56af1b6e],.detail-storage-item.audios .icon polyline[data-v-56af1b6e],.detail-storage-item.audios .icon rect[data-v-56af1b6e]{stroke:#fe66a1}.detail-storage-item.audios .storage-progress[data-v-56af1b6e] span{background:#fe66a1}.detail-storage-item.documents .icon circle[data-v-56af1b6e],.detail-storage-item.documents .icon line[data-v-56af1b6e],.detail-storage-item.documents .icon path[data-v-56af1b6e],.detail-storage-item.documents .icon polygon[data-v-56af1b6e],.detail-storage-item.documents .icon polyline[data-v-56af1b6e],.detail-storage-item.documents .icon rect[data-v-56af1b6e]{stroke:#fe6057}.detail-storage-item.documents .storage-progress[data-v-56af1b6e] span{background:#fe6057}.detail-storage-item.others .icon circle[data-v-56af1b6e],.detail-storage-item.others .icon line[data-v-56af1b6e],.detail-storage-item.others .icon path[data-v-56af1b6e],.detail-storage-item.others .icon polygon[data-v-56af1b6e],.detail-storage-item.others .icon polyline[data-v-56af1b6e],.detail-storage-item.others .icon rect[data-v-56af1b6e]{stroke:#1b2539}.detail-storage-item.others .storage-progress[data-v-56af1b6e] span{background:#1b2539}.header-storage-item[data-v-56af1b6e]{display:flex;align-items:flex-start;margin-bottom:10px}.header-storage-item .icon[data-v-56af1b6e]{width:35px}.header-storage-item .type[data-v-56af1b6e]{font-size:.9375em;color:#1b2539}.header-storage-item .total-size[data-v-56af1b6e]{font-size:.625em;display:block;color:rgba(27,37,57,.7)}@media (prefers-color-scheme:dark){.header-storage-item .type[data-v-56af1b6e]{color:#bec6cf}.header-storage-item .total-size[data-v-56af1b6e]{color:#7d858c}.detail-storage-item.disk .icon circle[data-v-56af1b6e],.detail-storage-item.disk .icon line[data-v-56af1b6e],.detail-storage-item.disk .icon path[data-v-56af1b6e],.detail-storage-item.disk .icon polygon[data-v-56af1b6e],.detail-storage-item.disk .icon polyline[data-v-56af1b6e],.detail-storage-item.disk .icon rect[data-v-56af1b6e],.detail-storage-item.others .icon circle[data-v-56af1b6e],.detail-storage-item.others .icon line[data-v-56af1b6e],.detail-storage-item.others .icon path[data-v-56af1b6e],.detail-storage-item.others .icon polygon[data-v-56af1b6e],.detail-storage-item.others .icon polyline[data-v-56af1b6e],.detail-storage-item.others .icon rect[data-v-56af1b6e]{stroke:#41454e}.detail-storage-item.disk .storage-progress[data-v-56af1b6e] span,.detail-storage-item.others .storage-progress[data-v-56af1b6e] span{background:#41454e}}",""])},QO4y:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,"",""])},VA79:function(a,e,t){var i=t("F12+");"string"==typeof i&&(i=[[a.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(a.exports=i.locals)},Xk6H:function(a,e,t){"use strict";var i=t("Idvm");t.n(i).a},b0xl:function(a,e,t){"use strict";var i=t("oyp5");t.n(i).a},"bN/l":function(a,e,t){var i=t("IS7u");"string"==typeof i&&(i=[[a.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(a.exports=i.locals)},eZ9V:function(a,e,t){"use strict";var i={name:"FormLabel",components:{Edit2Icon:t("CjXH").q}},o=(t("Xk6H"),t("KHd+")),r=Object(o.a)(i,(function(){var a=this.$createElement,e=this._self._c||a;return e("div",{staticClass:"form-label"},[e("edit-2-icon",{staticClass:"icon",attrs:{size:"22"}}),this._v(" "),e("b",{staticClass:"label"},[this._t("default")],2)],1)}),[],!1,null,"c1157a8e",null);e.a=r.exports},gahf:function(a,e,t){"use strict";var i={name:"PageTabGroup"},o=(t("yI2c"),t("KHd+")),r=Object(o.a)(i,(function(){var a=this.$createElement;return(this._self._c||a)("div",{staticClass:"page-tab-group"},[this._t("default")],2)}),[],!1,null,"1bb470e4",null);e.a=r.exports},ijzm:function(a,e,t){"use strict";t.r(e);var i=t("A5+z"),o=t("xnZf"),r=t("gahf"),s=t("4TWA"),n=t("xxrA"),d=t("D+dh"),l=t("eZ9V"),p=t("Nv84"),c=t("13Td"),m=t("qefO"),f=t("KnjL"),u=t("TJPC"),v=t("xCqy"),b=t("vDqi"),g=t.n(b),h={name:"AppOthers",components:{ValidationObserver:i.ValidationObserver,ValidationProvider:i.ValidationProvider,StorageItemDetail:o.a,PageTabGroup:r.a,SwitchInput:n.a,SelectInput:s.a,ImageInput:d.a,ButtonBase:p.a,FormLabel:l.a,SetupBox:c.a,required:u.a,PageTab:m.a,InfoBox:f.a},data:function(){return{isLoading:!0,isFlushingCache:!1,app:void 0}},methods:{flushCache:function(){var a=this;this.isFlushingCache=!0,g.a.get("/api/flush-cache").then((function(){v.a.$emit("toaster",{type:"success",message:"Your cache was successfully deleted."})})).finally((function(){a.isFlushingCache=!1}))}},mounted:function(){var a=this;g.a.get("/api/settings",{params:{column:"contact_email|google_analytics|storage_default|registration|storage_limitation|mimetypes_blacklist"}}).then((function(e){a.isLoading=!1,a.app={contactMail:e.data.contact_email,googleAnalytics:e.data.google_analytics,defaultStorage:e.data.storage_default,userRegistration:parseInt(e.data.registration),storageLimitation:parseInt(e.data.storage_limitation),mimetypesBlacklist:e.data.mimetypes_blacklist}}))}},x=(t("Ad51"),t("KHd+")),_=Object(x.a)(h,(function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("PageTab",{staticClass:"form-fixed-width",attrs:{"is-loading":a.isLoading}},[a.app?t("PageTabGroup",[t("div",{staticClass:"form block-form"},[t("FormLabel",[a._v("\n                "+a._s(a.$t("admin_settings.others.section_user"))+"\n            ")]),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("div",{staticClass:"input-wrapper"},[t("div",{staticClass:"inline-wrapper"},[t("div",{staticClass:"switch-label"},[t("label",{staticClass:"input-label"},[a._v("\n                                "+a._s(a.$t("admin_settings.others.storage_limit"))+":\n                            ")]),a._v(" "),t("small",{staticClass:"input-help",domProps:{innerHTML:a._s(a.$t("admin_settings.others.storage_limit_help"))}})]),a._v(" "),t("SwitchInput",{staticClass:"switch",attrs:{state:a.app.storageLimitation},on:{input:function(e){return a.$updateText("/settings","storage_limitation",a.app.storageLimitation)}},model:{value:a.app.storageLimitation,callback:function(e){a.$set(a.app,"storageLimitation",e)},expression:"app.storageLimitation"}})],1)])]),a._v(" "),a.app.storageLimitation?t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_settings.others.default_storage"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Default Storage Space",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(e){var i=e.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.app.defaultStorage,expression:"app.defaultStorage"}],class:{"is-error":i[0]},attrs:{min:"1",max:"999999999",placeholder:a.$t("admin_settings.others.default_storage_plac"),type:"number"},domProps:{value:a.app.defaultStorage},on:{input:[function(e){e.target.composing||a.$set(a.app,"defaultStorage",e.target.value)},function(e){return a.$updateText("/settings","storage_default",a.app.defaultStorage)}]}}),a._v(" "),i[0]?t("span",{staticClass:"error-message"},[a._v(a._s(i[0]))]):a._e()]}}],null,!1,1344268535)})],1):a._e(),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("div",{staticClass:"input-wrapper"},[t("div",{staticClass:"inline-wrapper"},[t("div",{staticClass:"switch-label"},[t("label",{staticClass:"input-label"},[a._v("\n                                "+a._s(a.$t("admin_settings.others.allow_registration"))+":\n                            ")]),a._v(" "),t("small",{staticClass:"input-help",domProps:{innerHTML:a._s(a.$t("admin_settings.others.allow_registration_help"))}})]),a._v(" "),t("SwitchInput",{staticClass:"switch",attrs:{state:a.app.userRegistration},on:{input:function(e){return a.$updateText("/settings","registration",a.app.userRegistration)}},model:{value:a.app.userRegistration,callback:function(e){a.$set(a.app,"userRegistration",e)},expression:"app.userRegistration"}})],1)])]),a._v(" "),t("FormLabel",{staticClass:"mt-70"},[a._v("\n                "+a._s(a.$t("admin_settings.others.section_others"))+"\n            ")]),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_settings.others.contact_email"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Contact Email",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(e){var i=e.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.app.contactMail,expression:"app.contactMail"}],class:{"is-error":i[0]},attrs:{placeholder:a.$t("admin_settings.others.contact_email_plac"),type:"email"},domProps:{value:a.app.contactMail},on:{input:[function(e){e.target.composing||a.$set(a.app,"contactMail",e.target.value)},function(e){return a.$updateText("/settings","contact_email",a.app.contactMail)}]}}),a._v(" "),i[0]?t("span",{staticClass:"error-message"},[a._v(a._s(i[0]))]):a._e()]}}],null,!1,1389857557)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_settings.others.google_analytics"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Google Analytics Code"},scopedSlots:a._u([{key:"default",fn:function(e){var i=e.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.app.googleAnalytics,expression:"app.googleAnalytics"}],class:{"is-error":i[0]},attrs:{placeholder:a.$t("admin_settings.others.google_analytics_plac"),type:"text"},domProps:{value:a.app.googleAnalytics},on:{input:[function(e){e.target.composing||a.$set(a.app,"googleAnalytics",e.target.value)},function(e){return a.$updateText("/settings","google_analytics",a.app.googleAnalytics)}]}}),a._v(" "),i[0]?t("span",{staticClass:"error-message"},[a._v(a._s(i[0]))]):a._e()]}}],null,!1,834681330)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_settings.others.mimetypes_blacklist"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Mimetypes Blacklist"},scopedSlots:a._u([{key:"default",fn:function(e){var i=e.errors;return[t("textarea",{directives:[{name:"model",rawName:"v-model",value:a.app.mimetypesBlacklist,expression:"app.mimetypesBlacklist"}],class:{"is-error":i[0]},attrs:{rows:"2",placeholder:a.$t("admin_settings.others.mimetypes_blacklist_plac"),type:"text"},domProps:{value:a.app.mimetypesBlacklist},on:{input:[function(e){e.target.composing||a.$set(a.app,"mimetypesBlacklist",e.target.value)},function(e){return a.$updateText("/settings","mimetypes_blacklist",a.app.mimetypesBlacklist)}]}}),a._v(" "),i[0]?t("span",{staticClass:"error-message"},[a._v(a._s(i[0]))]):a._e()]}}],null,!1,226294926)}),a._v(" "),t("small",{staticClass:"input-help",domProps:{innerHTML:a._s(a.$t("admin_settings.others.mimetypes_blacklist_help"))}})],1),a._v(" "),t("FormLabel",{staticClass:"mt-70"},[a._v("\n                "+a._s(a.$t("admin_settings.others.section_cache"))+"\n            ")]),a._v(" "),t("InfoBox",[a._v("\n                "+a._s(a.$t("admin_settings.others.cache_disclaimer"))+"\n            ")]),a._v(" "),t("ButtonBase",{staticClass:"submit-button",attrs:{loading:a.isFlushingCache,disabled:a.isFlushingCache,type:"submit","button-style":"theme"},nativeOn:{click:function(e){return a.flushCache(e)}}},[a._v("\n                "+a._s(a.$t("admin_settings.others.cache_clear"))+"\n            ")])],1)]):a._e()],1)}),[],!1,null,"794fd742",null);e.default=_.exports},jS71:function(a,e,t){"use strict";var i=t("wsaA");t.n(i).a},lig4:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".form-label[data-v-c1157a8e]{display:flex;align-items:center;margin-bottom:30px}.form-label .icon[data-v-c1157a8e]{margin-right:10px}.form-label .icon path[data-v-c1157a8e]{stroke:#00bc7e}.form-label .label[data-v-c1157a8e]{font-size:1.125em;font-weight:700}@media (prefers-color-scheme:dark){.form-label .label[data-v-c1157a8e]{color:#bec6cf}}",""])},oDxr:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".page-tab-group[data-v-1bb470e4]{margin-bottom:65px}",""])},oyp5:function(a,e,t){var i=t("LE5O");"string"==typeof i&&(i=[[a.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(a.exports=i.locals)},qefO:function(a,e,t){"use strict";var i={name:"PageTab",props:["isLoading"],components:{Spinner:t("zTYo").a}},o=(t("5pbA"),t("KHd+")),r=Object(o.a)(i,(function(){var a=this.$createElement,e=this._self._c||a;return e("div",{staticClass:"page-tab"},[e("div",{directives:[{name:"show",rawName:"v-show",value:this.isLoading,expression:"isLoading"}],attrs:{id:"loader"}},[e("Spinner")],1),this._v(" "),this._t("default")],2)}),[],!1,null,"4339da66",null);e.a=r.exports},w9z4:function(a,e,t){"use strict";var i=t("bN/l");t.n(i).a},woIv:function(a,e,t){"use strict";var i=t("VA79");t.n(i).a},wsaA:function(a,e,t){var i=t("FEcZ");"string"==typeof i&&(i=[[a.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(a.exports=i.locals)},xnZf:function(a,e,t){"use strict";var i=t("+Pqb"),o=t("CjXH"),r={name:"StorageItemDetail",props:["percentage","title","type","used"],components:{HardDriveIcon:o.z,FileTextIcon:o.v,ProgressBar:i.a,MusicIcon:o.L,VideoIcon:o.cb,ImageIcon:o.B,FileIcon:o.u}},s=(t("b0xl"),t("KHd+")),n=Object(s.a)(r,(function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("article",{staticClass:"detail-storage-item",class:a.type},[t("div",{staticClass:"header-storage-item"},[t("div",{staticClass:"icon"},["images"==a.type?t("image-icon",{attrs:{size:"23"}}):a._e(),a._v(" "),"videos"==a.type?t("video-icon",{attrs:{size:"23"}}):a._e(),a._v(" "),"audios"==a.type?t("music-icon",{attrs:{size:"23"}}):a._e(),a._v(" "),"documents"==a.type?t("file-text-icon",{attrs:{size:"23"}}):a._e(),a._v(" "),"others"==a.type?t("file-icon",{attrs:{size:"23"}}):a._e(),a._v(" "),"disk"==a.type?t("hard-drive-icon",{attrs:{size:"23"}}):a._e()],1),a._v(" "),t("div",{staticClass:"title"},[t("b",{staticClass:"type"},[a._v(a._s(a.title))]),a._v(" "),t("span",{staticClass:"total-size"},[a._v(a._s(a.used))])])]),a._v(" "),t("ProgressBar",{staticClass:"storage-progress",attrs:{progress:a.percentage}})],1)}),[],!1,null,"56af1b6e",null);e.a=n.exports},xo5e:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".form[data-v-794fd742]{max-width:700px}.form.inline-form[data-v-794fd742]{display:flex;position:relative;justify-content:center;margin:0 auto}.form.inline-form .input-wrapper[data-v-794fd742]{position:relative}.form.inline-form .input-wrapper .error-message[data-v-794fd742]{position:absolute;left:0;bottom:-25px}.form.block-form .wrapper-inline[data-v-794fd742]{display:flex;margin:0 -15px}.form.block-form .wrapper-inline .block-wrapper[data-v-794fd742]{width:100%;padding:0 15px}.form.block-form .block-wrapper[data-v-794fd742]{margin-bottom:32px}.form.block-form .block-wrapper label[data-v-794fd742]{font-size:.875em;color:rgba(27,37,57,.8);font-weight:700;display:block;margin-bottom:7px;text-align:left}.form.block-form .block-wrapper[data-v-794fd742]:last-child{margin-bottom:0}.form.block-form .button[data-v-794fd742]{margin-top:50px}.form .inline-wrapper[data-v-794fd742]{display:flex;align-items:center;justify-content:space-between}.form .inline-wrapper .switch-label .input-help[data-v-794fd742]{padding-top:0}.form .inline-wrapper .switch-label .input-label[data-v-794fd742]{font-weight:700;color:#1b2539;font-size:1em;margin-bottom:5px}.form .input-help[data-v-794fd742]{font-size:.75em;color:rgba(27,37,57,.7);line-height:1.35;padding-top:10px;display:block}.single-line-form[data-v-794fd742]{display:flex}.single-line-form .submit-button[data-v-794fd742]{margin-left:20px}.error-message[data-v-794fd742]{font-size:.875em;color:#fd397a;padding-top:5px;display:block;text-align:left}textarea[data-v-794fd742]{width:100%}input[type=email][data-v-794fd742],input[type=number][data-v-794fd742],input[type=password][data-v-794fd742],input[type=text][data-v-794fd742],textarea[data-v-794fd742]{border:1px solid transparent;transition:all .15s ease;font-size:1em;border-radius:8px;padding:13px 20px;-webkit-appearance:none;-moz-appearance:none;appearance:none;font-weight:700;outline:0;width:100%;box-shadow:0 1px 5px rgba(0,0,0,.12)}input[type=email].is-error[data-v-794fd742],input[type=number].is-error[data-v-794fd742],input[type=password].is-error[data-v-794fd742],input[type=text].is-error[data-v-794fd742],textarea.is-error[data-v-794fd742]{border-color:#fd397a;box-shadow:0 1px 5px rgba(253,57,122,.3)}input[type=email][data-v-794fd742]::-moz-placeholder,input[type=number][data-v-794fd742]::-moz-placeholder,input[type=password][data-v-794fd742]::-moz-placeholder,input[type=text][data-v-794fd742]::-moz-placeholder,textarea[data-v-794fd742]::-moz-placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-794fd742]:-ms-input-placeholder,input[type=number][data-v-794fd742]:-ms-input-placeholder,input[type=password][data-v-794fd742]:-ms-input-placeholder,input[type=text][data-v-794fd742]:-ms-input-placeholder,textarea[data-v-794fd742]:-ms-input-placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-794fd742]::placeholder,input[type=number][data-v-794fd742]::placeholder,input[type=password][data-v-794fd742]::placeholder,input[type=text][data-v-794fd742]::placeholder,textarea[data-v-794fd742]::placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-794fd742]:focus,input[type=number][data-v-794fd742]:focus,input[type=password][data-v-794fd742]:focus,input[type=text][data-v-794fd742]:focus,textarea[data-v-794fd742]:focus{border-color:#00bc7e;box-shadow:0 1px 5px rgba(0,188,126,.3)}input[type=email][disabled][data-v-794fd742],input[type=number][disabled][data-v-794fd742],input[type=password][disabled][data-v-794fd742],input[type=text][disabled][data-v-794fd742],textarea[disabled][data-v-794fd742]{background:#fff;color:rgba(27,37,57,.8);-webkit-text-fill-color:rgba(27,37,57,.8);opacity:1;cursor:not-allowed}.additional-link[data-v-794fd742]{font-size:1em;margin-top:50px;display:block;color:#1b2539}.additional-link a[data-v-794fd742],.additional-link b[data-v-794fd742]{color:#00bc7e;cursor:pointer}.additional-link a[data-v-794fd742]:hover,.additional-link b[data-v-794fd742]:hover{text-decoration:underline}@media only screen and (max-width:1024px){.form[data-v-794fd742]{max-width:100%}}@media only screen and (max-width:960px){.form .button[data-v-794fd742]{margin-top:20px;width:100%;margin-left:0;margin-right:0}.form input[data-v-794fd742],.form textarea[data-v-794fd742]{width:100%;min-width:100%}.form.block-form .block-wrapper[data-v-794fd742]{display:block}.form.block-form .block-wrapper label[data-v-794fd742]{width:100%;padding-right:0;display:block;margin-bottom:7px;text-align:left!important;font-size:.875em;padding-top:0}.form.block-form .button[data-v-794fd742]{margin-top:25px;margin-left:0;margin-right:0}.form.inline-form[data-v-794fd742]{display:block}.form.inline-form .input-wrapper .error-message[data-v-794fd742]{position:relative;bottom:0}.form .button[data-v-794fd742]{padding:14px 32px}.single-line-form[data-v-794fd742]{display:block}.single-line-form .submit-button[data-v-794fd742]{margin-left:0;margin-top:20px;width:100%}input[type=email][data-v-794fd742],input[type=number][data-v-794fd742],input[type=password][data-v-794fd742],input[type=text][data-v-794fd742],textarea[data-v-794fd742]{padding:14px 20px}}@media only screen and (max-width:690px){.form.block-form .wrapper-inline[data-v-794fd742]{display:block}}@media (prefers-color-scheme:dark){.form .input-help[data-v-794fd742]{color:#7d858c}.form.block-form .block-wrapper label[data-v-794fd742],.form .inline-wrapper .switch-label .input-label[data-v-794fd742]{color:#bec6cf}input[type=email][data-v-794fd742],input[type=number][data-v-794fd742],input[type=password][data-v-794fd742],input[type=text][data-v-794fd742],textarea[data-v-794fd742]{border-color:#1e2024;background:#1e2024;color:#bec6cf}input[type=email][data-v-794fd742]::-moz-placeholder,input[type=number][data-v-794fd742]::-moz-placeholder,input[type=password][data-v-794fd742]::-moz-placeholder,input[type=text][data-v-794fd742]::-moz-placeholder,textarea[data-v-794fd742]::-moz-placeholder{color:#7d858c}input[type=email][data-v-794fd742]:-ms-input-placeholder,input[type=number][data-v-794fd742]:-ms-input-placeholder,input[type=password][data-v-794fd742]:-ms-input-placeholder,input[type=text][data-v-794fd742]:-ms-input-placeholder,textarea[data-v-794fd742]:-ms-input-placeholder{color:#7d858c}input[type=email][data-v-794fd742]::placeholder,input[type=number][data-v-794fd742]::placeholder,input[type=password][data-v-794fd742]::placeholder,input[type=text][data-v-794fd742]::placeholder,textarea[data-v-794fd742]::placeholder{color:#7d858c}input[type=email][disabled][data-v-794fd742],input[type=number][disabled][data-v-794fd742],input[type=password][disabled][data-v-794fd742],input[type=text][disabled][data-v-794fd742],textarea[disabled][data-v-794fd742]{background:#1e2024;color:rgba(125,133,140,.8);-webkit-text-fill-color:rgba(125,133,140,.8)}}.block-form[data-v-794fd742]{max-width:100%}",""])},yI2c:function(a,e,t){"use strict";var i=t("zlQ3");t.n(i).a},ydEr:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".info-box[data-v-bf43be5e]{padding:20px;border-radius:8px;margin-bottom:32px;background:#f4f5f6;text-align:left}.info-box.error[data-v-bf43be5e]{background:rgba(253,57,122,.1)}.info-box.error a[data-v-bf43be5e],.info-box.error p[data-v-bf43be5e]{color:#fd397a}.info-box.error a[data-v-bf43be5e]{text-decoration:underline}.info-box p[data-v-bf43be5e]{font-size:.9375em;line-height:1.6;word-break:break-word;font-weight:600}.info-box p[data-v-bf43be5e] a{color:#00bc7e}.info-box a[data-v-bf43be5e],.info-box b[data-v-bf43be5e]{font-weight:700;color:#00bc7e}.info-box a[data-v-bf43be5e]{font-size:.9375em;line-height:1.6}.info-box ul[data-v-bf43be5e]{margin-top:15px}.info-box ul[data-v-bf43be5e],.info-box ul li[data-v-bf43be5e],.info-box ul li a[data-v-bf43be5e]{display:block}@media only screen and (max-width:690px){.info-box[data-v-bf43be5e]{padding:15px}}@media (prefers-color-scheme:dark){.info-box[data-v-bf43be5e]{background:#1e2024}.info-box p[data-v-bf43be5e],.info-box ul li[data-v-bf43be5e]{color:#bec6cf}}",""])},zlQ3:function(a,e,t){var i=t("oDxr");"string"==typeof i&&(i=[[a.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(a.exports=i.locals)}}]);