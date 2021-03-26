(window.webpackJsonp=window.webpackJsonp||[]).push([[60],{"0wuM":function(a,t,e){"use strict";var n=e("LiXv");e.n(n).a},LiXv:function(a,t,e){var n=e("dpFh");"string"==typeof n&&(n=[[a.i,n,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,r);n.locals&&(a.exports=n.locals)},UevJ:function(a,t,e){"use strict";e.r(t);var n=e("o0o1"),r=e.n(n),i=e("bDRN"),o=e("A5+z"),s=e("j8qy"),p=e("ASoH"),d=e("TJPC"),l=e("L2JU"),c=e("xCqy"),u=e("vDqi"),m=e.n(u);function v(a,t,e,n,r,i,o){try{var s=a[i](o),p=s.value}catch(a){return void e(a)}s.done?t(p):Promise.resolve(p).then(n,r)}function f(a,t){var e=Object.keys(a);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(a);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(a,t).enumerable}))),e.push.apply(e,n)}return e}function g(a,t,e){return t in a?Object.defineProperty(a,t,{value:e,enumerable:!0,configurable:!0,writable:!0}):a[t]=e,a}var h={name:"SignUp",components:{AuthContentWrapper:i.a,ValidationProvider:o.ValidationProvider,ValidationObserver:o.ValidationObserver,AuthContent:s.a,AuthButton:p.a,required:d.a},computed:function(a){for(var t=1;t<arguments.length;t++){var e=null!=arguments[t]?arguments[t]:{};t%2?f(Object(e),!0).forEach((function(t){g(a,t,e[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(a,Object.getOwnPropertyDescriptors(e)):f(Object(e)).forEach((function(t){Object.defineProperty(a,t,Object.getOwnPropertyDescriptor(e,t))}))}return a}({},Object(l.b)(["config"]),{privacyPolicy:function(){return this.config.legal.find((function(a){return"privacy-policy"===a.slug}))},termsOfService:function(){return this.config.legal.find((function(a){return"terms-of-service"===a.slug}))}}),data:function(){return{isLoading:!1,register:{name:"",email:"",password:"",password_confirmation:""}}},methods:{signUp:function(){var a,t=this;return(a=r.a.mark((function a(){return r.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return a.next=2,t.$refs.sign_up.validate();case 2:if(a.sent){a.next=5;break}return a.abrupt("return");case 5:t.isLoading=!0,m.a.post("/register",t.register).then((function(){t.isLoading=!1,t.$store.commit("SET_AUTHORIZED",!0),t.$router.push({name:"Files"})})).catch((function(a){500==a.response.status&&c.a.$emit("alert:open",{emoji:"🤔",title:t.$t("popup_signup_error.title"),message:t.$t("popup_signup_error.message")}),422==a.response.status&&(a.response.data.errors.email&&t.$refs.sign_up.setErrors({"E-Mail":a.response.data.errors.email}),a.response.data.errors.password&&t.$refs.sign_up.setErrors({"Your New Password":a.response.data.errors.password})),t.isLoading=!1}));case 7:case"end":return a.stop()}}),a)})),function(){var t=this,e=arguments;return new Promise((function(n,r){var i=a.apply(t,e);function o(a){v(i,n,r,o,s,"next",a)}function s(a){v(i,n,r,o,s,"throw",a)}o(void 0)}))})()}},created:function(){this.$scrollTop()}},b=(e("0wuM"),e("KHd+")),w=Object(b.a)(h,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("AuthContentWrapper",{ref:"auth"},[e("AuthContent",{attrs:{name:"sign-up",visible:!0}},[a.config.app_logo?e("img",{staticClass:"logo",attrs:{src:a.$getImage(a.config.app_logo),alt:a.config.app_name}}):a._e(),a._v(" "),a.config.app_logo?a._e():e("b",{staticClass:"auth-logo-text"},[a._v(a._s(a.config.app_name))]),a._v(" "),e("h1",[a._v(a._s(a.$t("page_registration.title")))]),a._v(" "),e("h2",[a._v(a._s(a.$t("page_registration.subtitle")))]),a._v(" "),e("ValidationObserver",{ref:"sign_up",staticClass:"form block-form",attrs:{tag:"form"},on:{submit:function(t){return t.preventDefault(),a.signUp(t)}},scopedSlots:a._u([{key:"default",fn:function(t){t.invalid;return[e("div",{staticClass:"block-wrapper"},[e("label",[a._v(a._s(a.$t("page_registration.label_email")))]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"E-Mail",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var n=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.register.email,expression:"register.email"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:a.$t("page_registration.placeholder_email"),type:"email"},domProps:{value:a.register.email},on:{input:function(t){t.target.composing||a.$set(a.register,"email",t.target.value)}}}),a._v(" "),n[0]?e("span",{staticClass:"error-message"},[a._v(a._s(n[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v(a._s(a.$t("page_registration.label_name")))]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Full Name",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var n=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.register.name,expression:"register.name"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:a.$t("page_registration.placeholder_name"),type:"text"},domProps:{value:a.register.name},on:{input:function(t){t.target.composing||a.$set(a.register,"name",t.target.value)}}}),a._v(" "),n[0]?e("span",{staticClass:"error-message"},[a._v(a._s(n[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v(a._s(a.$t("page_registration.label_pass")))]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Your New Password",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var n=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.register.password,expression:"register.password"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:a.$t("page_registration.placeholder_pass"),type:"password"},domProps:{value:a.register.password},on:{input:function(t){t.target.composing||a.$set(a.register,"password",t.target.value)}}}),a._v(" "),n[0]?e("span",{staticClass:"error-message"},[a._v(a._s(n[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v(a._s(a.$t("page_registration.label_confirm_pass")))]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Confirm Your Password",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var n=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.register.password_confirmation,expression:"register.password_confirmation"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:a.$t("page_registration.placeholder_confirm_pass"),type:"password"},domProps:{value:a.register.password_confirmation},on:{input:function(t){t.target.composing||a.$set(a.register,"password_confirmation",t.target.value)}}}),a._v(" "),n[0]?e("span",{staticClass:"error-message"},[a._v(a._s(n[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",[e("i18n",{staticClass:"legal-agreement",attrs:{path:"page_registration.agreement",tag:"p"}},[e("router-link",{staticClass:"text-theme",attrs:{to:{name:"DynamicPage",params:{slug:"terms-of-service"}},target:"_blank"}},[a._v(a._s(a.termsOfService.title))]),a._v(" "),e("router-link",{staticClass:"text-theme",attrs:{to:{name:"DynamicPage",params:{slug:"privacy-policy"}},target:"_blank"}},[a._v(a._s(a.privacyPolicy.title))])],1),a._v(" "),e("AuthButton",{attrs:{icon:"chevron-right",text:a.$t("page_registration.button_create_account"),loading:a.isLoading,disabled:a.isLoading}})],1)]}}])}),a._v(" "),e("span",{staticClass:"additional-link"},[a._v(a._s(a.$t("page_registration.have_an_account"))+"\n            "),e("router-link",{staticClass:"text-theme",attrs:{to:{name:"SignIn"}}},[a._v("\n                "+a._s(a.$t("page_forgotten_password.password_remember_button"))+"\n            ")])],1)],1)],1)}),[],!1,null,"da92097a",null);t.default=w.exports},adfF:function(a,t,e){var n=e("kFeM");"string"==typeof n&&(n=[[a.i,n,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(n,r);n.locals&&(a.exports=n.locals)},cJb0:function(a,t,e){"use strict";var n=e("adfF");e.n(n).a},dpFh:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,'.form.inline-form[data-v-da92097a] {\n  display: flex;\n  position: relative;\n  justify-content: center;\n  margin: 0 auto;\n}\n.form.inline-form .input-wrapper[data-v-da92097a] {\n  position: relative;\n}\n.form.inline-form .input-wrapper .error-message[data-v-da92097a] {\n  position: absolute;\n  left: 0;\n}\n.form.block-form.create-new-password .block-wrapper label[data-v-da92097a] {\n  width: 280px;\n}\n.form.block-form .block-wrapper[data-v-da92097a] {\n  display: flex;\n  align-items: center;\n  margin-top: 25px;\n  justify-content: center;\n}\n.form.block-form .block-wrapper[data-v-da92097a]:first-child {\n  margin-top: 0;\n}\n.form.block-form .block-wrapper label[data-v-da92097a] {\n  white-space: nowrap;\n  font-size: 1.125em;\n  font-weight: 700;\n  padding-right: 20px;\n  width: 200px;\n  text-align: right !important;\n  color: #1B2539;\n  text-align: right;\n}\n.form.block-form .button[data-v-da92097a] {\n  margin-top: 50px;\n}\n.input-wrapper .error-message[data-v-da92097a] {\n  font-size: 0.875em;\n  color: #fd397a;\n  padding-top: 5px;\n  display: block;\n  text-align: left;\n}\ntextarea[data-v-da92097a] {\n  width: 100%;\n}\ntextarea[data-v-da92097a],\ninput[type="password"][data-v-da92097a],\ninput[type="text"][data-v-da92097a],\ninput[type="email"][data-v-da92097a] {\n  background: #f4f5f6;\n  border: 1px solid transparent;\n  transition: 0.15s all ease;\n  font-size: 1em;\n  border-radius: 8px;\n  padding: 13px 20px;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  font-weight: 700;\n  outline: 0;\n  width: 100%;\n}\ntextarea.is-error[data-v-da92097a],\ninput[type="password"].is-error[data-v-da92097a],\ninput[type="text"].is-error[data-v-da92097a],\ninput[type="email"].is-error[data-v-da92097a] {\n  border-color: #fd397a;\n}\ntextarea[data-v-da92097a]::-webkit-input-placeholder, input[type="password"][data-v-da92097a]::-webkit-input-placeholder, input[type="text"][data-v-da92097a]::-webkit-input-placeholder, input[type="email"][data-v-da92097a]::-webkit-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-da92097a]::-moz-placeholder, input[type="password"][data-v-da92097a]::-moz-placeholder, input[type="text"][data-v-da92097a]::-moz-placeholder, input[type="email"][data-v-da92097a]::-moz-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-da92097a]:-ms-input-placeholder, input[type="password"][data-v-da92097a]:-ms-input-placeholder, input[type="text"][data-v-da92097a]:-ms-input-placeholder, input[type="email"][data-v-da92097a]:-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-da92097a]::-ms-input-placeholder, input[type="password"][data-v-da92097a]::-ms-input-placeholder, input[type="text"][data-v-da92097a]::-ms-input-placeholder, input[type="email"][data-v-da92097a]::-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-da92097a]::placeholder,\ninput[type="password"][data-v-da92097a]::placeholder,\ninput[type="text"][data-v-da92097a]::placeholder,\ninput[type="email"][data-v-da92097a]::placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[disabled][data-v-da92097a],\ninput[type="password"][disabled][data-v-da92097a],\ninput[type="text"][disabled][data-v-da92097a],\ninput[type="email"][disabled][data-v-da92097a] {\n  color: #A4ADB6;\n  cursor: not-allowed;\n}\n.additional-link[data-v-da92097a] {\n  font-size: 1em;\n  margin-top: 50px;\n  display: block;\n}\n.additional-link b[data-v-da92097a], .additional-link a[data-v-da92097a] {\n  cursor: pointer;\n}\n.additional-link b[data-v-da92097a]:hover, .additional-link a[data-v-da92097a]:hover {\n  text-decoration: underline;\n}\n@media only screen and (max-width: 960px) {\n.form .button[data-v-da92097a] {\n    margin-top: 20px;\n    width: 100%;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form input[data-v-da92097a], .form textarea[data-v-da92097a] {\n    width: 100%;\n    min-width: 100%;\n}\n.form.block-form .block-wrapper[data-v-da92097a] {\n    display: block;\n}\n.form.block-form .block-wrapper label[data-v-da92097a] {\n    width: 100%;\n    padding-right: 0;\n    display: block;\n    margin-bottom: 7px;\n    text-align: left !important;\n    font-size: 0.875em;\n    padding-top: 0;\n}\n.form.block-form .button[data-v-da92097a] {\n    margin-top: 25px;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form.inline-form[data-v-da92097a] {\n    display: block;\n}\n.form.inline-form .input-wrapper .error-message[data-v-da92097a] {\n    position: relative;\n    bottom: 0;\n}\n.form .button[data-v-da92097a] {\n    padding: 14px 32px;\n}\ntextarea[data-v-da92097a],\n  input[type="password"][data-v-da92097a],\n  input[type="text"][data-v-da92097a],\n  input[type="email"][data-v-da92097a] {\n    padding: 14px 20px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.form.block-form .block-wrapper label[data-v-da92097a] {\n    color: #bec6cf;\n}\ntextarea[data-v-da92097a],\n  input[type="password"][data-v-da92097a],\n  input[type="text"][data-v-da92097a],\n  input[type="email"][data-v-da92097a] {\n    background: #1e2024;\n    color: #bec6cf;\n}\ntextarea[data-v-da92097a]::-webkit-input-placeholder, input[type="password"][data-v-da92097a]::-webkit-input-placeholder, input[type="text"][data-v-da92097a]::-webkit-input-placeholder, input[type="email"][data-v-da92097a]::-webkit-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-da92097a]::-moz-placeholder, input[type="password"][data-v-da92097a]::-moz-placeholder, input[type="text"][data-v-da92097a]::-moz-placeholder, input[type="email"][data-v-da92097a]::-moz-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-da92097a]:-ms-input-placeholder, input[type="password"][data-v-da92097a]:-ms-input-placeholder, input[type="text"][data-v-da92097a]:-ms-input-placeholder, input[type="email"][data-v-da92097a]:-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-da92097a]::-ms-input-placeholder, input[type="password"][data-v-da92097a]::-ms-input-placeholder, input[type="text"][data-v-da92097a]::-ms-input-placeholder, input[type="email"][data-v-da92097a]::-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-da92097a]::placeholder,\n  input[type="password"][data-v-da92097a]::placeholder,\n  input[type="text"][data-v-da92097a]::placeholder,\n  input[type="email"][data-v-da92097a]::placeholder {\n    color: #7d858c;\n}\ntextarea[disabled][data-v-da92097a],\n  input[type="password"][disabled][data-v-da92097a],\n  input[type="text"][disabled][data-v-da92097a],\n  input[type="email"][disabled][data-v-da92097a] {\n    color: #7d858c;\n}\n}\n.auth-logo-text[data-v-da92097a] {\n  font-size: 1.375em;\n  font-weight: 800;\n  margin-bottom: 40px;\n  display: block;\n}\n.auth-form[data-v-da92097a] {\n  text-align: center;\n  max-width: 600px;\n  padding: 25px 20px;\n  display: table-cell;\n  vertical-align: middle;\n}\n.auth-form input[data-v-da92097a] {\n  min-width: 310px;\n}\n.auth-form .additional-link a[data-v-da92097a] {\n  font-weight: 700;\n  text-decoration: none;\n}\n.auth-form .user-avatar[data-v-da92097a] {\n  width: 100px;\n  height: 100px;\n  -o-object-fit: cover;\n     object-fit: cover;\n  margin-bottom: 20px;\n  border-radius: 8px;\n  box-shadow: 0 10px 30px rgba(25, 54, 60, 0.2);\n}\n.auth-form .logo[data-v-da92097a] {\n  width: 120px;\n  margin-bottom: 20px;\n}\n.auth-form h1[data-v-da92097a] {\n  font-size: 2.125em;\n  font-weight: 800;\n  line-height: 1.2;\n  margin-bottom: 2px;\n  color: #1B2539;\n}\n.auth-form h2[data-v-da92097a] {\n  font-size: 1.4375em;\n  font-weight: 500;\n  margin-bottom: 50px;\n  color: #1B2539;\n}\n.auth-form .block-form[data-v-da92097a] {\n  margin-left: auto;\n  margin-right: auto;\n}\n@media only screen and (min-width: 690px) and (max-width: 960px) {\n.auth-form[data-v-da92097a] {\n    padding-left: 20%;\n    padding-right: 20%;\n}\n}\n@media only screen and (max-width: 690px) {\n.auth-form[data-v-da92097a] {\n    width: 100%;\n}\n.auth-form h1[data-v-da92097a] {\n    font-size: 1.875em;\n}\n.auth-form h2[data-v-da92097a] {\n    font-size: 1.3125em;\n}\n}\n@media only screen and (max-width: 490px) {\n.auth-form h1[data-v-da92097a] {\n    font-size: 1.375em;\n}\n.auth-form h2[data-v-da92097a] {\n    font-size: 1.125em;\n}\n.auth-form input[data-v-da92097a] {\n    min-width: initial;\n}\n.auth-form .additional-link[data-v-da92097a] {\n    font-size: 0.9375em;\n}\n}\n@media (prefers-color-scheme: dark) {\n.auth-form h1[data-v-da92097a], .auth-form h2[data-v-da92097a], .auth-form .additional-link[data-v-da92097a] {\n    color: #bec6cf;\n}\n}\n.legal-agreement[data-v-da92097a] {\n  font-size: 1em;\n  padding: 55px 0 0;\n  max-width: 400px;\n  font-weight: 700;\n  line-height: 1.6;\n  margin: 0 auto;\n}\n',""])},kFeM:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,'.form.inline-form[data-v-20c64507] {\n  display: flex;\n  position: relative;\n  justify-content: center;\n  margin: 0 auto;\n}\n.form.inline-form .input-wrapper[data-v-20c64507] {\n  position: relative;\n}\n.form.inline-form .input-wrapper .error-message[data-v-20c64507] {\n  position: absolute;\n  left: 0;\n}\n.form.block-form.create-new-password .block-wrapper label[data-v-20c64507] {\n  width: 280px;\n}\n.form.block-form .block-wrapper[data-v-20c64507] {\n  display: flex;\n  align-items: center;\n  margin-top: 25px;\n  justify-content: center;\n}\n.form.block-form .block-wrapper[data-v-20c64507]:first-child {\n  margin-top: 0;\n}\n.form.block-form .block-wrapper label[data-v-20c64507] {\n  white-space: nowrap;\n  font-size: 1.125em;\n  font-weight: 700;\n  padding-right: 20px;\n  width: 200px;\n  text-align: right !important;\n  color: #1B2539;\n  text-align: right;\n}\n.form.block-form .button[data-v-20c64507] {\n  margin-top: 50px;\n}\n.input-wrapper .error-message[data-v-20c64507] {\n  font-size: 0.875em;\n  color: #fd397a;\n  padding-top: 5px;\n  display: block;\n  text-align: left;\n}\ntextarea[data-v-20c64507] {\n  width: 100%;\n}\ntextarea[data-v-20c64507],\ninput[type="password"][data-v-20c64507],\ninput[type="text"][data-v-20c64507],\ninput[type="email"][data-v-20c64507] {\n  background: #f4f5f6;\n  border: 1px solid transparent;\n  transition: 0.15s all ease;\n  font-size: 1em;\n  border-radius: 8px;\n  padding: 13px 20px;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  font-weight: 700;\n  outline: 0;\n  width: 100%;\n}\ntextarea.is-error[data-v-20c64507],\ninput[type="password"].is-error[data-v-20c64507],\ninput[type="text"].is-error[data-v-20c64507],\ninput[type="email"].is-error[data-v-20c64507] {\n  border-color: #fd397a;\n}\ntextarea[data-v-20c64507]::-webkit-input-placeholder, input[type="password"][data-v-20c64507]::-webkit-input-placeholder, input[type="text"][data-v-20c64507]::-webkit-input-placeholder, input[type="email"][data-v-20c64507]::-webkit-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-20c64507]::-moz-placeholder, input[type="password"][data-v-20c64507]::-moz-placeholder, input[type="text"][data-v-20c64507]::-moz-placeholder, input[type="email"][data-v-20c64507]::-moz-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-20c64507]:-ms-input-placeholder, input[type="password"][data-v-20c64507]:-ms-input-placeholder, input[type="text"][data-v-20c64507]:-ms-input-placeholder, input[type="email"][data-v-20c64507]:-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-20c64507]::-ms-input-placeholder, input[type="password"][data-v-20c64507]::-ms-input-placeholder, input[type="text"][data-v-20c64507]::-ms-input-placeholder, input[type="email"][data-v-20c64507]::-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-20c64507]::placeholder,\ninput[type="password"][data-v-20c64507]::placeholder,\ninput[type="text"][data-v-20c64507]::placeholder,\ninput[type="email"][data-v-20c64507]::placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[disabled][data-v-20c64507],\ninput[type="password"][disabled][data-v-20c64507],\ninput[type="text"][disabled][data-v-20c64507],\ninput[type="email"][disabled][data-v-20c64507] {\n  color: #A4ADB6;\n  cursor: not-allowed;\n}\n.additional-link[data-v-20c64507] {\n  font-size: 1em;\n  margin-top: 50px;\n  display: block;\n}\n.additional-link b[data-v-20c64507], .additional-link a[data-v-20c64507] {\n  cursor: pointer;\n}\n.additional-link b[data-v-20c64507]:hover, .additional-link a[data-v-20c64507]:hover {\n  text-decoration: underline;\n}\n@media only screen and (max-width: 960px) {\n.form .button[data-v-20c64507] {\n    margin-top: 20px;\n    width: 100%;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form input[data-v-20c64507], .form textarea[data-v-20c64507] {\n    width: 100%;\n    min-width: 100%;\n}\n.form.block-form .block-wrapper[data-v-20c64507] {\n    display: block;\n}\n.form.block-form .block-wrapper label[data-v-20c64507] {\n    width: 100%;\n    padding-right: 0;\n    display: block;\n    margin-bottom: 7px;\n    text-align: left !important;\n    font-size: 0.875em;\n    padding-top: 0;\n}\n.form.block-form .button[data-v-20c64507] {\n    margin-top: 25px;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form.inline-form[data-v-20c64507] {\n    display: block;\n}\n.form.inline-form .input-wrapper .error-message[data-v-20c64507] {\n    position: relative;\n    bottom: 0;\n}\n.form .button[data-v-20c64507] {\n    padding: 14px 32px;\n}\ntextarea[data-v-20c64507],\n  input[type="password"][data-v-20c64507],\n  input[type="text"][data-v-20c64507],\n  input[type="email"][data-v-20c64507] {\n    padding: 14px 20px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.form.block-form .block-wrapper label[data-v-20c64507] {\n    color: #bec6cf;\n}\ntextarea[data-v-20c64507],\n  input[type="password"][data-v-20c64507],\n  input[type="text"][data-v-20c64507],\n  input[type="email"][data-v-20c64507] {\n    background: #1e2024;\n    color: #bec6cf;\n}\ntextarea[data-v-20c64507]::-webkit-input-placeholder, input[type="password"][data-v-20c64507]::-webkit-input-placeholder, input[type="text"][data-v-20c64507]::-webkit-input-placeholder, input[type="email"][data-v-20c64507]::-webkit-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-20c64507]::-moz-placeholder, input[type="password"][data-v-20c64507]::-moz-placeholder, input[type="text"][data-v-20c64507]::-moz-placeholder, input[type="email"][data-v-20c64507]::-moz-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-20c64507]:-ms-input-placeholder, input[type="password"][data-v-20c64507]:-ms-input-placeholder, input[type="text"][data-v-20c64507]:-ms-input-placeholder, input[type="email"][data-v-20c64507]:-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-20c64507]::-ms-input-placeholder, input[type="password"][data-v-20c64507]::-ms-input-placeholder, input[type="text"][data-v-20c64507]::-ms-input-placeholder, input[type="email"][data-v-20c64507]::-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-20c64507]::placeholder,\n  input[type="password"][data-v-20c64507]::placeholder,\n  input[type="text"][data-v-20c64507]::placeholder,\n  input[type="email"][data-v-20c64507]::placeholder {\n    color: #7d858c;\n}\ntextarea[disabled][data-v-20c64507],\n  input[type="password"][disabled][data-v-20c64507],\n  input[type="text"][disabled][data-v-20c64507],\n  input[type="email"][disabled][data-v-20c64507] {\n    color: #7d858c;\n}\n}\n.auth-logo-text[data-v-20c64507] {\n  font-size: 1.375em;\n  font-weight: 800;\n  margin-bottom: 40px;\n  display: block;\n}\n.auth-form[data-v-20c64507] {\n  text-align: center;\n  max-width: 600px;\n  padding: 25px 20px;\n  display: table-cell;\n  vertical-align: middle;\n}\n.auth-form input[data-v-20c64507] {\n  min-width: 310px;\n}\n.auth-form .additional-link a[data-v-20c64507] {\n  font-weight: 700;\n  text-decoration: none;\n}\n.auth-form .user-avatar[data-v-20c64507] {\n  width: 100px;\n  height: 100px;\n  -o-object-fit: cover;\n     object-fit: cover;\n  margin-bottom: 20px;\n  border-radius: 8px;\n  box-shadow: 0 10px 30px rgba(25, 54, 60, 0.2);\n}\n.auth-form .logo[data-v-20c64507] {\n  width: 120px;\n  margin-bottom: 20px;\n}\n.auth-form h1[data-v-20c64507] {\n  font-size: 2.125em;\n  font-weight: 800;\n  line-height: 1.2;\n  margin-bottom: 2px;\n  color: #1B2539;\n}\n.auth-form h2[data-v-20c64507] {\n  font-size: 1.4375em;\n  font-weight: 500;\n  margin-bottom: 50px;\n  color: #1B2539;\n}\n.auth-form .block-form[data-v-20c64507] {\n  margin-left: auto;\n  margin-right: auto;\n}\n@media only screen and (min-width: 690px) and (max-width: 960px) {\n.auth-form[data-v-20c64507] {\n    padding-left: 20%;\n    padding-right: 20%;\n}\n}\n@media only screen and (max-width: 690px) {\n.auth-form[data-v-20c64507] {\n    width: 100%;\n}\n.auth-form h1[data-v-20c64507] {\n    font-size: 1.875em;\n}\n.auth-form h2[data-v-20c64507] {\n    font-size: 1.3125em;\n}\n}\n@media only screen and (max-width: 490px) {\n.auth-form h1[data-v-20c64507] {\n    font-size: 1.375em;\n}\n.auth-form h2[data-v-20c64507] {\n    font-size: 1.125em;\n}\n.auth-form input[data-v-20c64507] {\n    min-width: initial;\n}\n.auth-form .additional-link[data-v-20c64507] {\n    font-size: 0.9375em;\n}\n}\n@media (prefers-color-scheme: dark) {\n.auth-form h1[data-v-20c64507], .auth-form h2[data-v-20c64507], .auth-form .additional-link[data-v-20c64507] {\n    color: #bec6cf;\n}\n}\n.legal-agreement[data-v-20c64507] {\n  font-size: 1em;\n  padding: 55px 0 0;\n  max-width: 400px;\n  font-weight: 700;\n  line-height: 1.6;\n  margin: 0 auto;\n}\n',""])},vRHN:function(a,t,e){"use strict";e.r(t);var n=e("o0o1"),r=e.n(n),i=e("bDRN"),o=e("A5+z"),s=e("j8qy"),p=e("ASoH"),d=e("TJPC"),l=e("L2JU"),c=e("xCqy"),u=e("vDqi"),m=e.n(u);function v(a,t,e,n,r,i,o){try{var s=a[i](o),p=s.value}catch(a){return void e(a)}s.done?t(p):Promise.resolve(p).then(n,r)}function f(a,t){var e=Object.keys(a);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(a);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(a,t).enumerable}))),e.push.apply(e,n)}return e}function g(a,t,e){return t in a?Object.defineProperty(a,t,{value:e,enumerable:!0,configurable:!0,writable:!0}):a[t]=e,a}var h={name:"SignUp",components:{AuthContentWrapper:i.a,ValidationProvider:o.ValidationProvider,ValidationObserver:o.ValidationObserver,AuthContent:s.a,AuthButton:p.a,required:d.a},computed:function(a){for(var t=1;t<arguments.length;t++){var e=null!=arguments[t]?arguments[t]:{};t%2?f(Object(e),!0).forEach((function(t){g(a,t,e[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(a,Object.getOwnPropertyDescriptors(e)):f(Object(e)).forEach((function(t){Object.defineProperty(a,t,Object.getOwnPropertyDescriptor(e,t))}))}return a}({},Object(l.b)(["config"]),{privacyPolicy:function(){return this.config.legal.find((function(a){return"privacy-policy"===a.slug}))},termsOfService:function(){return this.config.legal.find((function(a){return"terms-of-service"===a.slug}))}}),data:function(){return{isLoading:!1,register:{name:"Peter",email:"john@doe.com",password:"vuefilemanager",password_confirmation:"vuefilemanager"}}},methods:{signUp:function(){var a,t=this;return(a=r.a.mark((function a(){return r.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return a.next=2,t.$refs.sign_up.validate();case 2:if(a.sent){a.next=5;break}return a.abrupt("return");case 5:t.isLoading=!0,m.a.post("/register",t.register).then((function(){t.isLoading=!1,t.$store.commit("SET_AUTHORIZED",!0),t.$router.push({name:"SetUpPlan"})})).catch((function(a){500==a.response.status&&c.a.$emit("alert:open",{emoji:"🤔",title:t.$t("popup_signup_error.title"),message:t.$t("popup_signup_error.message")}),422==a.response.status&&(a.response.data.errors.email&&t.$refs.sign_up.setErrors({"E-Mail":a.response.data.errors.email}),a.response.data.errors.password&&t.$refs.sign_up.setErrors({"Your New Password":a.response.data.errors.password})),t.isLoading=!1}));case 7:case"end":return a.stop()}}),a)})),function(){var t=this,e=arguments;return new Promise((function(n,r){var i=a.apply(t,e);function o(a){v(i,n,r,o,s,"next",a)}function s(a){v(i,n,r,o,s,"throw",a)}o(void 0)}))})()}},created:function(){this.$scrollTop()}},b=(e("cJb0"),e("KHd+")),w=Object(b.a)(h,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("AuthContentWrapper",{ref:"auth"},[e("AuthContent",{attrs:{name:"sign-up",visible:!0}},[a.config.app_logo?e("img",{staticClass:"logo",attrs:{src:a.$getImage(a.config.app_logo),alt:a.config.app_name}}):a._e(),a._v(" "),a.config.app_logo?a._e():e("b",{staticClass:"auth-logo-text"},[a._v(a._s(a.config.app_name))]),a._v(" "),e("h1",[a._v(a._s(a.$t("page_registration.title")))]),a._v(" "),e("h2",[a._v(a._s(a.$t("page_registration.subtitle")))]),a._v(" "),e("ValidationObserver",{ref:"sign_up",staticClass:"form block-form",attrs:{tag:"form"},on:{submit:function(t){return t.preventDefault(),a.signUp(t)}},scopedSlots:a._u([{key:"default",fn:function(t){t.invalid;return[e("div",{staticClass:"block-wrapper"},[e("label",[a._v(a._s(a.$t("page_registration.label_email")))]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"E-Mail",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var n=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.register.email,expression:"register.email"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:a.$t("page_registration.placeholder_email"),type:"email"},domProps:{value:a.register.email},on:{input:function(t){t.target.composing||a.$set(a.register,"email",t.target.value)}}}),a._v(" "),n[0]?e("span",{staticClass:"error-message"},[a._v(a._s(n[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v(a._s(a.$t("page_registration.label_name")))]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Full Name",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var n=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.register.name,expression:"register.name"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:a.$t("page_registration.placeholder_name"),type:"text"},domProps:{value:a.register.name},on:{input:function(t){t.target.composing||a.$set(a.register,"name",t.target.value)}}}),a._v(" "),n[0]?e("span",{staticClass:"error-message"},[a._v(a._s(n[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v(a._s(a.$t("page_registration.label_pass")))]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Your New Password",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var n=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.register.password,expression:"register.password"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:a.$t("page_registration.placeholder_pass"),type:"password"},domProps:{value:a.register.password},on:{input:function(t){t.target.composing||a.$set(a.register,"password",t.target.value)}}}),a._v(" "),n[0]?e("span",{staticClass:"error-message"},[a._v(a._s(n[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v(a._s(a.$t("page_registration.label_confirm_pass")))]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Confirm Your Password",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var n=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.register.password_confirmation,expression:"register.password_confirmation"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:a.$t("page_registration.placeholder_confirm_pass"),type:"password"},domProps:{value:a.register.password_confirmation},on:{input:function(t){t.target.composing||a.$set(a.register,"password_confirmation",t.target.value)}}}),a._v(" "),n[0]?e("span",{staticClass:"error-message"},[a._v(a._s(n[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",[e("i18n",{staticClass:"legal-agreement",attrs:{path:"page_registration.agreement",tag:"p"}},[e("router-link",{staticClass:"text-theme",attrs:{to:{name:"DynamicPage",params:{slug:"terms-of-service"}},target:"_blank"}},[a._v(a._s(a.termsOfService.title))]),a._v(" "),e("router-link",{staticClass:"text-theme",attrs:{to:{name:"DynamicPage",params:{slug:"privacy-policy"}},target:"_blank"}},[a._v(a._s(a.privacyPolicy.title))])],1),a._v(" "),e("AuthButton",{attrs:{icon:"chevron-right",text:a.$t("page_registration.button_create_account"),loading:a.isLoading,disabled:a.isLoading}})],1)]}}])}),a._v(" "),e("span",{staticClass:"additional-link"},[a._v(a._s(a.$t("page_registration.have_an_account"))+"\n            "),e("router-link",{staticClass:"text-theme",attrs:{to:{name:"SignIn"}}},[a._v("\n                "+a._s(a.$t("page_forgotten_password.password_remember_button"))+"\n            ")])],1)],1)],1)}),[],!1,null,"20c64507",null);t.default=w.exports}}]);