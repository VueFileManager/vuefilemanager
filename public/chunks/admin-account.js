(window.webpackJsonp=window.webpackJsonp||[]).push([[11],{"+SgC":function(a,n,t){"use strict";t.r(n);var e=t("o0o1"),i=t.n(e),o=t("A5+z"),r=t("bDRN"),d=t("4TWA"),p=t("xxrA"),s=t("D+dh"),c=t("eZ9V"),l=t("KnjL"),u=t("j8qy"),m=t("ASoH"),v=t("CjXH"),f=t("TJPC"),h=t("xCqy"),b=t("vDqi"),x=t.n(b);function w(a,n,t,e,i,o,r){try{var d=a[o](r),p=d.value}catch(a){return void t(a)}d.done?n(p):Promise.resolve(p).then(e,i)}var g={name:"EnvironmentSetup",components:{AuthContentWrapper:r.a,ValidationProvider:o.ValidationProvider,ValidationObserver:o.ValidationObserver,SettingsIcon:v.bb,SelectInput:d.a,SwitchInput:p.a,AuthContent:u.a,ImageInput:s.a,AuthButton:m.a,FormLabel:c.a,required:f.a,InfoBox:l.a},data:function(){return{isLoading:!1,admin:{name:"",email:"",avatar:void 0,password:"",password_confirmation:""}}},methods:{adminAccountSubmit:function(){var a,n=this;return(a=i.a.mark((function a(){var t;return i.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return a.next=2,n.$refs.adminAccount.validate();case 2:if(a.sent){a.next=5;break}return a.abrupt("return");case 5:n.isLoading=!0,(t=new FormData).append("name",n.admin.name),t.append("email",n.admin.email),t.append("password",n.admin.password),t.append("password_confirmation",n.admin.password_confirmation),t.append("license",localStorage.getItem("license")),t.append("purchase_code",localStorage.getItem("purchase_code")),n.admin.avatar&&t.append("avatar",n.admin.avatar),x.a.post("/api/setup/admin-setup",t,{headers:{"Content-Type":"multipart/form-data"}}).then((function(a){n.isLoading=!1,n.$store.commit("SET_AUTHORIZED",!0),"Extended"===localStorage.getItem("license")&&n.$store.commit("SET_SAAS",!0),n.$router.push({name:"Dashboard"}),localStorage.removeItem("purchase_code"),localStorage.removeItem("license")})).catch((function(a){401==a.response.status&&"invalid_client"===a.response.data.error&&h.a.$emit("alert:open",{emoji:"🤔",title:n.$t("popup_passport_error.title"),message:n.$t("popup_passport_error.message")}),500==a.response.status&&h.a.$emit("alert:open",{emoji:"🤔",title:n.$t("popup_signup_error.title"),message:n.$t("popup_signup_error.message")}),422==a.response.status&&(a.response.data.errors.email&&n.$refs.adminAccount.setErrors({Email:a.response.data.errors.email}),a.response.data.errors.password&&n.$refs.adminAccount.setErrors({Password:a.response.data.errors.password})),n.isLoading=!1}));case 15:case"end":return a.stop()}}),a)})),function(){var n=this,t=arguments;return new Promise((function(e,i){var o=a.apply(n,t);function r(a){w(o,e,i,r,d,"next",a)}function d(a){w(o,e,i,r,d,"throw",a)}r(void 0)}))})()}},created:function(){this.$scrollTop()}},y=(t("HihL"),t("KHd+")),k=Object(y.a)(g,(function(){var a=this,n=a.$createElement,t=a._self._c||n;return t("AuthContentWrapper",{ref:"auth"},[t("AuthContent",{attrs:{name:"database-credentials",visible:!0}},[t("div",{staticClass:"content-headline"},[t("settings-icon",{staticClass:"title-icon",attrs:{size:"40"}}),a._v(" "),t("h1",[a._v("Setup Wizard")]),a._v(" "),t("h2",[a._v("Create your admin account.")])],1),a._v(" "),t("ValidationObserver",{ref:"adminAccount",staticClass:"form block-form",attrs:{tag:"form"},on:{submit:function(n){return n.preventDefault(),a.adminAccountSubmit(n)}},scopedSlots:a._u([{key:"default",fn:function(n){n.invalid;return[t("FormLabel",[a._v("Create Admin Account")]),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v("Avatar (optional):")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Avatar"},scopedSlots:a._u([{key:"default",fn:function(n){var e=n.errors;return[t("ImageInput",{attrs:{error:e[0]},model:{value:a.admin.avatar,callback:function(n){a.$set(a.admin,"avatar",n)},expression:"admin.avatar"}})]}}],null,!0)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v("Full Name:")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Full Name",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(n){var e=n.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.admin.name,expression:"admin.name"}],class:{"is-error":e[0]},attrs:{placeholder:"Type your full name",type:"text"},domProps:{value:a.admin.name},on:{input:function(n){n.target.composing||a.$set(a.admin,"name",n.target.value)}}}),a._v(" "),e[0]?t("span",{staticClass:"error-message"},[a._v(a._s(e[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v("Email:")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Email",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(n){var e=n.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.admin.email,expression:"admin.email"}],class:{"is-error":e[0]},attrs:{placeholder:"Type your email",type:"email"},domProps:{value:a.admin.email},on:{input:function(n){n.target.composing||a.$set(a.admin,"email",n.target.value)}}}),a._v(" "),e[0]?t("span",{staticClass:"error-message"},[a._v(a._s(e[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v("Password:")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Password",rules:"required|confirmed:confirmation"},scopedSlots:a._u([{key:"default",fn:function(n){var e=n.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.admin.password,expression:"admin.password"}],class:{"is-error":e[0]},attrs:{placeholder:"Type your password",type:"password"},domProps:{value:a.admin.password},on:{input:function(n){n.target.composing||a.$set(a.admin,"password",n.target.value)}}}),a._v(" "),e[0]?t("span",{staticClass:"error-message"},[a._v(a._s(e[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v("Password Confirmation:")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",name:"confirmation",rules:"required",vid:"confirmation"},scopedSlots:a._u([{key:"default",fn:function(n){var e=n.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.admin.password_confirmation,expression:"admin.password_confirmation"}],class:{"is-error":e[0]},attrs:{placeholder:"Confirm your password",type:"password"},domProps:{value:a.admin.password_confirmation},on:{input:function(n){n.target.composing||a.$set(a.admin,"password_confirmation",n.target.value)}}}),a._v(" "),e[0]?t("span",{staticClass:"error-message"},[a._v(a._s(e[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),t("div",{staticClass:"submit-wrapper"},[t("AuthButton",{attrs:{icon:"chevron-right",text:"Create Admin and Login",loading:a.isLoading,disabled:a.isLoading}})],1)]}}])})],1)],1)}),[],!1,null,"71c1ea60",null);n.default=k.exports},ASoH:function(a,n,t){"use strict";var e={name:"AuthContent",props:["loading","icon","text"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},i=(t("RLEU"),t("KHd+")),o=Object(i.a)(e,(function(){var a=this,n=a.$createElement,t=a._self._c||n;return t("button",{staticClass:"button outline hover-text-theme hover-border-theme"},[t("span",{staticClass:"text-label"},[a._v(a._s(a.text))]),a._v(" "),a.loading?t("span",{staticClass:"icon"},[t("FontAwesomeIcon",{staticClass:"sync-alt svg-color-theme",attrs:{icon:"sync-alt"}})],1):a._e(),a._v(" "),!a.loading&&a.icon?t("span",{staticClass:"icon"},[t("FontAwesomeIcon",{staticClass:"svg-color-theme",attrs:{icon:a.icon}})],1):a._e()])}),[],!1,null,"16e9ad58",null);n.a=o.exports},HihL:function(a,n,t){"use strict";var e=t("z9sf");t.n(e).a},JHC5:function(a,n,t){var e=t("YUi1");"string"==typeof e&&(e=[[a.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(e,i);e.locals&&(a.exports=e.locals)},Jx8r:function(a,n,t){(a.exports=t("I1BE")(!1)).push([a.i,"#auth[data-v-31af8372] {\n  height: 100%;\n  width: 100%;\n  display: table;\n}\n",""])},KnjL:function(a,n,t){"use strict";var e={name:"InfoBox",props:["type"]},i=(t("pFam"),t("KHd+")),o=Object(i.a)(e,(function(){var a=this.$createElement;return(this._self._c||a)("div",{staticClass:"info-box",class:this.type},[this._t("default")],2)}),[],!1,null,"8e7c42f6",null);n.a=o.exports},LedX:function(a,n,t){"use strict";var e=t("WEWl");t.n(e).a},"Qqv+":function(a,n,t){var e=t("biqn");"string"==typeof e&&(e=[[a.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(e,i);e.locals&&(a.exports=e.locals)},RLEU:function(a,n,t){"use strict";var e=t("JHC5");t.n(e).a},WEWl:function(a,n,t){var e=t("sGz8");"string"==typeof e&&(e=[[a.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(e,i);e.locals&&(a.exports=e.locals)},YUi1:function(a,n,t){(a.exports=t("I1BE")(!1)).push([a.i,".button[data-v-16e9ad58] {\n  cursor: pointer;\n  border-radius: 8px;\n  text-decoration: none;\n  padding: 12px 32px;\n  display: inline-block;\n  margin-left: 15px;\n  margin-right: 15px;\n  white-space: nowrap;\n  transition: 150ms all ease;\n  background: transparent;\n}\n.button .text-label[data-v-16e9ad58] {\n  transition: 150ms all ease;\n  font-size: 1.0625em;\n  font-weight: 800;\n  line-height: 0;\n}\n.button .icon[data-v-16e9ad58] {\n  margin-left: 12px;\n  font-size: 1em;\n}\n.button.solid[data-v-16e9ad58] {\n  background: #00BC7E;\n  border: 2px solid #00BC7E;\n}\n.button.solid .text-label[data-v-16e9ad58] {\n  color: white;\n}\n.button.outline[data-v-16e9ad58] {\n  border: 2px solid #1B2539;\n}\n.button.outline .text-label[data-v-16e9ad58] {\n  color: #1B2539;\n}\n.button.outline .icon path[data-v-16e9ad58] {\n  fill: inherit;\n}\n.button.outline[data-v-16e9ad58]:hover {\n  border-color: inherit;\n}\n.button.outline:hover .text-label[data-v-16e9ad58] {\n  color: inherit;\n}\n@media (prefers-color-scheme: dark) {\n.button.outline[data-v-16e9ad58] {\n    background: #141414;\n    border-color: #bec6cf;\n}\n.button.outline .text-label[data-v-16e9ad58] {\n    color: #bec6cf;\n}\n}\n.sync-alt[data-v-16e9ad58] {\n  -webkit-animation: spin-data-v-16e9ad58 1s linear infinite;\n          animation: spin-data-v-16e9ad58 1s linear infinite;\n}\n@-webkit-keyframes spin-data-v-16e9ad58 {\n0% {\n    transform: rotate(0);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@keyframes spin-data-v-16e9ad58 {\n0% {\n    transform: rotate(0);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n",""])},bDRN:function(a,n,t){"use strict";var e={name:"AuthContentWrapper"},i=(t("iYAH"),t("KHd+")),o=Object(i.a)(e,(function(){var a=this.$createElement;return(this._self._c||a)("div",{attrs:{id:"auth"}},[this._t("default")],2)}),[],!1,null,"31af8372",null);n.a=o.exports},biqn:function(a,n,t){(a.exports=t("I1BE")(!1)).push([a.i,".info-box[data-v-8e7c42f6] {\n  padding: 20px;\n  border-radius: 8px;\n  margin-bottom: 32px;\n  background: #f4f5f6;\n  text-align: left;\n}\n.info-box.error[data-v-8e7c42f6] {\n  background: rgba(253, 57, 122, 0.1);\n}\n.info-box.error p[data-v-8e7c42f6], .info-box.error a[data-v-8e7c42f6] {\n  color: #fd397a;\n}\n.info-box.error a[data-v-8e7c42f6] {\n  text-decoration: underline;\n}\n.info-box p[data-v-8e7c42f6] {\n  font-size: 15px;\n  line-height: 1.6;\n  word-break: break-word;\n  font-weight: 600;\n}\n.info-box p[data-v-8e7c42f6] a {\n  font-size: 15px;\n}\n.info-box p[data-v-8e7c42f6] b {\n  font-size: 15px;\n  font-weight: 700;\n}\n.info-box b[data-v-8e7c42f6] {\n  font-weight: 700;\n}\n.info-box a[data-v-8e7c42f6] {\n  font-weight: 700;\n  font-size: 0.9375em;\n  line-height: 1.6;\n}\n.info-box ul[data-v-8e7c42f6] {\n  margin-top: 15px;\n  display: block;\n}\n.info-box ul li[data-v-8e7c42f6] {\n  display: block;\n}\n.info-box ul li a[data-v-8e7c42f6] {\n  display: block;\n}\n@media only screen and (max-width: 690px) {\n.info-box[data-v-8e7c42f6] {\n    padding: 15px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.info-box[data-v-8e7c42f6] {\n    background: #1e2024;\n}\n.info-box p[data-v-8e7c42f6] {\n    color: #bec6cf;\n}\n.info-box ul li[data-v-8e7c42f6] {\n    color: #bec6cf;\n}\n}\n",""])},iYAH:function(a,n,t){"use strict";var e=t("lh0Q");t.n(e).a},j8qy:function(a,n,t){"use strict";var e={name:"AuthContent",props:["visible","name"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},i=t("KHd+"),o=Object(i.a)(e,(function(){var a=this.$createElement,n=this._self._c||a;return this.isVisible?n("div",{staticClass:"auth-form"},[this._t("default")],2):this._e()}),[],!1,null,null,null);n.a=o.exports},lh0Q:function(a,n,t){var e=t("Jx8r");"string"==typeof e&&(e=[[a.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(e,i);e.locals&&(a.exports=e.locals)},pFam:function(a,n,t){"use strict";var e=t("Qqv+");t.n(e).a},pFm6:function(a,n,t){(a.exports=t("I1BE")(!1)).push([a.i,'.form[data-v-71c1ea60] {\n  max-width: 700px;\n}\n.form.inline-form[data-v-71c1ea60] {\n  display: flex;\n  position: relative;\n  justify-content: center;\n  margin: 0 auto;\n}\n.form.inline-form .input-wrapper[data-v-71c1ea60] {\n  position: relative;\n}\n.form.inline-form .input-wrapper .error-message[data-v-71c1ea60] {\n  position: absolute;\n  left: 0;\n  bottom: -25px;\n}\n.form.block-form .wrapper-inline[data-v-71c1ea60] {\n  display: flex;\n  margin: 0 -15px 0;\n}\n.form.block-form .wrapper-inline .block-wrapper[data-v-71c1ea60] {\n  width: 100%;\n  padding: 0 15px;\n}\n.form.block-form .block-wrapper[data-v-71c1ea60] {\n  margin-bottom: 22px;\n}\n.form.block-form .block-wrapper label[data-v-71c1ea60] {\n  font-size: 0.875em;\n  color: rgba(27, 37, 57, 0.8);\n  font-weight: 700;\n  display: block;\n  margin-bottom: 7px;\n  text-align: left;\n}\n.form.block-form .block-wrapper[data-v-71c1ea60]:last-child {\n  margin-bottom: 0;\n}\n.form.block-form .button[data-v-71c1ea60] {\n  margin-top: 50px;\n}\n.form .inline-wrapper[data-v-71c1ea60] {\n  display: flex;\n  align-items: center;\n  justify-content: space-between;\n}\n.form .inline-wrapper .switch-label .input-help[data-v-71c1ea60] {\n  padding-top: 0;\n}\n.form .inline-wrapper .switch-label .input-label[data-v-71c1ea60] {\n  font-weight: 700;\n  color: #1B2539;\n  font-size: 1em;\n  margin-bottom: 5px;\n}\n.form .input-help[data-v-71c1ea60] {\n  font-size: 0.75em;\n  color: rgba(27, 37, 57, 0.7);\n  line-height: 1.35;\n  padding-top: 10px;\n  display: block;\n}\n.single-line-form[data-v-71c1ea60] {\n  display: flex;\n}\n.single-line-form .submit-button[data-v-71c1ea60] {\n  margin-left: 20px;\n}\n.error-message[data-v-71c1ea60] {\n  font-size: 0.875em;\n  color: #fd397a;\n  padding-top: 5px;\n  display: block;\n  text-align: left;\n}\ntextarea[data-v-71c1ea60] {\n  width: 100%;\n}\ntextarea[data-v-71c1ea60],\ninput[type="password"][data-v-71c1ea60],\ninput[type="text"][data-v-71c1ea60],\ninput[type="number"][data-v-71c1ea60],\ninput[type="date"][data-v-71c1ea60],\ninput[type="email"][data-v-71c1ea60] {\n  border: 1px solid transparent;\n  transition: 150ms all ease;\n  font-size: 1em;\n  border-radius: 8px;\n  padding: 13px 20px;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  font-weight: 700;\n  outline: 0;\n  width: 100%;\n  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);\n  background: white;\n}\ntextarea.is-error[data-v-71c1ea60],\ninput[type="password"].is-error[data-v-71c1ea60],\ninput[type="text"].is-error[data-v-71c1ea60],\ninput[type="number"].is-error[data-v-71c1ea60],\ninput[type="date"].is-error[data-v-71c1ea60],\ninput[type="email"].is-error[data-v-71c1ea60] {\n  border-color: #fd397a;\n}\ntextarea[data-v-71c1ea60]::-webkit-input-placeholder, input[type="password"][data-v-71c1ea60]::-webkit-input-placeholder, input[type="text"][data-v-71c1ea60]::-webkit-input-placeholder, input[type="number"][data-v-71c1ea60]::-webkit-input-placeholder, input[type="date"][data-v-71c1ea60]::-webkit-input-placeholder, input[type="email"][data-v-71c1ea60]::-webkit-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-71c1ea60]::-moz-placeholder, input[type="password"][data-v-71c1ea60]::-moz-placeholder, input[type="text"][data-v-71c1ea60]::-moz-placeholder, input[type="number"][data-v-71c1ea60]::-moz-placeholder, input[type="date"][data-v-71c1ea60]::-moz-placeholder, input[type="email"][data-v-71c1ea60]::-moz-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-71c1ea60]:-ms-input-placeholder, input[type="password"][data-v-71c1ea60]:-ms-input-placeholder, input[type="text"][data-v-71c1ea60]:-ms-input-placeholder, input[type="number"][data-v-71c1ea60]:-ms-input-placeholder, input[type="date"][data-v-71c1ea60]:-ms-input-placeholder, input[type="email"][data-v-71c1ea60]:-ms-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-71c1ea60]::-ms-input-placeholder, input[type="password"][data-v-71c1ea60]::-ms-input-placeholder, input[type="text"][data-v-71c1ea60]::-ms-input-placeholder, input[type="number"][data-v-71c1ea60]::-ms-input-placeholder, input[type="date"][data-v-71c1ea60]::-ms-input-placeholder, input[type="email"][data-v-71c1ea60]::-ms-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-71c1ea60]::placeholder,\ninput[type="password"][data-v-71c1ea60]::placeholder,\ninput[type="text"][data-v-71c1ea60]::placeholder,\ninput[type="number"][data-v-71c1ea60]::placeholder,\ninput[type="date"][data-v-71c1ea60]::placeholder,\ninput[type="email"][data-v-71c1ea60]::placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[disabled][data-v-71c1ea60],\ninput[type="password"][disabled][data-v-71c1ea60],\ninput[type="text"][disabled][data-v-71c1ea60],\ninput[type="number"][disabled][data-v-71c1ea60],\ninput[type="date"][disabled][data-v-71c1ea60],\ninput[type="email"][disabled][data-v-71c1ea60] {\n  background: white;\n  color: rgba(27, 37, 57, 0.8);\n  -webkit-text-fill-color: rgba(27, 37, 57, 0.8);\n  opacity: 1;\n  cursor: not-allowed;\n}\ninput[type="color"][data-v-71c1ea60] {\n  width: 38px !important;\n  height: 40px;\n  border: none;\n  outline: none;\n  background: none;\n  min-width: initial !important;\n}\n.additional-link[data-v-71c1ea60] {\n  font-size: 1em;\n  margin-top: 50px;\n  display: block;\n}\n.additional-link b[data-v-71c1ea60], .additional-link a[data-v-71c1ea60] {\n  cursor: pointer;\n}\n.additional-link b[data-v-71c1ea60]:hover, .additional-link a[data-v-71c1ea60]:hover {\n  text-decoration: underline;\n}\n@media only screen and (max-width: 1024px) {\n.form[data-v-71c1ea60] {\n    max-width: 100%;\n}\n}\n@media only screen and (max-width: 960px) {\n.form .button[data-v-71c1ea60] {\n    margin-top: 20px;\n    width: 100%;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form input[data-v-71c1ea60], .form textarea[data-v-71c1ea60] {\n    width: 100%;\n    min-width: 100%;\n}\n.form.block-form .block-wrapper[data-v-71c1ea60] {\n    display: block;\n}\n.form.block-form .block-wrapper label[data-v-71c1ea60] {\n    width: 100%;\n    padding-right: 0;\n    display: block;\n    margin-bottom: 7px;\n    text-align: left !important;\n    font-size: 0.875em;\n    padding-top: 0;\n}\n.form.block-form .button[data-v-71c1ea60] {\n    margin-top: 25px;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form.inline-form[data-v-71c1ea60] {\n    display: block;\n}\n.form.inline-form .input-wrapper .error-message[data-v-71c1ea60] {\n    position: relative;\n    bottom: 0;\n}\n.form .button[data-v-71c1ea60] {\n    padding: 14px 32px;\n}\n.single-line-form[data-v-71c1ea60] {\n    display: block;\n}\n.single-line-form .submit-button[data-v-71c1ea60] {\n    margin-left: 0;\n    margin-top: 20px;\n    width: 100%;\n}\ntextarea[data-v-71c1ea60],\n  input[type="password"][data-v-71c1ea60],\n  input[type="number"][data-v-71c1ea60],\n  input[type="date"][data-v-71c1ea60],\n  input[type="text"][data-v-71c1ea60],\n  input[type="email"][data-v-71c1ea60] {\n    padding: 14px 20px;\n}\n}\n@media only screen and (max-width: 690px) {\n.form.block-form .wrapper-inline[data-v-71c1ea60] {\n    display: block;\n    margin-bottom: 32px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.form .input-help[data-v-71c1ea60] {\n    color: #7d858c;\n}\n.form.block-form .block-wrapper label[data-v-71c1ea60] {\n    color: #bec6cf;\n}\n.form .inline-wrapper .switch-label .input-label[data-v-71c1ea60] {\n    color: #bec6cf;\n}\ntextarea[data-v-71c1ea60],\n  input[type="password"][data-v-71c1ea60],\n  input[type="text"][data-v-71c1ea60],\n  input[type="date"][data-v-71c1ea60],\n  input[type="number"][data-v-71c1ea60],\n  input[type="email"][data-v-71c1ea60] {\n    border-color: #1e2024;\n    background: #1e2024;\n    color: #bec6cf;\n}\ntextarea[data-v-71c1ea60]::-webkit-input-placeholder, input[type="password"][data-v-71c1ea60]::-webkit-input-placeholder, input[type="text"][data-v-71c1ea60]::-webkit-input-placeholder, input[type="date"][data-v-71c1ea60]::-webkit-input-placeholder, input[type="number"][data-v-71c1ea60]::-webkit-input-placeholder, input[type="email"][data-v-71c1ea60]::-webkit-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-71c1ea60]::-moz-placeholder, input[type="password"][data-v-71c1ea60]::-moz-placeholder, input[type="text"][data-v-71c1ea60]::-moz-placeholder, input[type="date"][data-v-71c1ea60]::-moz-placeholder, input[type="number"][data-v-71c1ea60]::-moz-placeholder, input[type="email"][data-v-71c1ea60]::-moz-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-71c1ea60]:-ms-input-placeholder, input[type="password"][data-v-71c1ea60]:-ms-input-placeholder, input[type="text"][data-v-71c1ea60]:-ms-input-placeholder, input[type="date"][data-v-71c1ea60]:-ms-input-placeholder, input[type="number"][data-v-71c1ea60]:-ms-input-placeholder, input[type="email"][data-v-71c1ea60]:-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-71c1ea60]::-ms-input-placeholder, input[type="password"][data-v-71c1ea60]::-ms-input-placeholder, input[type="text"][data-v-71c1ea60]::-ms-input-placeholder, input[type="date"][data-v-71c1ea60]::-ms-input-placeholder, input[type="number"][data-v-71c1ea60]::-ms-input-placeholder, input[type="email"][data-v-71c1ea60]::-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-71c1ea60]::placeholder,\n  input[type="password"][data-v-71c1ea60]::placeholder,\n  input[type="text"][data-v-71c1ea60]::placeholder,\n  input[type="date"][data-v-71c1ea60]::placeholder,\n  input[type="number"][data-v-71c1ea60]::placeholder,\n  input[type="email"][data-v-71c1ea60]::placeholder {\n    color: #7d858c;\n}\ntextarea[disabled][data-v-71c1ea60],\n  input[type="password"][disabled][data-v-71c1ea60],\n  input[type="text"][disabled][data-v-71c1ea60],\n  input[type="date"][disabled][data-v-71c1ea60],\n  input[type="number"][disabled][data-v-71c1ea60],\n  input[type="email"][disabled][data-v-71c1ea60] {\n    background: #1e2024;\n    color: rgba(125, 133, 140, 0.8);\n    -webkit-text-fill-color: rgba(125, 133, 140, 0.8);\n}\n.popup-wrapper textarea[data-v-71c1ea60],\n  .popup-wrapper input[type="password"][data-v-71c1ea60],\n  .popup-wrapper input[type="date"][data-v-71c1ea60],\n  .popup-wrapper input[type="text"][data-v-71c1ea60],\n  .popup-wrapper input[type="number"][data-v-71c1ea60],\n  .popup-wrapper input[type="email"][data-v-71c1ea60] {\n    background: #25272c;\n}\n}\n.auth-logo-text[data-v-71c1ea60] {\n  font-size: 1.375em;\n  font-weight: 800;\n  margin-bottom: 40px;\n  display: block;\n}\n.auth-form[data-v-71c1ea60] {\n  text-align: center;\n  max-width: 600px;\n  padding: 25px 20px;\n  display: table-cell;\n  vertical-align: middle;\n}\n.auth-form input[data-v-71c1ea60] {\n  min-width: 310px;\n}\n.auth-form .additional-link a[data-v-71c1ea60] {\n  font-weight: 700;\n  text-decoration: none;\n}\n.auth-form .user-avatar[data-v-71c1ea60] {\n  width: 100px;\n  height: 100px;\n  -o-object-fit: cover;\n     object-fit: cover;\n  margin-bottom: 20px;\n  border-radius: 8px;\n  box-shadow: 0 10px 30px rgba(25, 54, 60, 0.2);\n}\n.auth-form .logo[data-v-71c1ea60] {\n  width: 120px;\n  margin-bottom: 20px;\n}\n.auth-form h1[data-v-71c1ea60] {\n  font-size: 2.125em;\n  font-weight: 800;\n  line-height: 1.2;\n  margin-bottom: 2px;\n  color: #1B2539;\n}\n.auth-form h2[data-v-71c1ea60] {\n  font-size: 1.4375em;\n  font-weight: 500;\n  margin-bottom: 50px;\n  color: #1B2539;\n}\n.auth-form .block-form[data-v-71c1ea60] {\n  margin-left: auto;\n  margin-right: auto;\n}\n@media only screen and (min-width: 690px) and (max-width: 960px) {\n.auth-form[data-v-71c1ea60] {\n    padding-left: 20%;\n    padding-right: 20%;\n}\n}\n@media only screen and (max-width: 690px) {\n.auth-form[data-v-71c1ea60] {\n    width: 100%;\n}\n.auth-form h1[data-v-71c1ea60] {\n    font-size: 1.875em;\n}\n.auth-form h2[data-v-71c1ea60] {\n    font-size: 1.3125em;\n}\n}\n@media only screen and (max-width: 490px) {\n.auth-form h1[data-v-71c1ea60] {\n    font-size: 1.375em;\n}\n.auth-form h2[data-v-71c1ea60] {\n    font-size: 1.125em;\n}\n.auth-form input[data-v-71c1ea60] {\n    min-width: initial;\n}\n.auth-form .additional-link[data-v-71c1ea60] {\n    font-size: 0.9375em;\n}\n}\n@media (prefers-color-scheme: dark) {\n.auth-form h1[data-v-71c1ea60], .auth-form h2[data-v-71c1ea60], .auth-form .additional-link[data-v-71c1ea60] {\n    color: #bec6cf;\n}\n}\n.content-headline[data-v-71c1ea60] {\n  max-width: 630px;\n  margin-left: auto;\n  margin-right: auto;\n}\n.auth-form input[data-v-71c1ea60] {\n  min-width: initial;\n}\n.form[data-v-71c1ea60] {\n  max-width: 580px;\n  text-align: left;\n}\n.submit-wrapper[data-v-71c1ea60] {\n  text-align: right;\n}\n.submit-wrapper .button[data-v-71c1ea60] {\n  margin: 58px 0 50px 0;\n  width: 100%;\n}\n.title-icon[data-v-71c1ea60] {\n  margin-bottom: 10px;\n  -webkit-animation: spinner-data-v-71c1ea60 5s linear infinite;\n          animation: spinner-data-v-71c1ea60 5s linear infinite;\n}\n.title-icon circle[data-v-71c1ea60], .title-icon path[data-v-71c1ea60] {\n  color: inherit;\n}\n@-webkit-keyframes spinner-data-v-71c1ea60 {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@keyframes spinner-data-v-71c1ea60 {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@media (prefers-color-scheme: dark) {\n.duplicator .duplicator-item[data-v-71c1ea60] {\n    background: #1e2024;\n}\n.duplicator .duplicator-item input[data-v-71c1ea60],\n  .duplicator .duplicator-item textarea[data-v-71c1ea60] {\n    background: #141414;\n}\n}\n',""])},sGz8:function(a,n,t){(a.exports=t("I1BE")(!1)).push([a.i,".input-wrapper[data-v-421ca226] {\n  display: flex;\n  width: 100%;\n}\n.input-wrapper .input-label[data-v-421ca226] {\n  color: #1B2539;\n}\n.input-wrapper .switch-content[data-v-421ca226] {\n  width: 100%;\n}\n.input-wrapper .switch-content[data-v-421ca226]:last-child {\n  width: 80px;\n}\n.switch[data-v-421ca226] {\n  width: 50px;\n  height: 28px;\n  border-radius: 50px;\n  display: block;\n  background: #f1f1f5;\n  position: relative;\n  transition: 0.3s all ease;\n}\n.switch .switch-button[data-v-421ca226] {\n  transition: 0.3s all ease;\n  width: 22px;\n  height: 22px;\n  border-radius: 50px;\n  display: block;\n  background: white;\n  position: absolute;\n  top: 3px;\n  left: 3px;\n  box-shadow: 0 2px 4px rgba(37, 38, 94, 0.1);\n  cursor: pointer;\n}\n.switch.active .switch-button[data-v-421ca226] {\n  left: 25px;\n}\n@media (prefers-color-scheme: dark) {\n.switch[data-v-421ca226] {\n    background: #1e2024;\n}\n.popup-wrapper .switch[data-v-421ca226] {\n    background: #25272c;\n}\n}\n",""])},xxrA:function(a,n,t){"use strict";var e={name:"SwitchInput",props:["label","name","state","info"],data:function(){return{isSwitched:void 0}},methods:{changeState:function(){this.isSwitched=!this.isSwitched,this.$emit("input",this.isSwitched)}},mounted:function(){this.isSwitched=this.state}},i=(t("LedX"),t("KHd+")),o=Object(i.a)(e,(function(){var a=this,n=a.$createElement,t=a._self._c||n;return t("div",{staticClass:"input-wrapper"},[t("div",{staticClass:"switch-content"},[a.label?t("label",{staticClass:"input-label"},[a._v(a._s(a.label)+":")]):a._e(),a._v(" "),a.info?t("small",{staticClass:"input-info"},[a._v(a._s(a.info))]):a._e()]),a._v(" "),t("div",{staticClass:"switch-content text-right"},[t("div",{staticClass:"switch",class:{active:a.isSwitched},on:{click:a.changeState}},[t("div",{staticClass:"switch-button"})])])])}),[],!1,null,"421ca226",null);n.a=o.exports},z9sf:function(a,n,t){var e=t("pFm6");"string"==typeof e&&(e=[[a.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(e,i);e.locals&&(a.exports=e.locals)}}]);