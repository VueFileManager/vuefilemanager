(window.webpackJsonp=window.webpackJsonp||[]).push([[54],{"2Sb1":function(e,a,t){"use strict";var r={name:"PageHeader",props:["title","canBack"],components:{ChevronLeftIcon:t("CjXH").g}},o=(t("qf9i"),t("KHd+")),i=Object(o.a)(r,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",{staticClass:"page-header"},[e.canBack?t("div",{staticClass:"go-back",on:{click:function(a){return e.$router.back()}}},[t("chevron-left-icon",{attrs:{size:"17"}})],1):e._e(),e._v(" "),t("div",{staticClass:"content"},[t("h1",{staticClass:"title"},[e._v(e._s(e.title))])])])}),[],!1,null,"aafe7e24",null);a.a=i.exports},"3Idf":function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".page-header[data-v-aafe7e24]{display:flex;align-items:center;background:#fff;z-index:9;width:100%;position:-webkit-sticky;position:sticky;top:0;padding-top:20px;padding-bottom:20px}.page-header .title[data-v-aafe7e24]{font-size:1.125em;font-weight:700;color:#1b2539}.page-header .go-back[data-v-aafe7e24]{margin-right:10px;cursor:pointer}.page-header .go-back svg[data-v-aafe7e24]{vertical-align:middle;margin-top:-4px}@media only screen and (max-width:960px){.page-header .title[data-v-aafe7e24]{font-size:1.125em}}@media only screen and (max-width:690px){.page-header[data-v-aafe7e24]{display:none}}@media (prefers-color-scheme:dark){.page-header[data-v-aafe7e24]{background:#131414}.page-header .title[data-v-aafe7e24]{color:#bec6cf}.page-header .icon path[data-v-aafe7e24]{fill:#00bc7e}}",""])},"92Jy":function(e,a,t){"use strict";var r=t("qphJ");t.n(r).a},"D+dh":function(e,a,t){"use strict";var r=t("CjXH"),o={name:"ImageInput",props:["image","error"],components:{ImageIcon:r.B,XIcon:r.db},data:function(){return{imagePreview:void 0}},computed:{isData:function(){return void 0!==this.imagePreview&&""!==this.imagePreview}},methods:{resetImage:function(){this.imagePreview=void 0,this.$emit("input",void 0)},showImagePreview:function(e){var a=this,t=e.target.files[0].name,r=t.substring(t.lastIndexOf(".")+1).toLowerCase();if(["png","jpg","jpeg","svg"].includes(r)){var o=e.target.files[0],i=new FileReader;i.onload=function(){return a.imagePreview=i.result},i.readAsDataURL(o),this.$emit("input",e.target.files[0])}else alert(this.$t("validation_errors.wrong_image"))}},created:function(){this.image&&(this.imagePreview=this.image)}},i=(t("w9z4"),t("KHd+")),s=Object(i.a)(o,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",{staticClass:"dropzone",class:{"is-error":e.error}},[e.imagePreview?t("div",{staticClass:"reset-image",on:{click:e.resetImage}},[t("x-icon",{staticClass:"close-icon",attrs:{size:"14"}})],1):e._e(),e._v(" "),t("input",{ref:"file",staticClass:"dummy",attrs:{type:"file"},on:{change:function(a){return e.showImagePreview(a)}}}),e._v(" "),e.imagePreview?t("img",{ref:"image",staticClass:"image-preview",attrs:{src:e.imagePreview}}):e._e(),e._v(" "),t("div",{directives:[{name:"show",rawName:"v-show",value:!e.isData,expression:"! isData"}],staticClass:"dropzone-message"},[t("image-icon",{staticClass:"icon-upload",attrs:{size:"28"}}),e._v(" "),t("span",{staticClass:"dropzone-title"},[e._v("\n            "+e._s(e.$t("input_image.title"))+"\n        ")]),e._v(" "),t("span",{staticClass:"dropzone-description"},[e._v("\n            "+e._s(e.$t("input_image.supported"))+"\n        ")])],1)])}),[],!1,null,"eb0cae00",null);a.a=s.exports},GwJr:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".form[data-v-77be2634]{max-width:700px}.form.inline-form[data-v-77be2634]{display:flex;position:relative;justify-content:center;margin:0 auto}.form.inline-form .input-wrapper[data-v-77be2634]{position:relative}.form.inline-form .input-wrapper .error-message[data-v-77be2634]{position:absolute;left:0;bottom:-25px}.form.block-form .wrapper-inline[data-v-77be2634]{display:flex;margin:0 -15px}.form.block-form .wrapper-inline .block-wrapper[data-v-77be2634]{width:100%;padding:0 15px}.form.block-form .block-wrapper[data-v-77be2634]{margin-bottom:32px}.form.block-form .block-wrapper label[data-v-77be2634]{font-size:.875em;color:rgba(27,37,57,.8);font-weight:700;display:block;margin-bottom:7px;text-align:left}.form.block-form .block-wrapper[data-v-77be2634]:last-child{margin-bottom:0}.form.block-form .button[data-v-77be2634]{margin-top:50px}.form .inline-wrapper[data-v-77be2634]{display:flex;align-items:center;justify-content:space-between}.form .inline-wrapper .switch-label .input-help[data-v-77be2634]{padding-top:0}.form .inline-wrapper .switch-label .input-label[data-v-77be2634]{font-weight:700;color:#1b2539;font-size:1em;margin-bottom:5px}.form .input-help[data-v-77be2634]{font-size:.75em;color:rgba(27,37,57,.7);line-height:1.35;padding-top:10px;display:block}.single-line-form[data-v-77be2634]{display:flex}.single-line-form .submit-button[data-v-77be2634]{margin-left:20px}.error-message[data-v-77be2634]{font-size:.875em;color:#fd397a;padding-top:5px;display:block;text-align:left}textarea[data-v-77be2634]{width:100%}input[type=email][data-v-77be2634],input[type=number][data-v-77be2634],input[type=password][data-v-77be2634],input[type=text][data-v-77be2634],textarea[data-v-77be2634]{border:1px solid transparent;transition:all .15s ease;font-size:1em;border-radius:8px;padding:13px 20px;-webkit-appearance:none;-moz-appearance:none;appearance:none;font-weight:700;outline:0;width:100%;box-shadow:0 1px 5px rgba(0,0,0,.12)}input[type=email].is-error[data-v-77be2634],input[type=number].is-error[data-v-77be2634],input[type=password].is-error[data-v-77be2634],input[type=text].is-error[data-v-77be2634],textarea.is-error[data-v-77be2634]{border-color:#fd397a;box-shadow:0 1px 5px rgba(253,57,122,.3)}input[type=email][data-v-77be2634]::-moz-placeholder,input[type=number][data-v-77be2634]::-moz-placeholder,input[type=password][data-v-77be2634]::-moz-placeholder,input[type=text][data-v-77be2634]::-moz-placeholder,textarea[data-v-77be2634]::-moz-placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-77be2634]:-ms-input-placeholder,input[type=number][data-v-77be2634]:-ms-input-placeholder,input[type=password][data-v-77be2634]:-ms-input-placeholder,input[type=text][data-v-77be2634]:-ms-input-placeholder,textarea[data-v-77be2634]:-ms-input-placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-77be2634]::placeholder,input[type=number][data-v-77be2634]::placeholder,input[type=password][data-v-77be2634]::placeholder,input[type=text][data-v-77be2634]::placeholder,textarea[data-v-77be2634]::placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-77be2634]:focus,input[type=number][data-v-77be2634]:focus,input[type=password][data-v-77be2634]:focus,input[type=text][data-v-77be2634]:focus,textarea[data-v-77be2634]:focus{border-color:#00bc7e;box-shadow:0 1px 5px rgba(0,188,126,.3)}input[type=email][disabled][data-v-77be2634],input[type=number][disabled][data-v-77be2634],input[type=password][disabled][data-v-77be2634],input[type=text][disabled][data-v-77be2634],textarea[disabled][data-v-77be2634]{background:#fff;color:rgba(27,37,57,.8);-webkit-text-fill-color:rgba(27,37,57,.8);opacity:1;cursor:not-allowed}.additional-link[data-v-77be2634]{font-size:1em;margin-top:50px;display:block;color:#1b2539}.additional-link a[data-v-77be2634],.additional-link b[data-v-77be2634]{color:#00bc7e;cursor:pointer}.additional-link a[data-v-77be2634]:hover,.additional-link b[data-v-77be2634]:hover{text-decoration:underline}@media only screen and (max-width:1024px){.form[data-v-77be2634]{max-width:100%}}@media only screen and (max-width:960px){.form .button[data-v-77be2634]{margin-top:20px;width:100%;margin-left:0;margin-right:0}.form input[data-v-77be2634],.form textarea[data-v-77be2634]{width:100%;min-width:100%}.form.block-form .block-wrapper[data-v-77be2634]{display:block}.form.block-form .block-wrapper label[data-v-77be2634]{width:100%;padding-right:0;display:block;margin-bottom:7px;text-align:left!important;font-size:.875em;padding-top:0}.form.block-form .button[data-v-77be2634]{margin-top:25px;margin-left:0;margin-right:0}.form.inline-form[data-v-77be2634]{display:block}.form.inline-form .input-wrapper .error-message[data-v-77be2634]{position:relative;bottom:0}.form .button[data-v-77be2634]{padding:14px 32px}.single-line-form[data-v-77be2634]{display:block}.single-line-form .submit-button[data-v-77be2634]{margin-left:0;margin-top:20px;width:100%}input[type=email][data-v-77be2634],input[type=number][data-v-77be2634],input[type=password][data-v-77be2634],input[type=text][data-v-77be2634],textarea[data-v-77be2634]{padding:14px 20px}}@media only screen and (max-width:690px){.form.block-form .wrapper-inline[data-v-77be2634]{display:block}}@media (prefers-color-scheme:dark){.form .input-help[data-v-77be2634]{color:#7d858c}.form.block-form .block-wrapper label[data-v-77be2634],.form .inline-wrapper .switch-label .input-label[data-v-77be2634]{color:#bec6cf}input[type=email][data-v-77be2634],input[type=number][data-v-77be2634],input[type=password][data-v-77be2634],input[type=text][data-v-77be2634],textarea[data-v-77be2634]{border-color:#1e2024;background:#1e2024;color:#bec6cf}input[type=email][data-v-77be2634]::-moz-placeholder,input[type=number][data-v-77be2634]::-moz-placeholder,input[type=password][data-v-77be2634]::-moz-placeholder,input[type=text][data-v-77be2634]::-moz-placeholder,textarea[data-v-77be2634]::-moz-placeholder{color:#7d858c}input[type=email][data-v-77be2634]:-ms-input-placeholder,input[type=number][data-v-77be2634]:-ms-input-placeholder,input[type=password][data-v-77be2634]:-ms-input-placeholder,input[type=text][data-v-77be2634]:-ms-input-placeholder,textarea[data-v-77be2634]:-ms-input-placeholder{color:#7d858c}input[type=email][data-v-77be2634]::placeholder,input[type=number][data-v-77be2634]::placeholder,input[type=password][data-v-77be2634]::placeholder,input[type=text][data-v-77be2634]::placeholder,textarea[data-v-77be2634]::placeholder{color:#7d858c}input[type=email][disabled][data-v-77be2634],input[type=number][disabled][data-v-77be2634],input[type=password][disabled][data-v-77be2634],input[type=text][disabled][data-v-77be2634],textarea[disabled][data-v-77be2634]{background:#1e2024;color:rgba(125,133,140,.8);-webkit-text-fill-color:rgba(125,133,140,.8)}}",""])},IS7u:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".dropzone[data-v-eb0cae00]{border:1px dashed #a1abc2;border-radius:8px;position:relative;text-align:center;display:flex;align-items:center;min-height:175px}.dropzone.is-error[data-v-eb0cae00]{border:2px dashed rgba(253,57,122,.3)}.dropzone.is-error .dropzone-title[data-v-eb0cae00]{color:#fd397a}.dropzone.is-error .icon-upload circle[data-v-eb0cae00],.dropzone.is-error .icon-upload polyline[data-v-eb0cae00],.dropzone.is-error .icon-upload rect[data-v-eb0cae00]{stroke:#fd397a}.dropzone input[type=file][data-v-eb0cae00]{opacity:0;position:absolute;top:0;left:0;right:0;bottom:0;z-index:1;width:100%;cursor:pointer}.dropzone .image-preview[data-v-eb0cae00]{position:absolute;width:100%;height:100%;-o-object-fit:contain;object-fit:contain;left:0;padding:25px;display:block}.dropzone .image-preview.fit-image[data-v-eb0cae00]{-o-object-fit:cover;object-fit:cover;border-radius:12px;overflow:hidden}.dropzone .dropzone-message[data-v-eb0cae00]{padding:50px 0;width:100%}.dropzone .dropzone-message .icon-upload circle[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload polyline[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload rect[data-v-eb0cae00]{stroke:#00bc7e}.dropzone .dropzone-message .dropzone-title[data-v-eb0cae00]{font-size:1em;font-weight:700;display:block}.dropzone .dropzone-message .dropzone-description[data-v-eb0cae00]{color:rgba(27,37,57,.7);font-size:.75em}.dropzone .reset-image[data-v-eb0cae00]{z-index:2;background:#fff;border-radius:50px;display:block;position:absolute;right:0;top:0;cursor:pointer;transform:translateY(-50%) translateX(50%);padding:0 4px;box-shadow:0 1px 5px rgba(0,0,0,.12)}.dropzone .reset-image .close-icon[data-v-eb0cae00]{vertical-align:middle}.dropzone .reset-image .close-icon line path[data-v-eb0cae00]{fill:#1b2539}@media (prefers-color-scheme:dark){.dropzone[data-v-eb0cae00]{border-color:hsla(0,0%,100%,.2)}.dropzone .dropzone-message .icon-upload line[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload path[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload polyline[data-v-eb0cae00]{stroke:#00bc7e}.dropzone .dropzone-message .dropzone-description[data-v-eb0cae00]{color:#7d858c}}",""])},Idvm:function(e,a,t){var r=t("lig4");"string"==typeof r&&(r=[[e.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(r,o);r.locals&&(e.exports=r.locals)},KPNK:function(e,a,t){var r=t("3Idf");"string"==typeof r&&(r=[[e.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(r,o);r.locals&&(e.exports=r.locals)},SF2u:function(e,a,t){"use strict";var r=t("nY35");t.n(r).a},"T3V+":function(e,a,t){"use strict";t.r(a);var r=t("o0o1"),o=t.n(r),i=t("A5+z"),s=t("4TWA"),n=t("D+dh"),p=t("eZ9V"),l=t("D62o"),d=t("THmQ"),c=t("Nv84"),u=t("2Sb1"),m=t("TJPC"),v=t("L2JU"),b=t("xCqy"),f=t("vDqi"),g=t.n(f);function h(e,a,t,r,o,i,s){try{var n=e[i](s),p=n.value}catch(e){return void t(e)}n.done?a(p):Promise.resolve(p).then(r,o)}function _(e,a){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);a&&(r=r.filter((function(a){return Object.getOwnPropertyDescriptor(e,a).enumerable}))),t.push.apply(t,r)}return t}function w(e,a,t){return a in e?Object.defineProperty(e,a,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[a]=t,e}var x={name:"Profile",components:{ValidationProvider:i.ValidationProvider,ValidationObserver:i.ValidationObserver,SectionTitle:d.a,MobileHeader:l.a,SelectInput:s.a,ButtonBase:c.a,ImageInput:n.a,PageHeader:u.a,FormLabel:p.a,required:m.a},computed:function(e){for(var a=1;a<arguments.length;a++){var t=null!=arguments[a]?arguments[a]:{};a%2?_(Object(t),!0).forEach((function(a){w(e,a,t[a])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(t)):_(Object(t)).forEach((function(a){Object.defineProperty(e,a,Object.getOwnPropertyDescriptor(t,a))}))}return e}({},Object(v.b)(["roles"])),data:function(){return{isLoading:!1,user:{role:"",avatar:void 0,name:"",email:"",password:"",password_confirmation:"",storage_capacity:5}}},methods:{createUser:function(){var e,a=this;return(e=o.a.mark((function e(){var t;return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,a.$refs.createUser.validate();case 2:if(e.sent){e.next=5;break}return e.abrupt("return");case 5:a.isLoading=!0,(t=new FormData).append("name",a.user.name),t.append("role",a.user.role),t.append("email",a.user.email),t.append("password",a.user.password),t.append("storage_capacity",a.user.storage_capacity),t.append("password_confirmation",a.user.password_confirmation),a.user.avatar&&t.append("avatar",a.user.avatar),g.a.post("/api/users/create",t,{headers:{"Content-Type":"multipart/form-data"}}).then((function(e){a.isLoading=!1,b.a.$emit("toaster",{type:"success",message:a.$t("toaster.created_user")}),a.$router.push({name:"UserDetail",params:{id:e.data.data.id}})})).catch((function(e){422==e.response.status?(e.response.data.errors.email&&a.$refs.createUser.setErrors({email:e.response.data.errors.email}),e.response.data.errors.password&&a.$refs.createUser.setErrors({password:e.response.data.errors.password}),e.response.data.errors.storage_capacity&&a.$refs.createUser.setErrors({"storage capacity":a.$t("errors.capacity_digit")})):b.a.$emit("alert:open",{title:a.$t("popup_error.title"),message:a.$t("popup_error.message")}),a.isLoading=!1}));case 15:case"end":return e.stop()}}),e)})),function(){var a=this,t=arguments;return new Promise((function(r,o){var i=e.apply(a,t);function s(e){h(i,r,o,s,n,"next",e)}function n(e){h(i,r,o,s,n,"throw",e)}s(void 0)}))})()}}},y=(t("SF2u"),t("KHd+")),k=Object(y.a)(x,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",{attrs:{id:"single-page"}},[t("div",{staticClass:"small-width",attrs:{id:"page-content"}},[t("MobileHeader",{attrs:{title:e.$router.currentRoute.meta.title}}),e._v(" "),t("PageHeader",{attrs:{"can-back":!0,title:e.$router.currentRoute.meta.title}}),e._v(" "),t("div",{staticClass:"content-page"},[t("ValidationObserver",{ref:"createUser",staticClass:"form block-form",attrs:{tag:"form"},on:{submit:function(a){return a.preventDefault(),e.createUser(a)}},scopedSlots:e._u([{key:"default",fn:function(a){a.invalid;return[t("div",{staticClass:"form-group"},[t("FormLabel",[e._v(e._s(e.$t("admin_page_user.create_user.group_details")))]),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("admin_page_user.create_user.avatar")))]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"avatar"},scopedSlots:e._u([{key:"default",fn:function(a){var r=a.errors;return[t("ImageInput",{attrs:{error:r[0]},model:{value:e.user.avatar,callback:function(a){e.$set(e.user,"avatar",a)},expression:"user.avatar"}})]}}],null,!0)})],1),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("page_registration.label_email")))]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"email",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var r=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.user.email,expression:"user.email"}],class:{"is-error":r[0]},attrs:{placeholder:e.$t("admin_page_user.create_user.label_email"),type:"email"},domProps:{value:e.user.email},on:{input:function(a){a.target.composing||e.$set(e.user,"email",a.target.value)}}}),e._v(" "),r[0]?t("span",{staticClass:"error-message"},[e._v(e._s(r[0]))]):e._e()]}}],null,!0)})],1),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("page_registration.label_name")))]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"user name",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var r=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.user.name,expression:"user.name"}],class:{"is-error":r[0]},attrs:{placeholder:e.$t("admin_page_user.create_user.label_name"),type:"text"},domProps:{value:e.user.name},on:{input:function(a){a.target.composing||e.$set(e.user,"name",a.target.value)}}}),e._v(" "),r[0]?t("span",{staticClass:"error-message"},[e._v(e._s(r[0]))]):e._e()]}}],null,!0)})],1),e._v(" "),t("div",{staticClass:"wrapper-inline"},[t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("page_registration.label_pass")))]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"password",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var r=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.user.password,expression:"user.password"}],class:{"is-error":r[0]},attrs:{placeholder:e.$t("page_registration.placeholder_pass"),type:"password"},domProps:{value:e.user.password},on:{input:function(a){a.target.composing||e.$set(e.user,"password",a.target.value)}}}),e._v(" "),r[0]?t("span",{staticClass:"error-message"},[e._v(e._s(r[0]))]):e._e()]}}],null,!0)})],1),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("page_registration.label_confirm_pass")))]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"password confirm",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var r=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.user.password_confirmation,expression:"user.password_confirmation"}],class:{"is-error":r[0]},attrs:{placeholder:e.$t("admin_page_user.create_user.label_conf_pass"),type:"password"},domProps:{value:e.user.password_confirmation},on:{input:function(a){a.target.composing||e.$set(e.user,"password_confirmation",a.target.value)}}}),e._v(" "),r[0]?t("span",{staticClass:"error-message"},[e._v(e._s(r[0]))]):e._e()]}}],null,!0)})],1)])],1),e._v(" "),t("div",{staticClass:"form-group"},[t("FormLabel",[e._v(e._s(e.$t("admin_page_user.create_user.group_settings")))]),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("admin_page_user.select_role"))+":")]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"permission",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var r=a.errors;return[t("SelectInput",{attrs:{options:e.roles,placeholder:e.$t("admin_page_user.select_role"),isError:r[0]},model:{value:e.user.role,callback:function(a){e.$set(e.user,"role",a)},expression:"user.role"}}),e._v(" "),r[0]?t("span",{staticClass:"error-message"},[e._v(e._s(r[0]))]):e._e()]}}],null,!0)})],1),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("admin_page_user.label_change_capacity")))]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"storage capacity",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var r=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.user.storage_capacity,expression:"user.storage_capacity"}],class:{"is-error":r[0]},attrs:{min:"1",max:"999999999",placeholder:e.$t("admin_page_user.label_change_capacity"),type:"number"},domProps:{value:e.user.storage_capacity},on:{input:function(a){a.target.composing||e.$set(e.user,"storage_capacity",a.target.value)}}}),e._v(" "),r[0]?t("span",{staticClass:"error-message"},[e._v(e._s(r[0]))]):e._e()]}}],null,!0)})],1)],1),e._v(" "),t("div",{staticClass:"form-group"},[t("ButtonBase",{attrs:{disabled:e.isLoading,loading:e.isLoading,"button-style":"theme",type:"submit"}},[e._v("\n                        "+e._s(e.$t("admin_page_user.create_user.submit"))+"\n                    ")])],1)]}}])})],1)],1)])}),[],!1,null,"77be2634",null);a.default=k.exports},THmQ:function(e,a,t){"use strict";var r={name:"SectionTitle"},o=(t("92Jy"),t("KHd+")),i=Object(o.a)(r,(function(){var e=this.$createElement;return(this._self._c||e)("b",{staticClass:"text-label"},[this._t("default")],2)}),[],!1,null,"69d97df2",null);a.a=i.exports},Xk6H:function(e,a,t){"use strict";var r=t("Idvm");t.n(r).a},"bN/l":function(e,a,t){var r=t("IS7u");"string"==typeof r&&(r=[[e.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(r,o);r.locals&&(e.exports=r.locals)},eZ9V:function(e,a,t){"use strict";var r={name:"FormLabel",components:{Edit2Icon:t("CjXH").q}},o=(t("Xk6H"),t("KHd+")),i=Object(o.a)(r,(function(){var e=this.$createElement,a=this._self._c||e;return a("div",{staticClass:"form-label"},[a("edit-2-icon",{staticClass:"icon",attrs:{size:"22"}}),this._v(" "),a("b",{staticClass:"label"},[this._t("default")],2)],1)}),[],!1,null,"c1157a8e",null);a.a=i.exports},lW02:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".text-label[data-v-69d97df2]{font-size:.75em;color:#afafaf;font-weight:700;display:block;margin-bottom:20px}@media (prefers-color-scheme:dark){.text-label[data-v-69d97df2]{color:#00bc7e}}",""])},lig4:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".form-label[data-v-c1157a8e]{display:flex;align-items:center;margin-bottom:30px}.form-label .icon[data-v-c1157a8e]{margin-right:10px}.form-label .icon path[data-v-c1157a8e]{stroke:#00bc7e}.form-label .label[data-v-c1157a8e]{font-size:1.125em;font-weight:700}@media (prefers-color-scheme:dark){.form-label .label[data-v-c1157a8e]{color:#bec6cf}}",""])},nY35:function(e,a,t){var r=t("GwJr");"string"==typeof r&&(r=[[e.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(r,o);r.locals&&(e.exports=r.locals)},qf9i:function(e,a,t){"use strict";var r=t("KPNK");t.n(r).a},qphJ:function(e,a,t){var r=t("lW02");"string"==typeof r&&(r=[[e.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(r,o);r.locals&&(e.exports=r.locals)},w9z4:function(e,a,t){"use strict";var r=t("bN/l");t.n(r).a}}]);