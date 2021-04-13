(window.webpackJsonp=window.webpackJsonp||[]).push([[38],{CH5q:function(t,e,a){var n=a("ydlg");"string"==typeof n&&(n=[[t.i,n,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,r);n.locals&&(t.exports=n.locals)},TXj0:function(t,e,a){"use strict";var n=a("CH5q");a.n(n).a},vRHN:function(t,e,a){"use strict";a.r(e);var n=a("o0o1"),r=a.n(n),d=a("bDRN"),i=a("A5+z"),o=a("j8qy"),s=a("ASoH"),p=a("TJPC"),l=a("L2JU"),c=a("xCqy"),u=a("vDqi"),m=a.n(u);function v(t,e,a,n,r,d,i){try{var o=t[d](i),s=o.value}catch(t){return void a(t)}o.done?e(s):Promise.resolve(s).then(n,r)}function f(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,n)}return a}function g(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var h={name:"SignUp",components:{AuthContentWrapper:d.a,ValidationProvider:i.ValidationProvider,ValidationObserver:i.ValidationObserver,AuthContent:o.a,AuthButton:s.a,required:p.a},computed:function(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?f(Object(a),!0).forEach((function(e){g(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):f(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}({},Object(l.b)(["config"]),{privacyPolicy:function(){return this.config.legal.find((function(t){return"privacy-policy"===t.slug}))},termsOfService:function(){return this.config.legal.find((function(t){return"terms-of-service"===t.slug}))}}),data:function(){return{isLoading:!1,register:{name:"",email:"",password:"",password_confirmation:""}}},methods:{signUp:function(){var t,e=this;return(t=r.a.mark((function t(){return r.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.$refs.sign_up.validate();case 2:if(t.sent){t.next=5;break}return t.abrupt("return");case 5:e.isLoading=!0,m.a.post("/register",e.register).then((function(){e.isLoading=!1,e.$store.commit("SET_AUTHORIZED",!0),e.$router.push({name:"SetUpPlan"})})).catch((function(t){500==t.response.status&&c.a.$emit("alert:open",{emoji:"🤔",title:e.$t("popup_signup_error.title"),message:e.$t("popup_signup_error.message")}),422==t.response.status&&(t.response.data.errors.email&&e.$refs.sign_up.setErrors({"E-Mail":t.response.data.errors.email}),t.response.data.errors.password&&e.$refs.sign_up.setErrors({"Your New Password":t.response.data.errors.password})),e.isLoading=!1}));case 7:case"end":return t.stop()}}),t)})),function(){var e=this,a=arguments;return new Promise((function(n,r){var d=t.apply(e,a);function i(t){v(d,n,r,i,o,"next",t)}function o(t){v(d,n,r,i,o,"throw",t)}i(void 0)}))})()}},created:function(){this.$scrollTop()}},b=(a("TXj0"),a("KHd+")),w=Object(b.a)(h,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("AuthContentWrapper",{ref:"auth"},[a("AuthContent",{attrs:{name:"sign-up",visible:!0}},[t.config.app_logo?a("img",{staticClass:"logo",attrs:{src:t.$getImage(t.config.app_logo),alt:t.config.app_name}}):t._e(),t._v(" "),t.config.app_logo?t._e():a("b",{staticClass:"auth-logo-text"},[t._v(t._s(t.config.app_name))]),t._v(" "),a("h1",[t._v(t._s(t.$t("page_registration.title")))]),t._v(" "),a("h2",[t._v(t._s(t.$t("page_registration.subtitle")))]),t._v(" "),a("ValidationObserver",{ref:"sign_up",staticClass:"form block-form",attrs:{tag:"form"},on:{submit:function(e){return e.preventDefault(),t.signUp(e)}},scopedSlots:t._u([{key:"default",fn:function(e){e.invalid;return[a("div",{staticClass:"block-wrapper"},[a("label",[t._v(t._s(t.$t("page_registration.label_email")))]),t._v(" "),a("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"E-Mail",rules:"required"},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.errors;return[a("input",{directives:[{name:"model",rawName:"v-model",value:t.register.email,expression:"register.email"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:t.$t("page_registration.placeholder_email"),type:"email"},domProps:{value:t.register.email},on:{input:function(e){e.target.composing||t.$set(t.register,"email",e.target.value)}}}),t._v(" "),n[0]?a("span",{staticClass:"error-message"},[t._v(t._s(n[0]))]):t._e()]}}],null,!0)})],1),t._v(" "),a("div",{staticClass:"block-wrapper"},[a("label",[t._v(t._s(t.$t("page_registration.label_name")))]),t._v(" "),a("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Full Name",rules:"required"},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.errors;return[a("input",{directives:[{name:"model",rawName:"v-model",value:t.register.name,expression:"register.name"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:t.$t("page_registration.placeholder_name"),type:"text"},domProps:{value:t.register.name},on:{input:function(e){e.target.composing||t.$set(t.register,"name",e.target.value)}}}),t._v(" "),n[0]?a("span",{staticClass:"error-message"},[t._v(t._s(n[0]))]):t._e()]}}],null,!0)})],1),t._v(" "),a("div",{staticClass:"block-wrapper"},[a("label",[t._v(t._s(t.$t("page_registration.label_pass")))]),t._v(" "),a("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Your New Password",rules:"required"},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.errors;return[a("input",{directives:[{name:"model",rawName:"v-model",value:t.register.password,expression:"register.password"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:t.$t("page_registration.placeholder_pass"),type:"password"},domProps:{value:t.register.password},on:{input:function(e){e.target.composing||t.$set(t.register,"password",e.target.value)}}}),t._v(" "),n[0]?a("span",{staticClass:"error-message"},[t._v(t._s(n[0]))]):t._e()]}}],null,!0)})],1),t._v(" "),a("div",{staticClass:"block-wrapper"},[a("label",[t._v(t._s(t.$t("page_registration.label_confirm_pass")))]),t._v(" "),a("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Confirm Your Password",rules:"required"},scopedSlots:t._u([{key:"default",fn:function(e){var n=e.errors;return[a("input",{directives:[{name:"model",rawName:"v-model",value:t.register.password_confirmation,expression:"register.password_confirmation"}],staticClass:"focus-border-theme",class:{"is-error":n[0]},attrs:{placeholder:t.$t("page_registration.placeholder_confirm_pass"),type:"password"},domProps:{value:t.register.password_confirmation},on:{input:function(e){e.target.composing||t.$set(t.register,"password_confirmation",e.target.value)}}}),t._v(" "),n[0]?a("span",{staticClass:"error-message"},[t._v(t._s(n[0]))]):t._e()]}}],null,!0)})],1),t._v(" "),a("div",[a("i18n",{staticClass:"legal-agreement",attrs:{path:"page_registration.agreement",tag:"p"}},[a("router-link",{staticClass:"text-theme",attrs:{to:{name:"DynamicPage",params:{slug:"terms-of-service"}},target:"_blank"}},[t._v(t._s(t.termsOfService.title))]),t._v(" "),a("router-link",{staticClass:"text-theme",attrs:{to:{name:"DynamicPage",params:{slug:"privacy-policy"}},target:"_blank"}},[t._v(t._s(t.privacyPolicy.title))])],1),t._v(" "),a("AuthButton",{attrs:{icon:"chevron-right",text:t.$t("page_registration.button_create_account"),loading:t.isLoading,disabled:t.isLoading}})],1)]}}])}),t._v(" "),a("span",{staticClass:"additional-link"},[t._v(t._s(t.$t("page_registration.have_an_account"))+"\n            "),a("router-link",{staticClass:"text-theme",attrs:{to:{name:"SignIn"}}},[t._v("\n                "+t._s(t.$t("page_forgotten_password.password_remember_button"))+"\n            ")])],1)],1)],1)}),[],!1,null,"c8d034d2",null);e.default=w.exports},ydlg:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,'.form.inline-form[data-v-c8d034d2] {\n  display: flex;\n  position: relative;\n  justify-content: center;\n  margin: 0 auto;\n}\n.form.inline-form .input-wrapper[data-v-c8d034d2] {\n  position: relative;\n}\n.form.inline-form .input-wrapper .error-message[data-v-c8d034d2] {\n  position: absolute;\n  left: 0;\n}\n.form.block-form.create-new-password .block-wrapper label[data-v-c8d034d2] {\n  width: 280px;\n}\n.form.block-form .block-wrapper[data-v-c8d034d2] {\n  display: flex;\n  align-items: center;\n  margin-top: 25px;\n  justify-content: center;\n}\n.form.block-form .block-wrapper[data-v-c8d034d2]:first-child {\n  margin-top: 0;\n}\n.form.block-form .block-wrapper label[data-v-c8d034d2] {\n  white-space: nowrap;\n  font-size: 1.125em;\n  font-weight: 700;\n  padding-right: 20px;\n  width: 200px;\n  text-align: right !important;\n  color: #1B2539;\n  text-align: right;\n}\n.form.block-form .button[data-v-c8d034d2] {\n  margin-top: 50px;\n}\n.input-wrapper .error-message[data-v-c8d034d2] {\n  font-size: 0.875em;\n  color: #fd397a;\n  padding-top: 5px;\n  display: block;\n  text-align: left;\n}\ntextarea[data-v-c8d034d2] {\n  width: 100%;\n}\ntextarea[data-v-c8d034d2],\ninput[type="password"][data-v-c8d034d2],\ninput[type="text"][data-v-c8d034d2],\ninput[type="email"][data-v-c8d034d2] {\n  background: #f4f5f6;\n  border: 1px solid transparent;\n  transition: 0.15s all ease;\n  font-size: 1em;\n  border-radius: 8px;\n  padding: 13px 20px;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  font-weight: 700;\n  outline: 0;\n  width: 100%;\n}\ntextarea.is-error[data-v-c8d034d2],\ninput[type="password"].is-error[data-v-c8d034d2],\ninput[type="text"].is-error[data-v-c8d034d2],\ninput[type="email"].is-error[data-v-c8d034d2] {\n  border-color: #fd397a;\n}\ntextarea[data-v-c8d034d2]::-webkit-input-placeholder, input[type="password"][data-v-c8d034d2]::-webkit-input-placeholder, input[type="text"][data-v-c8d034d2]::-webkit-input-placeholder, input[type="email"][data-v-c8d034d2]::-webkit-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-c8d034d2]::-moz-placeholder, input[type="password"][data-v-c8d034d2]::-moz-placeholder, input[type="text"][data-v-c8d034d2]::-moz-placeholder, input[type="email"][data-v-c8d034d2]::-moz-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-c8d034d2]:-ms-input-placeholder, input[type="password"][data-v-c8d034d2]:-ms-input-placeholder, input[type="text"][data-v-c8d034d2]:-ms-input-placeholder, input[type="email"][data-v-c8d034d2]:-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-c8d034d2]::-ms-input-placeholder, input[type="password"][data-v-c8d034d2]::-ms-input-placeholder, input[type="text"][data-v-c8d034d2]::-ms-input-placeholder, input[type="email"][data-v-c8d034d2]::-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-c8d034d2]::placeholder,\ninput[type="password"][data-v-c8d034d2]::placeholder,\ninput[type="text"][data-v-c8d034d2]::placeholder,\ninput[type="email"][data-v-c8d034d2]::placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[disabled][data-v-c8d034d2],\ninput[type="password"][disabled][data-v-c8d034d2],\ninput[type="text"][disabled][data-v-c8d034d2],\ninput[type="email"][disabled][data-v-c8d034d2] {\n  color: #A4ADB6;\n  cursor: not-allowed;\n}\n.additional-link[data-v-c8d034d2] {\n  font-size: 1em;\n  margin-top: 50px;\n  display: block;\n}\n.additional-link b[data-v-c8d034d2], .additional-link a[data-v-c8d034d2] {\n  cursor: pointer;\n}\n.additional-link b[data-v-c8d034d2]:hover, .additional-link a[data-v-c8d034d2]:hover {\n  text-decoration: underline;\n}\n@media only screen and (max-width: 960px) {\n.form .button[data-v-c8d034d2] {\n    margin-top: 20px;\n    width: 100%;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form input[data-v-c8d034d2], .form textarea[data-v-c8d034d2] {\n    width: 100%;\n    min-width: 100%;\n}\n.form.block-form .block-wrapper[data-v-c8d034d2] {\n    display: block;\n}\n.form.block-form .block-wrapper label[data-v-c8d034d2] {\n    width: 100%;\n    padding-right: 0;\n    display: block;\n    margin-bottom: 7px;\n    text-align: left !important;\n    font-size: 0.875em;\n    padding-top: 0;\n}\n.form.block-form .button[data-v-c8d034d2] {\n    margin-top: 25px;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form.inline-form[data-v-c8d034d2] {\n    display: block;\n}\n.form.inline-form .input-wrapper .error-message[data-v-c8d034d2] {\n    position: relative;\n    bottom: 0;\n}\n.form .button[data-v-c8d034d2] {\n    padding: 14px 32px;\n}\ntextarea[data-v-c8d034d2],\n  input[type="password"][data-v-c8d034d2],\n  input[type="text"][data-v-c8d034d2],\n  input[type="email"][data-v-c8d034d2] {\n    padding: 14px 20px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.form.block-form .block-wrapper label[data-v-c8d034d2] {\n    color: #bec6cf;\n}\ntextarea[data-v-c8d034d2],\n  input[type="password"][data-v-c8d034d2],\n  input[type="text"][data-v-c8d034d2],\n  input[type="email"][data-v-c8d034d2] {\n    background: #1e2024;\n    color: #bec6cf;\n}\ntextarea[data-v-c8d034d2]::-webkit-input-placeholder, input[type="password"][data-v-c8d034d2]::-webkit-input-placeholder, input[type="text"][data-v-c8d034d2]::-webkit-input-placeholder, input[type="email"][data-v-c8d034d2]::-webkit-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-c8d034d2]::-moz-placeholder, input[type="password"][data-v-c8d034d2]::-moz-placeholder, input[type="text"][data-v-c8d034d2]::-moz-placeholder, input[type="email"][data-v-c8d034d2]::-moz-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-c8d034d2]:-ms-input-placeholder, input[type="password"][data-v-c8d034d2]:-ms-input-placeholder, input[type="text"][data-v-c8d034d2]:-ms-input-placeholder, input[type="email"][data-v-c8d034d2]:-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-c8d034d2]::-ms-input-placeholder, input[type="password"][data-v-c8d034d2]::-ms-input-placeholder, input[type="text"][data-v-c8d034d2]::-ms-input-placeholder, input[type="email"][data-v-c8d034d2]::-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-c8d034d2]::placeholder,\n  input[type="password"][data-v-c8d034d2]::placeholder,\n  input[type="text"][data-v-c8d034d2]::placeholder,\n  input[type="email"][data-v-c8d034d2]::placeholder {\n    color: #7d858c;\n}\ntextarea[disabled][data-v-c8d034d2],\n  input[type="password"][disabled][data-v-c8d034d2],\n  input[type="text"][disabled][data-v-c8d034d2],\n  input[type="email"][disabled][data-v-c8d034d2] {\n    color: #7d858c;\n}\n}\n.auth-logo-text[data-v-c8d034d2] {\n  font-size: 1.375em;\n  font-weight: 800;\n  margin-bottom: 40px;\n  display: block;\n}\n.auth-form[data-v-c8d034d2] {\n  text-align: center;\n  max-width: 600px;\n  padding: 25px 20px;\n  display: table-cell;\n  vertical-align: middle;\n}\n.auth-form input[data-v-c8d034d2] {\n  min-width: 310px;\n}\n.auth-form .additional-link a[data-v-c8d034d2] {\n  font-weight: 700;\n  text-decoration: none;\n}\n.auth-form .user-avatar[data-v-c8d034d2] {\n  width: 100px;\n  height: 100px;\n  -o-object-fit: cover;\n     object-fit: cover;\n  margin-bottom: 20px;\n  border-radius: 8px;\n  box-shadow: 0 10px 30px rgba(25, 54, 60, 0.2);\n}\n.auth-form .logo[data-v-c8d034d2] {\n  width: 120px;\n  margin-bottom: 20px;\n}\n.auth-form h1[data-v-c8d034d2] {\n  font-size: 2.125em;\n  font-weight: 800;\n  line-height: 1.2;\n  margin-bottom: 2px;\n  color: #1B2539;\n}\n.auth-form h2[data-v-c8d034d2] {\n  font-size: 1.4375em;\n  font-weight: 500;\n  margin-bottom: 50px;\n  color: #1B2539;\n}\n.auth-form .block-form[data-v-c8d034d2] {\n  margin-left: auto;\n  margin-right: auto;\n}\n@media only screen and (min-width: 690px) and (max-width: 960px) {\n.auth-form[data-v-c8d034d2] {\n    padding-left: 20%;\n    padding-right: 20%;\n}\n}\n@media only screen and (max-width: 690px) {\n.auth-form[data-v-c8d034d2] {\n    width: 100%;\n}\n.auth-form h1[data-v-c8d034d2] {\n    font-size: 1.875em;\n}\n.auth-form h2[data-v-c8d034d2] {\n    font-size: 1.3125em;\n}\n}\n@media only screen and (max-width: 490px) {\n.auth-form h1[data-v-c8d034d2] {\n    font-size: 1.375em;\n}\n.auth-form h2[data-v-c8d034d2] {\n    font-size: 1.125em;\n}\n.auth-form input[data-v-c8d034d2] {\n    min-width: initial;\n}\n.auth-form .additional-link[data-v-c8d034d2] {\n    font-size: 0.9375em;\n}\n}\n@media (prefers-color-scheme: dark) {\n.auth-form h1[data-v-c8d034d2], .auth-form h2[data-v-c8d034d2], .auth-form .additional-link[data-v-c8d034d2] {\n    color: #bec6cf;\n}\n}\n.legal-agreement[data-v-c8d034d2] {\n  font-size: 1em;\n  padding: 55px 0 0;\n  max-width: 400px;\n  font-weight: 700;\n  line-height: 1.6;\n  margin: 0 auto;\n}\n',""])}}]);